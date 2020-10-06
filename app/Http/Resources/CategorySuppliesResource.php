<?php

namespace App\Http\Resources;

class CategorySuppliesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent_name' => $this->parent->name ?? null,
            'name' => $this->name,
            'code' => $this->code,
            'project_id' => $this->project_id,
            'project_name' => $this->project->name ?? null,
        ];
    }
}
