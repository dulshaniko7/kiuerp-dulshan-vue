<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemAccessAdminIpRestriction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_id", "ip_location", "ip_address", "ip_address_key", "remarks", "created_by", "updated_by", "deleted_by"
    ];
}
