#!/usr/bin/env php
<?php

require_once './vendor/autoload.php';

/**
 * Lint Blade files
 *
 * - Check for invalid interpolation markers
 * - Check for invalid @var annotations
 * - Check for unused Blade files
 */

/**
 * CONFIG
 */

// framework specific blade files to ignore
$ignoreMissingBladeFile = ['errors.500', 'mail.created-user-mail'];

/**
 * LOGIC
 */

$path = trim($argv[1] ?? null, '/') . '/';

if ($path === '/') {
    exitWithCode('Usage: php ' . $argv[0] . ' <path>', 1);
}

echo "\033[0;32m\n";
echo "Linting Blade files in $path\n";
echo "\033[0m\n";

$hasError = false;
$bladeFiles = [];

foreach (regexRecurseIterator($path, '/\.blade\.php$/') as $file) {
    $content = file_get_contents($file->getPathname());
    // remove the $path prefix from the pathname
    $bladeName = str_replace('/', '.', substr($file->getPathname(), strlen($path), strlen('.blade.php') * -1));
    $bladeFiles[] = $bladeName;

    // Check for invalid interpolation markers
    if (preg_match('/(\{\{|\{!!)(?!-- )(\S)/', $content, $match)) {
        echo "\033[0;31m";
        echo "[$bladeName] Invalid interpolation markers: Should be a single space before and after!\n";
        echo "\033[0m";
        $hasError = true;
    }

    // Check for invalid @var annotations
    if (preg_match('/\* @var \$/', $content)) {
        echo "\033[0;31m";
        echo "[$bladeName] Invalid @var annotations: Should be @var [type] [name]\n";
        echo "\033[0m";
        $hasError = true;
    }
}


$bladeReferences = findBladeReferences($path, 'app');
$diff = array_diff($bladeFiles, $bladeReferences, $ignoreMissingBladeFile);
if (!empty($diff)) {
    echo "\033[0;31m";
    foreach ($diff as $file) {
        echo "[$file] Unused file\n";
    }
    echo "\033[0m";
    $hasError = true;
}

if ($hasError) {
    exitWithCode("Found issues in $path", 2);
}

exitWithCode("No issues found in $path", 0);

/**
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function exitWithCode(string $message, int $code): never
{
    echo match ($code) {
        1 => "\033[0;33m\n$message\n\033[0m\n",
        2 => "\033[0;31m\n$message\n\033[0m\n",
        0 => "\033[0;32m\n$message\n\033[0m\n",
        default => "$message\n",
    };
    exit($code);
}

/**
 * find references to blade files, i.e. view(), @include, @extends, <x- />
 *
 * @return string[]
 */
function findBladeReferences(string $path, string $srcPath): array
{
    $references = [];

    // scan all the blade files in $path and find instances of @extends, @include, <x- />

    foreach (regexRecurseIterator($path, '/\.blade\.php$/') as $file) {
        $content = file_get_contents($file->getPathname());
        if (preg_match_all("/@include\('([\w.]+)/", $content, $matches, PREG_SET_ORDER)) {
            $references = array_merge($references, array_map(fn ($match) => $match[1], $matches));
        }
        if (preg_match_all("/@extends\('([\w.]+)/", $content, $matches, PREG_SET_ORDER)) {
            $references = array_merge($references, array_map(fn ($match) => $match[1], $matches));
        }
        if (preg_match_all("/<x-([\w.\-]+)/", $content, $matches, PREG_SET_ORDER)) {
            $references = array_merge($references, array_map(fn ($match) => 'components.' . $match[1], $matches));
        }
    }

    // scan the $srcPath and find all instances of view()

    foreach (regexRecurseIterator($srcPath, '/\.php$/') as $file) {
        $content = file_get_contents($file->getPathname());
        if (preg_match_all("/view\(\s*'([\w.\-]+)/x", $content, $matches, PREG_SET_ORDER)) {
            $references = array_merge($references, array_map(fn ($matches) => $matches[1], $matches));
        }
    }

    return array_unique($references);
}

function regexRecurseIterator(string $path, string $regex): RegexIterator
{
    return new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)), $regex);
}
