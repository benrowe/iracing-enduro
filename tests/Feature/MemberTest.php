<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RouteNames;
use Tests\TestCase;

class MemberTest extends TestCase
{
    public function testCanRefreshMemberList(): void
    {
        $this
            ->get(route(RouteNames::MEMBERS_REFRESH))
            ->assertOk()
            ->assertSee('Unallocated Members');
    }
}
