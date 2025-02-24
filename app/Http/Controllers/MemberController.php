<?php

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Services\Members;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    #[HttpRoute(RouteNames::MEMBERS_REFRESH)]
    public function refresh(Members $members): RedirectResponse
    {
        $members->refresh();
        return redirect('/');
    }
}
