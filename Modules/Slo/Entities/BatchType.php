<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchType extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $primaryKey = "id";

    public function batches()
    {
        return $this->hasMany(Batch::class, 'batch_id', 'batch_id');
    }

}
