<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $guarded = [];

    protected $primaryKey = 'course_id';
}
