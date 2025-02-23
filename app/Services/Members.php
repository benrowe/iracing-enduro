<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use iRacingPHP\iRacing;

class Members
{
    public function __construct(private iRacing $iracing)
    {
    }

    public function add(string $memberId): void
    {
        $argumented = $this->getAugmented();
        $argumented[$memberId] = $this->extracted($memberId);
        Cache::put('members', $argumented);

        $members = $this->get();
        $members[] = $memberId;
        $this->set($members);
        // augment the member with the new member

    }

    public function set(array $members): void
    {
        Cache::put('memberIds', $members);
        Cache::forget('members');
    }

    public function get(): array
    {
        return Cache::get('memberIds', []);
    }

    public function getAugmented(): array
    {
        $members = Cache::get('memberIds', []);

        return Cache::rememberForever('members', function () use ($members) {
            $cfg = config('app.iracing');
            $iracing = new iRacing($cfg['email'], $cfg['password']);
            $rating = [];
            foreach ($members as $accountId) {

                list($member, $license) = $this->extracted($accountId);

                $rating[$accountId] = [
                    'name' => $member->member_info->display_name,
                    'irating' => $license->irating,
                ];
            }
            return $rating;
        });
    }

    function extracted(string $accountId): array
    {
        $member = $this->iracing->member->profile(['cust_id' => $accountId]);

        $license = collect($member->license_history)->first(fn($license) => $license->category === 'sports_car');
        return array($member, $license);
    }
}
