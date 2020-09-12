<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminSystemPermission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_perm_group_id", "permission_title", "permission_action", "permission_key", "permission_status", "disabled_reason", "created_by", "updated_by", "deleted_by"
    ];

    protected $primaryKey = "system_perm_id";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = ["id", "name"];

    /*
     * Setting up this field as an empty array, otherwise it will retrieve ORM relations every time
     */
    protected $with = [];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function getNameAttribute()
    {
        return $this->permission_title;
    }

    public function permissionGroup()
    {
        return $this->belongsTo(AdminPermissionGroup::class, "admin_perm_group_id", "admin_perm_group_id");
    }
}
