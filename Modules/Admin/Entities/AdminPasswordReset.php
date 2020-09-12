<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class AdminPasswordReset extends Model
{
    protected $fillable = ["email", "token"];

    const UPDATED_AT = null;
}
