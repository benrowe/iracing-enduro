<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use App\Services\Team;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function testAddTeam(): void
    {
        $this
            ->post(route(RouteNames::TEAMS_ADD))
            ->assertOk()
            ->assertSee('Team 1');

        $this
            ->post(route(RouteNames::TEAMS_ADD))
            ->assertOk()
            ->assertSee('Team 1')
            ->assertSee('Team 2');
    }

    public function testDeleteExistingTeam(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();
        $this
            ->delete(route(RouteNames::TEAMS_DELETE, ['index' => 1]))
            ->assertOk()
            ->assertDontSee('Team 1');
    }

    public function testDeleteUnknownTeam(): void
    {
        $this
            ->delete(route(RouteNames::TEAMS_DELETE, ['index' => 1]))
            ->assertNotFound();
    }
}
