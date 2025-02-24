<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Services\Members;
use App\Services\Team;
use Illuminate\View\View;

class TeamController extends Controller
{
    #[HttpRoute(RouteNames::DASHBOARD)]
    public function index(Members $members, Team $team): View
    {
        $ratings = $members->getAugmented();

        $memberRatings = array_map(static fn (array $member) => $member['irating'], $ratings);

        [$team1, $team2] = $team->splitListWithIndexes($memberRatings);

        return view('teams', compact('team1', 'team2', 'ratings'));
    }
}
