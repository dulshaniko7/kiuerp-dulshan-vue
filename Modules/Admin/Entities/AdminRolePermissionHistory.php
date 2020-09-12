<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermissionHistory extends Model
{
    protected $fillable = ["admin_role_id", "admin_perm_system_id", "remarks", "invoked_permissions", "revoked_permissions", "updated_by"];

    const UPDATED_AT = null;

    protected $casts = [
        'invoked_permissions' => 'revoked_permissions',
    ];
}
