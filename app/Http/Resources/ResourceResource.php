<?php

namespace App\Http\Resources;

class ResourceResource extends BaseResource
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
            'name' => $this->name,
            'resource_type_id' => $this->resource_type_id,
            'resource_type_name' => $this->resourceType->name ?? null,
            'unit_id' => $this->unit_id,
            'unit_name' => $this->unit->name ?? null,
            'unit_price' => $this->unit_price,
        ];
    }
}
