<?php

namespace App\Http\Resources;

use App\Http\Resources\ModuleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'description'   => $this->description,
            'category'      => $this->category->name,
            'instructor'    => $this->user->name,
            'status'        => $this->status,
            'modules'       => ModuleResource::collection($this->modules)
        ];
    }
}
