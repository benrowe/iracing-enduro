<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;
use BackedEnum;

/**
 * @codeCoverageIgnore
 */
#[Attribute(Attribute::TARGET_METHOD)]
class HttpRoute
{
    public function __construct(public BackedEnum $route)
    {
    }
}
