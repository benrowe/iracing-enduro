<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use iRacingPHP\iRacing;

readonly class Members
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

    /**
     * @param string[] $members
     */
    public function setIds(array $members): void
    {
        Cache::put('memberIds', $members);
        Cache::forget('members');
    }

    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return Cache::get('memberIds', []);
    }

    /**
     * @return array{name: string, irating: int}
     */
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


        uasort($avail, static function ($left, $right) {
            return $right['irating'] <=> $left['irating'];
        });

        return $avail;
    }

    public function refresh(): void
    {
        Cache::forget('members');
    }

    /**
     * @return array{name: string, irating: int}
     */
    private function getMemberDetail(string $accountId): array
    {
        $member = $this->iracing->member->profile(['cust_id' => $accountId]);

        $license = collect($member->license_history)
            ->first(static fn ($license) => $license->category === 'sports_car');

        return [
            'name' => $member->member_info->display_name,
            'irating' => $license->irating,
        ];
    }
}
