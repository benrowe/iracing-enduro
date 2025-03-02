<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use App\Exceptions\TeamException;
use App\Services\Members;
use App\Services\Team;
use App\Services\TeamEntity;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamMemberTest extends TestCase
{
    public function testAddTeamMember(): void
    {
        $this->mock(Members::class, static function (MockInterface $mock): void {
            $mock
                ->shouldReceive('getIds')
                ->once()
                ->andReturn([1]);
            $mock
                ->shouldReceive('getAugmented')
                ->once()
                ->andReturn(['1' => ['name' => 'Member 1', 'irating' => 12345]]);
        });
        $team = $this->app->make(Team::class);
        $team->addNew();
        $this
            ->post(route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => 1, 'id' => 1]))
            ->assertOk()
            ->assertSee('Member 1 (12345)', false);
    }

    public function testCanNotAddUnknownTeamMember(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();
        $this
            ->post(route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => 1, 'id' => 1]))
            ->assertNotFound();
    }

    public function testCanNotAddExistingMemberToTeam(): void
    {
        $this->mock(Members::class, static function (MockInterface $mock): void {
            $mock
                ->shouldReceive('getIds')
                ->atLeast()
                ->once()
                ->andReturn([1]);
        });
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addMember(0, 1);
        $this
            ->post(route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => 1, 'id' => 1]))
            ->assertConflict();
    }

    public function testUnknownTeamExceptionIsPassedThrough(): void
    {
        Cache::put('teams', [new TeamEntity([1])]);
        $this->mock(Members::class, static function (MockInterface $mock): void {
            $mock
                ->shouldReceive('getIds')
                ->atLeast()
                ->once()
                ->andThrow(new TeamException('foo'));
        });
        $this
            ->post(route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => 1, 'id' => 1]))
            ->assertServerError();
    }

    public function testDeleteTeamMember(): void
    {
        Cache::put('teams', [new TeamEntity([1])]);
        Cache::put('members', [1 => ['name' => 'Member 1', 'irating' => 12345]]);
        $this
            ->delete(route(RouteNames::TEAMS_MEMBERS_DELETE, ['index' => 1, 'id' => 1]))
            ->assertOk();

        $team = Cache::get('teams')[0];
        $this->assertCount(0, $team->members);
    }

    public function testDeleteTeamMemberNotFound(): void
    {
        $this
            ->delete(route(RouteNames::TEAMS_MEMBERS_DELETE, ['index' => 1, 'id' => 1]))
            ->assertNotFound();
    }

    public function testDeleteUnknownTeamExceptionIsPassedThrough(): void
    {
        $this->partialMock(Team::class, static function (MockInterface $mock): void {
            $mock
                ->shouldReceive('getTeam')
                ->once()
                ->andThrow(new TeamException('foo'));
        });
        $this
            ->delete(route(RouteNames::TEAMS_MEMBERS_DELETE, ['index' => 1, 'id' => 1]))
            ->assertServerError();
    }
}
