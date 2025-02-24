<?php

declare(strict_types=1);

namespace App\Enums;

enum RouteNames: string
{
    // authenticated
    case DASHBOARD = 'web.home';

    case MEMBERS_REFRESH = 'web.members.refresh';

    case SETTINGS_INDEX = 'web.settings.index';
    case SETTINGS_STORE = 'web.settings.store';
}
