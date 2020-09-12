<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminLoginHistory extends Model
{
    protected $fillable = [
        "admin_id", "login_ip", "country_id", "city", "login_failed_reason", "online_status", "last_activity_at", "sign_in_at", "sign_out_type", "sign_out_at"
    ];

    protected $primaryKey = "admin_login_history_id";

    public $timestamps = false;

    protected $appends = ["id"];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function setLastActivityAtAttribute($value)
    {
        $this->attributes['last_activity_at'] = date("Y-m-d H:i:s", time());
    }

    public static function boot()
    {
        parent::boot();

        // Set field values of created_by and updated_by with current admin id
        static::creating(function ($model) {
            $model->sign_in_at = date("Y-m-d H:i:s", time());
        });
    }
}
