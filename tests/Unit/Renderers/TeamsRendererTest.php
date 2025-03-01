<?php

declare(strict_types=1);

namespace Tests\Unit\Renderers;

use App\Renderers\TeamsRenderer;
use App\Services\Members;
use App\Services\TeamEntity;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Cache;
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
        $iracing->member = Mockery::mock(Member::class, static function (Mockery\MockInterface $mock): void {
            $mock
                ->shouldReceive('profile')
                ->atLeast()
                ->once()
                ->with(['cust_id' => '1'])
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
        $this->app->make(Members::class)->addId(1);
        $teams = $this->app->make(TeamsRenderer::class);

        $view = $teams->render();
        $this->assertInstanceOf(View::class, $view);

        $this->assertStringContainsString('John Doe - 50', $view->toHtml());
    }

    public function testAllocatedMembersAreNotDisplayedInUnallocated(): void
    {
        Cache::put('memberIds', [1]);
        Cache::put('teams', [new TeamEntity([1])]);
        Cache::put('members', [
            '1' => ['name' => 'Member 1', 'irating' => 12345],
        ]);

        $teams = $this->app->make(TeamsRenderer::class);

        $view = $teams->render();
        $this->assertInstanceOf(View::class, $view);
        // load the html into a dom parser and interrogate it
        $dom = new DOMDocument();
        $dom->loadHTML($view->toHtml(), LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);
        // query the #unallocated-members ul element and get the li element within
        $li = $xpath->query('//ul[@id="unallocated-members"]//li')->item(0);
        $this->assertSame('no unallocated team members', $li->textContent);
    }
}
