<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'content'       => $this->content, 
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('F d, Y'),
            'last_updated'  => $this->updated_at->format('F d, Y')
        ];
    }
}
