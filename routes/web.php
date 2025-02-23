<?php

use Illuminate\Support\Facades\Route;
use iRacingPHP\iRacing;

Route::get('/', function () {
    echo '<h1> Same Day Racing Enduro Tools!!</h1>';
    echo '<h2>WIP</h2>';
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
    // build a list of the users irating
//    $summary = $iracing->member->info();
    dd($rating);
});
