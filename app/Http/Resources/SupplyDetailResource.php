<?php

namespace App\Http\Resources;

class SupplyDetailResource extends BaseResource
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
            'supplies_id' => $this->supplies_id,
            'material_code' => $this->material_code,
            'size' => $this->size,
            'specification' => $this->specification,
            'supplier' => $this->supplier,
            'color' => $this->color,
            'intensity' => $this->intensity,
            'density' => $this->density,
            'standard' => $this->standard,
            'source' => $this->source,
        ];
    }
}
