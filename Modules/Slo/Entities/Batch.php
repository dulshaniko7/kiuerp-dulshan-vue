<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\Entities\Course;

class Batch extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $primaryKey = "batch_id";

    public function course()
    {
        return $this->belongsTo(Course::class, "course_id", 'course_id');
    }

    public function batchType()
    {
        return $this->belongsTo(BatchType::class, 'id', 'id');
    }

    public static function boot()
    {
        parent::boot();
    }

    public function generateBatchCode()
    {
        $batch_code = Batch::all()->max('batch_code');

        if ($batch_code != null) {
            $batch_code = intval($batch_code);

            $batch_code++;

            if ($batch_code < 10) {
                $batch_code = '00' . $batch_code;
            }
            if (($batch_code > 10) && ($batch_code < 100)) {
                $batch_code = '0' . $batch_code;
            }





        } else {
            $batch_code = "001";
        }

        return $batch_code;
    }
}
