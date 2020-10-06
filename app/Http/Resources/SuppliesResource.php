<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class SuppliesResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->input('search_option'))
        {
            $searchOption     = (object) $request->input('search_option');
            $currentProjectId = isset($searchOption->current_project_id) ? (int) $searchOption->current_project_id : false;
        }
        else
        {
            $currentProjectId = false;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'parent_name' => $this->parent->name ?? null,
            'unit_id' => $this->unit_id,
            'unit_name' => $this->unit->name ?? null,
            'category_supplies_id' => $this->category_supplies_id,
            'category_supplies_name' => $this->categorySupplies->name ?? null,
            'code' => $this->code,
            'project_id' => $this->project_id,
            'description' => $this->description,
            'unit' => $this->unit,
            $this->mergeWhen($this->whenLoaded('supplyDetail'), [
                'material_code' => $this->supplyDetail->material_code ?? null,
                'size' => $this->supplyDetail->size ?? null,
                'specification' => $this->supplyDetail->specification ?? null,
                'supplier' => $this->supplyDetail->supplier ?? null,
                'color' => $this->supplyDetail->color ?? null,
                'intensity' => $this->supplyDetail->intensity ?? null,
                'density' => $this->supplyDetail->density ?? null,
                'standard' => $this->supplyDetail->standard ?? null,
                'source' => $this->supplyDetail->source ?? null,
            ]),
            'quantity' => $this->pivot->quantity ?? null,
            'unit_price' => $this->pivot->unit_price ?? null,
            'date_arrival_request' => isset($this->pivot->date_arrival)
                ? Carbon::parse($this->pivot->date_arrival)->format(config('api.date_format'))
                : '',
            'note_request' => $this->pivot->note ?? null,
        ];
    }
}
