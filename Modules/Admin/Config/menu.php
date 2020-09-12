<?php
$mainMenu = [];

$mainMenu[] = ["menu_order" => 1, "url" => "/dashboard", "name" => "Dashboard", "slug" => "dashboard", "icon" => "DashboardIcon"];
$mainMenu[] = ["menu_order" => 2, "url" => "", "name" => "Administrators", "slug" => "dashboard", "icon" => "UsersIcon",
                    "submenu" => [
                        ["url" => "/dashboard/admin/create", "name" => "Create Admins", "slug" => "admin-create"],
                        ["url" => "/dashboard/admin", "name" => "List Admins", "slug" => "admin-list"],
                        ["url" => "/dashboard/admin_role/create", "name" => "Create Admin Roles", "slug" => "admin-role-create"],
                        ["url" => "/dashboard/admin_role", "name" => "List Admin Roles", "slug" => "admin-role-list"]
                    ]
                ];

$mainMenu[] = ["menu_order" => 3, "url" => "", "name" => "Team Manger", "slug" => "team", "icon" => "ShieldIcon",
    "submenu" => [
        ["url" => "/dashboard/team/create", "name" => "Create Teams", "slug" => "team-create"],
        ["url" => "/dashboard/team", "name" => "List Teams", "slug" => "team-list"],
        ["url" => "/dashboard/team_player/create", "name" => "Create Team Players", "slug" => "team-player-create"],
        ["url" => "/dashboard/team_player", "name" => "List Team Players", "slug" => "team-player-list"],
        ["url" => "/dashboard/team_player_role/create", "name" => "Create Player Roles", "slug" => "team-player-role-create"],
        ["url" => "/dashboard/team_player_role", "name" => "List Player Roles", "slug" => "team-player-role-list"],
    ]
];

$mainMenu[] = ["menu_order" => 4, "url" => "", "name" => "Tournament Manger", "slug" => "team", "icon" => "LayersIcon",
    "submenu" => [
        ["url" => "/dashboard/tournament/create", "name" => "Create Tournaments", "slug" => "tournament-create"],
        ["url" => "/dashboard/tournament", "name" => "List Tournaments", "slug" => "tournament-list"],
        ["url" => "/dashboard/match/create", "name" => "Create Matches", "slug" => "match-create"],
        ["url" => "/dashboard/match", "name" => "List Matches", "slug" => "match-list"]
    ]
];

return [
    "main_menu" => $mainMenu
];
