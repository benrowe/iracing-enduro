<?php

declare(strict_types=1);

namespace Tests\Unit\Renderers;

use App\Renderers\TeamsRenderer;
use App\Services\Members;
use Illuminate\View\View;
use iRacingPHP\Data\Member;
use iRacingPHP\iRacing;
use Mockery;
use Tests\TestCase;

class TeamsRendererTest extends TestCase
{
    public function testCanRenderEmptyTeamsAndMembers(): void
    {
        $teams = $this->app->make(TeamsRenderer::class);

        $view = $teams->render();
        $this->assertInstanceOf(View::class, $view);

        $this->assertStringContainsString('Unallocated Members', $view->toHtml());
    }

    public function testCanRenderMember(): void
    {
        $iracing = $this
            ->getMockBuilder(iRacing::class)
            ->disableOriginalConstructor()
            ->getMock();
        $iracing->member = Mockery::mock(Member::class, static function (Mockery\MockInterface $mock) {
            $mock
                ->shouldReceive('profile')
                ->atLeast()
                ->once()
                ->with(['cust_id' => '1'])
                ->andReturn((object) [
                    'license_history' => [
                        (object) [
                            'category' => 'sports_car',
                            'irating' => 50
                        ]
                    ],
                    'member_info' => (object) [
                        'display_name' => 'John Doe'
                    ]
                ]);
        });

        $this->app->instance(iRacing::class, $iracing);
        $this->app->make(Members::class)->addId('1');
        $teams = $this->app->make(TeamsRenderer::class);

        $view = $teams->render();
        $this->assertInstanceOf(View::class, $view);

        $this->assertStringContainsString('John Doe - 50', $view->toHtml());
    }
}
