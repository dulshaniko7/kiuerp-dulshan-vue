<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemAccessIpRestriction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "ip_location", "ip_address", "ip_address_key", "description", "created_by", "updated_by", "deleted_by"
    ];
}
