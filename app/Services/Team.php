<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TeamException;
use Illuminate\Support\Facades\Cache;

class Team
{
    public function __construct(private Members $members)
    {
    }

    public function addNew(): void
    {
        $teams = $this->getTeams();
        $teams[] = new TeamEntity();
        Cache::put('teams', $teams);
        return;
    }

    /**
     * @return TeamEntity[]
     */
    public function getTeams(): array
    {
        return Cache::get('teams', []);
    }

    public function reset(): void
    {
        Cache::forget('teams');
    }

    /**
     * @throws TeamException
     */
    public function delete(int $index): void
    {
        $teams = $this->getTeams();
        if (!isset($teams[$index])) {
            throw new TeamException('Team not found');
        }
        unset($teams[$index]);
        Cache::put('teams', array_values($teams));
    }

    /**
     * @return string[]
     */
    public function getAllocatedMembers(): array
    {
        $teams = $this->getTeams();
        return collect($teams)
            ->flatMap(static fn (TeamEntity $team) => $team->members)
            ->toArray();
    }

    /**
     * @throws TeamException
     */
    public function addMember(int $teamIndex, int $memberId): void
    {
        $team = $this->getTeam($teamIndex);

        if (!$team) {
            throw new TeamException('Team not found');
        }
        if (!in_array($memberId, $this->members->getIds(), true)) {
            throw new TeamException('Member not found');
        }

        if (in_array($memberId, $this->getAllocatedMembers(), true)) {
            throw new TeamException('Member already exists in a team');
        }

        $team = new TeamEntity(array_merge($team->members, [$memberId]));
        $teams = $this->getTeams();

        $teams[$teamIndex] = $team;
        Cache::put('teams', $teams);
    }



    private function getTeam(int $index): ?TeamEntity
    {
        $teams = $this->getTeams();
        return $teams[$index] ?? null;
    }
}
