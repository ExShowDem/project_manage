<?php

namespace App\Http\Resources;

class ProjectResource extends BaseResource
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
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'featured_image' => $this->featured_image,
        ];
    }
}
