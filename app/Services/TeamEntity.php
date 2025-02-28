<?php

declare(strict_types=1);

namespace App\Services;

readonly class TeamEntity
{
    /**
     * @param int[] $members
     */
    public function __construct(public array $members = [])
    {
    }
}
