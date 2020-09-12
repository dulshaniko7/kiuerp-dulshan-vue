<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    protected $fillable = [
        "admin_id", "admin_perm_system_id", "system_perm_id", "admin_perm_change_remark_id", "inv_rev_status", "valid_from", "valid_till"
    ];

    public $timestamps = false;
}
