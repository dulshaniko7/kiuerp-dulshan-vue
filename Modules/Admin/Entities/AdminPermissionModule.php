<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermissionModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_perm_system_id", "module_name", "module_status", "remarks", "created_by", "updated_by", "deleted_by"
    ];

    protected $primaryKey = "admin_perm_module_id";

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
        return $this->module_name;
    }

    public function permissionSystem()
    {
        return $this->belongsTo(AdminPermissionSystem::class, "admin_perm_system_id", "admin_perm_system_id");
    }
}
