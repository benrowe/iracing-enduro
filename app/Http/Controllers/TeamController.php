<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Renderers\TeamsRenderer;
use App\Services\Team;
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

    public function add(Team $team): View
    {
        $team->addNew();
        return $this->renderer->render();
    }

    public function delete(Team $team, int $index): View
    {
        $team->delete($index - 1);
        return $this->renderer->render();
    }

    public function autoAllocate(Team $team): View
    {
        // add the un-allocated members to the existing teams by averaging out their rating
        return $this->renderer->render();
    }
}
