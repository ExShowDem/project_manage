<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class DevicesResource extends BaseResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'parent_id'             => $this->parent_id,
            'parent_name'           => $this->parent->name          ?? null,
            'unit_id'               => $this->unit_id,
            'unit_name'             => $this->unit->name            ?? null,
            'code'                  => $this->code,
            'project_id'            => $this->project_id,
            'description'           => $this->description,
            'unit'                  => $this->unit,
            'unit_price'            => $this->unit_price,
            $this->mergeWhen($this->whenLoaded('deviceDetail'), [
                'material_code' => $this->deviceDetail->material_code ?? null,
                'size'          => $this->deviceDetail->size          ?? null,
                'specification' => $this->deviceDetail->specification ?? null,
                'supplier'      => $this->deviceDetail->supplier      ?? null,
                'color'         => $this->deviceDetail->color         ?? null,
                'intensity'     => $this->deviceDetail->intensity     ?? null,
                'density'       => $this->deviceDetail->density       ?? null,
                'standard'      => $this->deviceDetail->standard      ?? null,
                'source'        => $this->deviceDetail->source        ?? null,
            ]),
        ];
    }
}
