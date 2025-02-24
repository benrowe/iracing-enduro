<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Attributes\HttpRoute;
use App\Enums\RouteNames;
use App\Services\Members;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    #[HttpRoute(RouteNames::SETTINGS_INDEX)]
    public function index(Members $members): View
    {
        return view('settings', [
            'members' => $members->getAugmented(),
        ]);
    }

    #[HttpRoute(RouteNames::SETTINGS_STORE)]
    public function store(Request $request, Members $members): RedirectResponse
    {
        $members->addId($request->string('memberId')->toString());
        return redirect('/settings');
    }
}
