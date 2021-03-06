<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPermissionGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_perm_module_id", "group_name", "group_status", "remarks", "created_by", "updated_by", "deleted_by"
    ];

    protected $primaryKey = "admin_perm_group_id";

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
        return $this->group_name;
    }

    public function permissionModule()
    {
        return $this->belongsTo(AdminPermissionModule::class, "admin_perm_module_id", "admin_perm_module_id");
    }
}
