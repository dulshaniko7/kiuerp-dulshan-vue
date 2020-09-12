<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermissionSystem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "system_name", "system_slug", "system_status", "remarks", "created_by", "updated_by", "deleted_by"
    ];

    protected $primaryKey = "admin_perm_system_id";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = ["id", "name"];

    protected $with = [];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function getNameAttribute()
    {
        return $this->faculty_name;
    }

    public function permissionModules()
    {
        return $this->hasMany(AdminPermissionModule::class, "admin_perm_system_id", "admin_perm_system_id");
    }

    public function systemPermissions()
    {
        return $this->hasMany(AdminSystemPermission::class, "admin_perm_system_id", "admin_perm_system_id");
    }
}
