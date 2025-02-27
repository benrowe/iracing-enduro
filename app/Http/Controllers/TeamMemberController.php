<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Exceptions\TeamException;
use App\Renderers\TeamsRenderer;
use App\Services\Team;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TeamMemberController
{
    public function __construct(private readonly TeamsRenderer $renderer)
    {
    }

    /**
     * @throws TeamException
     */
    #[HttpRoute(RouteNames::TEAMS_MEMBERS_STORE)]
    public function store(int $index, int $id, Team $team): View
    {
        $teamIndex = $index - 1;
        $memberId = $id;
        try {
            $team->addMember($teamIndex, $memberId);
        } catch (TeamException $e) {
            if (str_contains($e->getMessage(), 'not found')) {
                abort(Response::HTTP_NOT_FOUND);
            }
            if (str_contains($e->getMessage(), 'already exists')) {
                abort(Response::HTTP_CONFLICT);
            }
            throw $e;
        }
        return $this->renderer->render();
    }
}
