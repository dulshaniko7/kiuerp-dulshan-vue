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

return [
    "groups" => $permissions
];

