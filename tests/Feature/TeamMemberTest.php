<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use App\Services\Members;
use App\Services\Team;
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
            ->assertSee('<li>Member 1 (12345)</li>', false);
    }

    public function testCanNotAddUnknownTeamMember(): void
    {
        $team = $this->app->make(Team::class);
        $team->addNew();
        $this
            ->post(route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => 1, 'id' => 1]))
            ->assertNotFound();
    }
}
