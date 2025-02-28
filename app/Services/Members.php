<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use iRacingPHP\iRacing;

class Members
{
    public function __construct(readonly private iRacing $iracing)
    {
    }

    public function addId(int $memberId): void
    {
        $members = $this->getIds();

        if (in_array($memberId, $members, true)) {
            return;
        }

        $augmented = $this->getAugmented();
        $augmented[$memberId] = $this->getMemberDetail($memberId);
        Cache::put('members', $augmented);


        $members[] = $memberId;
        $this->setIds(array_unique($members));
    }

    /**
     * @param int[] $members
     */
    public function setIds(array $members): void
    {
        Cache::put('memberIds', array_map('intval', $members));
        Cache::forget('members');
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return array_map('intval', Cache::get('memberIds', []));
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
    private function getMemberDetail(int $accountId): array
    {
        /**
         * @var object{
         *     license_history: object{category: string, irating: int}[],
         *     member_info: object{display_name: string}
         * } $member
         */
        $member = $this->iracing->member->profile(['cust_id' => $accountId]);

        $license = collect($member->license_history)
            ->first(static fn (object $license) => $license->category === 'sports_car');

        return [
            'name' => $member->member_info->display_name,
            'irating' => $license->irating,
        ];
    }
}
