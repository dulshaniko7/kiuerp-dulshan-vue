<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class IpRestrictionRevokedAdmin extends Model
{
    protected $fillable = ["admin_id", "remarks", "created_by"];

    const UPDATED_AT = null;
}
