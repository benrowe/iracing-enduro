<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Team
{
    public function addNew(): void
    {

    }

    public function getTeams(): array
    {
        return Cache::get('teams', []);
    }
public function splitListWithIndexes(array $nums): array
{
arsort($nums); // Sort by value while keeping indexes
$totalSum = array_sum($nums);
$target = $totalSum / 2;

$bestDiff = PHP_INT_MAX;
$bestSubset = [];

$indexes = array_keys($nums);
$n = count($nums);
$totalCombinations = 1 << $n; // 2^n subsets

    // Try all subsets (brute force approach)
for ($i = 1; $i < $totalCombinations; $i++) {
$subset = [];
$subsetSum = 0;

for ($j = 0; $j < $n; $j++) {
if ($i & (1 << $j)) {
$idx = $indexes[$j];
$subset[$idx] = $nums[$idx];
$subsetSum += $nums[$idx];
}
}

$diff = abs($target - $subsetSum);
if ($diff < $bestDiff) {
    $bestDiff = $diff;
    $bestSubset = $subset;
}
}

// Split lists
$list1 = $bestSubset;
$list2 = $nums;

foreach (array_keys($list1) as $idx) {
    unset($list2[$idx]);
}

return [$list1, $list2];
}
}
