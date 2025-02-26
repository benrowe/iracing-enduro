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

    const TEAMS_MEMBERS_STORE = 'web.teams.members.store';
    const TEAMS_ADD = 'web.teams.add';
    const TEAMS_DELETE = 'web.teams.delete';
    const TEAMS_AUTO_ALLOCATE = 'web.teams.auto-allocate';
}
