<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Renderers\TeamsRenderer;
use App\Services\Members;
use Illuminate\View\View;

readonly class MemberController
{
    public function __construct(private TeamsRenderer $renderer)
    {

    }

    #[HttpRoute(RouteNames::MEMBERS_REFRESH)]
    public function refresh(Members $members): View
    {
        $members->refresh();
        return $this->renderer->render();
    }
}
