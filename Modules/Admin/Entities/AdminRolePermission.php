<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model
{
    protected $fillable = [
        "admin_role_id", "admin_perm_system_id", "system_perm_id", "created_by"
    ];

    const UPDATED_AT = null;
}
