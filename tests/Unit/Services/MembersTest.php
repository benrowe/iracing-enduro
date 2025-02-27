<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Members;
use Illuminate\Support\Facades\Cache;
use iRacingPHP\Data\Member;
use iRacingPHP\iRacing;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class MembersTest extends TestCase
{
    public function testCanAddMember(): void
    {
        $iracing = $this
            ->getMockBuilder(iRacing::class)
            ->disableOriginalConstructor()
            ->getMock();
        $iracing->member = Mockery::mock(Member::class, static function (MockInterface $mock): void {
            $mock
                ->shouldReceive('profile')
                ->atLeast()
                ->once()
                ->with(['cust_id' => '12345'])
                ->andReturn((object) [
                    'license_history' => [
                        (object) [
                            'category' => 'sports_car',
                            'irating' => 50,
                        ],
                    ],
                    'member_info' => (object) [
                        'display_name' => 'John Doe',
                    ],
                ]);
        });
        $this->app->instance(iRacing::class, $iracing);
        $members = $this->app->make(Members::class);
        $members->addId('12345');

        $this->assertEquals(['12345' => ['name' => 'John Doe', 'irating' => 50]], $members->getAugmented());
        $this->assertEquals(['12345'], $members->getIds());
    }

    public function testCanNotAddExistingMember(): void
    {
        Cache::put('memberIds', ['12345']);
        $members = $this->app->make(Members::class);
        $members->addId('12345');
        $this->assertSame(['12345'], $members->getIds());
    }

    public function testAugmentedListIsOrderedByRanking(): void
    {
        Cache::put('members', [
            '12345' => ['name' => 'John Doe', 'irating' => 50],
            '12346' => ['name' => 'Harry Hardcore', 'irating' => 6000],
            '23456' => ['name' => 'Jane Doe', 'irating' => 100],

        ]);
        $members = $this->app->make(Members::class);
        $this->assertSame([
            '12346' => ['name' => 'Harry Hardcore', 'irating' => 6000],
            '23456' => ['name' => 'Jane Doe', 'irating' => 100],
            '12345' => ['name' => 'John Doe', 'irating' => 50],
        ], $members->getAugmented());
    }

    public function testMembersRefresh(): void
    {
        Cache::put('members', ['12345' => ['name' => 'John Doe', 'irating' => 50]]);
        $members = $this->app->make(Members::class);
        $members->refresh();
        $this->assertSame([], $members->getAugmented());
    }
}
