<?php

namespace App\Http\Resources;

use App\Http\Resources\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'course'        => $this->course->name,
            'description'   => $this->description, 
            'status'        => $this->status,
            'units'         => UnitResource::collection($this->units)
        ];
    }
}
