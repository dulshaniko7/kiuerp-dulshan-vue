<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AdminRole extends Model
{
    use SoftDeletes;

    protected $guard = 'admin';

    protected $fillable = ['role_name', 'description', 'allowed_roles', 'role_status', 'disabled_reason', 'created_by', 'updated_by', 'deleted_by'];

    protected $casts = ["allowed_roles" => "array"];

    protected $appends = ["id", "allowed_roles_data"];

    protected $primaryKey = "admin_role_id";

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function setCreatedByAttribute($value)
    {
        $this->attributes['created_by'] = auth("admin")->user()->admin_id;
    }

    public function setUpdatedByAttribute($value)
    {
        $this->attributes['created_by'] = auth("admin")->user()->admin_id;
    }

    public function getAllowedRolesDataAttribute()
    {
        if($this->allowed_roles)
        {
            return AdminRole::query()->select("admin_role_id AS id", "role_name AS name")->whereIn("admin_role_id", $this->allowed_roles)->get();
        }
        else
        {
            return null;
        }
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, "admin_role_id", "admin_role_id");
    }
}
