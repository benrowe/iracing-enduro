<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Renderers\TeamsRenderer;
use App\Services\Team;
use Illuminate\View\View;

class TeamMemberController
{
    public function __construct(private readonly TeamsRenderer $renderer)
    {
    }

    #[HttpRoute(RouteNames::TEAMS_MEMBERS_STORE)]
    public function store(int $index, int $id, Team $team): View
    {
        $teamIndex = $index - 1;
        $memberId = $id;
        $team->addMember($teamIndex, $memberId);
        return $this->renderer->render();
    }
}
