<?php

declare(strict_types=1);

namespace App\Renderers;

use App\Services\Members;
use App\Services\Team;
use Illuminate\View\View;

class TeamsRenderer
{
    public function __construct(private readonly Members $memberService, private readonly Team $teamService)
    {
    }

    public function render(): View
    {
        $members = $this->memberService->getAugmented();
        $teams = $this->teamService->getTeams();
        $allocatedMembers = $this->teamService->getAllocatedMembers();
        $unallocatedMembers = $this->getUnallocatedMembers($members, $allocatedMembers);

        return view('teams', compact('members', 'teams', 'unallocatedMembers'));
    }

    /**
     * @param array<int, array{name: string, irating: int}> $members
     * @param int[] $allocatedMembers
     * @return array<int, array{name: string, irating: int}>
     */
    private function getUnallocatedMembers(array $members, array $allocatedMembers): array
    {
        return collect($members)
            ->only(array_diff(array_keys($members), $allocatedMembers))
            ->toArray();
    }
}
