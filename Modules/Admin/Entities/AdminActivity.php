<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    protected $fillable = [
        "admin_login_history_id", "admin_id", "activity", "activity_old_data", "activity_new_data", "activity_model_name", "activity_model", "activity_at"
    ];

    public $timestamps = false;

    protected $casts = [
        'activity_old_data' => 'activity_new_data',
    ];

    public function setActivityAtAttribute($value)
    {
        $this->attributes['activity_at'] = date("Y-m-d H:i:s", time());
    }
}
