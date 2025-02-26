<?php

declare(strict_types=1);

namespace App\Services;

readonly class TeamEntity
{
    public function __construct(public array $members = [])
    {
    }
}
