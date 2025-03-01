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
        $teams[] = $this->entity([]);
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
     * @return int[]
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

        $team = $this->entity(array_merge($team->members, [$memberId]));
        $teams = $this->getTeams();

        $teams[$teamIndex] = $team;
        Cache::put('teams', $teams);
    }

    public function autoAllocateNewMembers(): void
    {
        $teams = array_map(static fn (TeamEntity $team) => $team->members, $this->getTeams());
        $existingMembers = $this->getAllocatedMembers();
        $allMembers = $this->members->getIds();

        $newMembers = array_diff($allMembers, $existingMembers);

        $newTeams = $this->allocateNewTeamMembers($teams, $newMembers);

        Cache::put('teams', array_map($this->entity(...), $newTeams));
    }

    /**
     * @throws TeamException
     */
    public function deleteMember(int $teamIndex, int $memberId): void
    {
        $team = $this->getTeam($teamIndex);

        if (!$team) {
            throw new TeamException('Team not found');
        }

        // is this member actually in the team?
        if (!in_array($memberId, $team->members, true)) {
            throw new TeamException('Member not found');
        }
        $team = $this->entity(array_diff($team->members, [$memberId]));
        $teams = $this->getTeams();
        $teams[$teamIndex] = $team;
        Cache::put('teams', $teams);
    }

    public function getTeam(int $index): ?TeamEntity
    {
        $teams = $this->getTeams();
        return $teams[$index] ?? null;
    }

    /**
     * @param int[][] $teams
     * @param int[] $newMembers
     * @return int[][]
     * @throws TeamException
     */
    private function allocateNewTeamMembers(array $teams, array $newMembers): array
    {
        if ($teams === []) {
            throw new TeamException('No teams to allocate members to');
        }

        if ($newMembers === []) {
            return $teams;
        }
        // Calculate current team averages
        $currentTeamAverages = array_map(static function ($team) {
            if ($team === []) {
                return 0;
            }
            return array_sum($team) / count($team);
        }, $teams);

        foreach ($newMembers as $name => $value) {
            // Find the team with the lowest average
            $lowestIndex = array_search(min($currentTeamAverages), $currentTeamAverages, true);

            // Add the member to the team
            $teams[$lowestIndex][$name] = $value;

            // Recalculate the average for this team
            $currentTeamAverages[$lowestIndex] = array_sum($teams[$lowestIndex]) / count($teams[$lowestIndex]);
        }

        return array_map('array_values', $teams);
    }

    /**
     * @param int[] $members
     */
    private function entity(array $members): TeamEntity
    {
        return new TeamEntity(array_map('intval', $members));
    }
}
