<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use iRacingPHP\iRacing;

class Members
{

    public function get(): array
    {
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
        return Cache::rememberForever('members', function () use ($members) {
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
    }
}
