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

    case TEAMS_MEMBERS_STORE = 'web.teams.members.store';
    case TEAMS_MEMBERS_DELETE = 'web.teams.members.delete';

    case TEAMS_ADD = 'web.teams.add';
    case TEAMS_DELETE = 'web.teams.delete';
    case TEAMS_AUTO_ALLOCATE = 'web.teams.auto-allocate';
}
