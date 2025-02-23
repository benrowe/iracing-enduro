<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use iRacingPHP\iRacing;

function printTeam(string $label, array $list1, mixed $rating): void
{
    echo "$label:<br>";
    echo '<ul>';
    foreach ($list1 as $acc => $value) {
        echo '<li>' . $rating[$acc]['name'] . ' (' . $value . ')</li>';
    }
    echo '</ul>';
    echo "Avg: " . (array_sum($list1) / count($list1)) . "<br><br>";
}

Route::get('/', function () {

    $members = [
        '121405',
        '480098',
        '135304',
        '900334',
        '52908',
        '20489',
        '528036',
        '932876'
    ];


    $ratings = Cache::rememberForever('members', function () use ($members) {
        $cfg = config('app.iracing');
        $iracing = new iRacing($cfg['email'], $cfg['password']);
        $rating = [];
        foreach ($members as $accountId) {

            $member = $iracing->member->profile(['cust_id' => $accountId]);

            $license = collect($member->license_history)->first(fn ($license) => $license->category === 'sports_car');

            $rating[$accountId] = [
                'name' => $member->member_info->display_name,
                'irating' => $license->irating,
            ];
        }
        return $rating;
    });
    usort($ratings, function ($a, $b) {
        return $b['irating'] <=> $a['irating'];
    });
    // Example usage
    $nums = array_map(fn ($rat) => $rat['irating'], $ratings);

    [$team1, $team2] = splitListWithIndexes($nums);

    return view('welcome', compact('team1', 'team2', 'ratings'));
});

Route::get('/refresh', function () {
    Cache::forget('members');
    return redirect('/');
});


function splitListWithIndexes(array $nums): array {
    arsort($nums); // Sort by value while keeping indexes
    $totalSum = array_sum($nums);
    $target = $totalSum / 2;

    $bestDiff = PHP_INT_MAX;
    $bestSubset = [];

    $indexes = array_keys($nums);
    $n = count($nums);
    $totalCombinations = 1 << $n; // 2^n subsets

    // Try all subsets (brute force approach)
    for ($i = 1; $i < $totalCombinations; $i++) {
        $subset = [];
        $subsetSum = 0;

        for ($j = 0; $j < $n; $j++) {
            if ($i & (1 << $j)) {
                $idx = $indexes[$j];
                $subset[$idx] = $nums[$idx];
                $subsetSum += $nums[$idx];
            }
        }

        $diff = abs($target - $subsetSum);
        if ($diff < $bestDiff) {
            $bestDiff = $diff;
            $bestSubset = $subset;
        }
    }

    // Split lists
    $list1 = $bestSubset;
    $list2 = $nums;

    foreach (array_keys($list1) as $idx) {
        unset($list2[$idx]);
    }

    return [$list1, $list2];
}
