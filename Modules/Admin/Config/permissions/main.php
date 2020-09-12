<?php

$permissions = [];

$permGroup = [];
$permGroup["name"] = "Administrator Manager";
$permGroup["permissions"][]=["action"=>"dashboard/admin", "label"=>"List Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/create", "label"=>"Create Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/edit", "label"=>"Edit Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/view", "label"=>"View Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/activate", "label"=>"Activate/Deactivate Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/delete", "label"=>"Move To Trash Administrators"];
$permGroup["permissions"][]=["action"=>"dashboard/admin/delete_permanent", "label"=>"Permanently Delete Administrators"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Admin Role Manager";
$permGroup["permissions"][]=["action"=>"dashboard/admin_role", "label"=>"List Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/create", "label"=>"Create Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/edit", "label"=>"Edit Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/view", "label"=>"View Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/activate", "label"=>"Activate/Deactivate Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/delete", "label"=>"Move To Trash Admin Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/admin_role/delete_permanent", "label"=>"Permanently Delete Admin Roles"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Team Manager";
$permGroup["permissions"][]=["action"=>"dashboard/team", "label"=>"List Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/create", "label"=>"Create Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/edit", "label"=>"Edit Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/view", "label"=>"View Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/activate", "label"=>"Activate/Deactivate Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/delete", "label"=>"Move To Trash Teams"];
$permGroup["permissions"][]=["action"=>"dashboard/team/delete_permanent", "label"=>"Permanently Delete Teams"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Team Player Manager";
$permGroup["permissions"][]=["action"=>"dashboard/team_player", "label"=>"List Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/create", "label"=>"Create Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/edit", "label"=>"Edit Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/view", "label"=>"View Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/activate", "label"=>"Activate/Deactivate Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/delete", "label"=>"Move To Trash Team Players"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player/delete_permanent", "label"=>"Permanently Delete Team Players"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Team Player Role Manager";
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role", "label"=>"List Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/create", "label"=>"Create Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/edit", "label"=>"Edit Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/view", "label"=>"View Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/activate", "label"=>"Activate/Deactivate Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/delete", "label"=>"Move To Trash Team Player Roles"];
$permGroup["permissions"][]=["action"=>"dashboard/team_player_role/delete_permanent", "label"=>"Permanently Delete Team Player Roles"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Tournament Manager";
$permGroup["permissions"][]=["action"=>"dashboard/tournament", "label"=>"List Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/create", "label"=>"Create Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/edit", "label"=>"Edit Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/view", "label"=>"View Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/activate", "label"=>"Activate/Deactivate Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/delete", "label"=>"Move To Trash Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/delete_permanent", "label"=>"Permanently Delete Tournaments"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/generate_schedule", "label"=>"Generate Match Schedule"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/match_schedule", "label"=>"View Tournament Match Schedule"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/match_schedule_team", "label"=>"View Team Match Schedule"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/teams", "label"=>"View Tournament Teams List"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/leader-board", "label"=>"View Tournament Leader Board"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/score-board", "label"=>"View Tournament Match Summary"];
$permGroup["permissions"][]=["action"=>"dashboard/tournament/find_winner", "label"=>"Evaluate Tournament Final"];

$permissions[]=$permGroup;

$permGroup = [];
$permGroup["name"] = "Match Manager";
$permGroup["permissions"][]=["action"=>"dashboard/match", "label"=>"List Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/create", "label"=>"Create Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/edit", "label"=>"Edit Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/view", "label"=>"View Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/activate", "label"=>"Activate/Deactivate Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/delete", "label"=>"Move To Trash Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/delete_permanent", "label"=>"Permanently Delete Matches"];
$permGroup["permissions"][]=["action"=>"dashboard/match/score_data", "label"=>"View Match Score"];
$permGroup["permissions"][]=["action"=>"dashboard/match/finish", "label"=>"Evaluate & Finish Match Score Card"];

$permGroup["permissions"][]=["action"=>"dashboard/match_score/create", "label"=>"Add Score To Score Card"];
$permGroup["permissions"][]=["action"=>"dashboard/match_score/edit", "label"=>"Edit Score Record In Score Card"];
$permGroup["permissions"][]=["action"=>"dashboard/match_score/delete", "label"=>"Delete Score Record In Score Card"];

$permissions[]=$permGroup;

return [
    "groups" => $permissions
];

