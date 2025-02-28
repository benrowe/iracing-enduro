<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use App\Services\Members;
use iRacingPHP\Data\Member;
use iRacingPHP\iRacing;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class SettingTest extends TestCase
{
    public function testCanDisplaySettings(): void
    {
        $this
            ->get(route(RouteNames::SETTINGS_INDEX))
            ->assertOk()
            ->assertSee('Existing Members')
            ->assertSee('Add Member');
    }

    public function testExistingMemberIsDisplayed(): void
    {
        // add member
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
        $this->app->make(Members::class)->addId(12345);
        $this
            ->get(route(RouteNames::SETTINGS_INDEX))
            ->assertOk()
            ->assertSee('John Doe - 12345');
    }

    public function testCanAddNewMember(): void
    {
        // add member
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
        $this
            ->post(route(RouteNames::SETTINGS_STORE), ['memberId' => '12345'])
            ->assertRedirect(route(RouteNames::SETTINGS_INDEX));
    }
}
