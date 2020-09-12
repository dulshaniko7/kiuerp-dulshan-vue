<?php

namespace Modules\Slo\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'batch_type' => $this->batch_type,
            'description' => $this->description
        ];
    }
}
