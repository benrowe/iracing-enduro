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

    public function addId(string $memberId): void
    {
        $augmented = $this->getAugmented();
        $augmented[$memberId] = $this->getMemberDetail($memberId);
        Cache::put('members', $augmented);

        $members = $this->getIds();
        $members[] = $memberId;
        $this->setIds(array_unique($members));
    }

    public function setIds(array $members): void
    {
        Cache::put('memberIds', $members);
        Cache::forget('members');
    }

    public function getIds(): array
    {
        return Cache::get('memberIds', []);
    }

    public function getAugmented(): array
    {
        $members = Cache::get('memberIds', []);

        $avail = Cache::rememberForever('members', function () use ($members) {
            $rating = [];

            foreach ($members as $accountId) {
                $rating[$accountId] = $this->getMemberDetail($accountId);
            }
            return $rating;
        });

        usort($avail, static function ($a, $b) {
            return $b['irating'] <=> $a['irating'];
        });

        return $avail;
    }

    function getMemberDetail(string $accountId): array
    {
        $member = $this->iracing->member->profile(['cust_id' => $accountId]);

        $license = collect($member->license_history)->first(static fn ($license) => $license->category === 'sports_car');
        return [
            'name' => $member->member_info->display_name,
            'irating' => $license->irating,
        ];
    }

    public function refresh(): void
    {
        Cache::forget('members');
    }
}
