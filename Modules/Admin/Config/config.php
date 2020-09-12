<?php

$game = array();
$game["points"] = array();
$game["points"]["try"] = 5;
$game["points"]["conversion"] = 3;
$game["points"]["bonus"] = 6;
$game["bonus_score_type"] = "try";
$game["bonus_frontier"] = 3;

return [
    'name' => 'Admin',
    'system' => "main", //for permission handling purpose
    'permission_systems' => ["main" => "Main System"],
    "game" => $game
];
