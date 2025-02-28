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

readonly class TeamController
{
    public function __construct(private TeamsRenderer $renderer)
    {
    }

    #[HttpRoute(RouteNames::DASHBOARD)]
    public function index(): View
    {
        return $this->renderer->render();
    }

    #[HttpRoute(RouteNames::TEAMS_ADD)]
    public function add(Team $team): View
    {
        $team->addNew();
        return $this->renderer->render();
    }

    #[HttpRoute(RouteNames::TEAMS_DELETE)]
    public function delete(Team $team, int $index): View
    {
        try {
            $team->delete($index - 1);
        } catch (TeamException) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $this->renderer->render();
    }

    #[HttpRoute(RouteNames::TEAMS_AUTO_ALLOCATE)]
    public function autoAllocate(Team $team): View
    {
        $team->autoAllocateNewMembers();
        return $this->renderer->render();
    }
}
