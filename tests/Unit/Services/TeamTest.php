<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\TeamException;
use App\Services\Team;
use App\Services\TeamEntity;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function testCanAddBlankTeam(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();

        $teams = $team->getTeams();
        $this->assertCount(1, $teams);
        $this->assertCount(1, Cache::get('teams'));
        $this->assertCount(0, $teams[0]->members);
    }

    public function testCanGetTeams(): void
    {
        $team = $this->app->make(Team::class);
        $this->assertCount(0, $team->getTeams());

        $team->addNew();
        $this->assertCount(1, $team->getTeams());
    }

    public function testResetTeams(): void
    {
        Cache::put('teams', ['team1', 'team2']);

        $team = $this->app->make(Team::class);
        $team->reset();

        $this->assertCount(0, $team->getTeams());
    }

    public function testDeleteTeam(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->delete(0);

        $this->assertCount(0, $team->getTeams());
    }

    public function testDeleteUnknownTeamThrowsError()
    {
        $team = $this->app->make(Team::class);
        $this->expectException(TeamException::class);
        $this->expectExceptionMessage('Team not found');
        $team->delete(0);
    }

    public function testCanGetListOfAllAllocatedMembers(): void
    {
        Cache::put('teams', [
            new TeamEntity([1, 2, 3]),
            new TeamEntity([4, 5, 6]),
        ]);
        $team = $this->app->make(Team::class);

        $this->assertEquals([1, 2, 3, 4, 5, 6], $team->getAllocatedMembers());
    }

    public function testCantAddMemberToNonExistingTeam(): void
    {
        $team = $this->app->make(Team::class);
        $this->expectException(TeamException::class);
        $this->expectExceptionMessage('Team not found');
        $team->addMember(0, 1);
    }

    public function testCanAddMemberToTeam(): void
    {
        Cache::put('memberIds', [1]);
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addMember(0, 1);
        $this->assertSame(1, $team->getTeams()[0]->members[0]);
    }

    public function testCantAssignMemberAlreadyAllocatedToSameTeam(): void
    {
        $this->expectException(TeamException::class);
        $this->expectExceptionMessage('Member already exists in a team');
        Cache::put('memberIds', [1]);
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addMember(0, 1);
        $team->addMember(0, 1);
    }

    public function testCantAssignMemberAlreadyAllocatedToDifferentTeam(): void
    {
        $this->expectException(TeamException::class);
        $this->expectExceptionMessage('Member already exists in a team');
        Cache::put('memberIds', [1]);
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addNew();
        $team->addMember(0, 1);
        $team->addMember(1, 1);
    }

    public function testCantAssignUnknownMember(): void
    {
        $this->expectException(TeamException::class);
        $this->expectExceptionMessage('Member not found');
        $team = $this->app->make(Team::class);
        $team->addNew();
        $team->addMember(0, 1);
    }
}

