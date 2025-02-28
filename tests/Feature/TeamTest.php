<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use App\Services\Team;
use App\Services\TeamEntity;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function testTeamPage(): void
    {
        $this
            ->get(route(RouteNames::DASHBOARD))
            ->assertOk();

        // add some members
        Cache::put('memberIds', [1, 2]);
        Cache::put('members', [
            '1' => ['name' => 'Member 1', 'irating' => 12345],
            '2' => ['name' => 'Member 2', 'irating' => 67890]]);

        $this
            ->get(route(RouteNames::DASHBOARD))
            ->assertOk()
            ->assertSee('Member 1 - 12345')
            ->assertSee('Member 2 - 67890');

        // Add teams

        Cache::put('teams', [new TeamEntity(), new TeamEntity()]);
        $this
            ->get(route(RouteNames::DASHBOARD))
            ->assertOk()
            ->assertSee('Member 1 - 12345')
            ->assertSee('Member 2 - 67890')
            ->assertSee('Team 1')
            ->assertSee('Team 2');
    }

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

    public function testAutoAllocate(): void
    {
        Cache::put('memberIds', [1, 2, 3, 4, 5]);
        Cache::put('teams', [new TeamEntity(), new TeamEntity()]);
        Cache::put('members', [
            '1' => ['name' => 'Member 1', 'irating' => 1234],
            '2' => ['name' => 'Member 2', 'irating' => 1902],
            '3' => ['name' => 'Member 3', 'irating' => 1530],
            '4' => ['name' => 'Member 4', 'irating' => 2345],
            '5' => ['name' => 'Member 5', 'irating' => 6789],
        ]);
        $this
            ->post(route(RouteNames::TEAMS_AUTO_ALLOCATE))
            ->assertOk()
            // ensure the members are rendered into the team list somewhere
            ->assertSee('Member 1 (1234)')
            ->assertSee('Member 2 (1902)')
            ->assertSee('Member 3 (1530)')
            ->assertSee('Member 4 (2345)')
            ->assertSee('Member 5 (6789)');

        // Assert the teams state
        $teams = Cache::get('teams');
        // assert the count of the team members = 5
        $this->assertCount(5, $teams[0]->members + $teams[1]->members);
    }

    public function testCanResetTeams(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addNew();
        $this
            ->post(route(RouteNames::TEAMS_RESET))
            ->assertOk()
            ->assertDontSee('Team 1');
    }
}
