<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminPermissionChangeRemark extends Model
{
    protected $fillable = ["remarks", "created_by"];
}
