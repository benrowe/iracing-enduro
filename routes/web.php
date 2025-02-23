<?php

use App\Services\Members;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Members $members) {

    $ratings = $members->getAugmented();
    usort($ratings, function ($a, $b) {
        return $b['irating'] <=> $a['irating'];
    });
    // Example usage
    $nums = array_map(fn ($rat) => $rat['irating'], $ratings);

    [$team1, $team2] = splitListWithIndexes($nums);

    return view('welcome', compact('team1', 'team2', 'ratings'));
});

Route::get('/settings', function (Members $members) {
    return view('settings', [
        'members' => $members->getAugmented()
    ]);
});
Route::post('/settings/add', function (Members $members, \Illuminate\Http\Request $request) {
    $members->add($request->memberId);
    return redirect('/settings');

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
