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
        $unallocatedMembers = array_diff_key($members, $allocatedMembers);

        return view('teams', compact('members', 'teams', 'unallocatedMembers'));
    }
}
