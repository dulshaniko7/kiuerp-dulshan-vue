<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes;

    //protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'lecturer_id', 'name', 'email', 'password', 'admin_role_id', 'status', 'default_admin', 'created_by', 'updated_by', 'deleted_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["id"];

    //protected $appends = ["id", "admin_image_url", "admin_role"];

    protected $primaryKey = "admin_id";

    private $admin_image_dir = "images/admin_images/";

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function adminRole()
    {
        return $this->belongsTo(AdminRole::class, "admin_role_id", "admin_role_id");
    }

    /*public function getAdminImageUrlAttribute()
    {
        if($this->image != "")
        {
            return BaseModel::getFileUrl($this->image, $this->admin_image_dir);
        }
        else
        {
            return "";
        }
    }*/

    /*public static function boot()
    {
        parent::boot();

        // Set field values of created_by and updated_by with current admin id
        static::creating(function ($model) {
            $model->created_by = auth("admin")->user()->admin_id;
            $model->updated_by = auth("admin")->user()->admin_id;
        });

        // Update field updated_by with current admin id
        static::saving(function ($model) {
            $model->updated_by = auth("admin")->user()->admin_id;
        });

        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->deleted_by = auth("admin")->user()->admin_id;
            }
        });
    }*/
}
