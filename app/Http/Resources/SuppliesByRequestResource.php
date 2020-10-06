<?php

namespace App\Http\Resources;

class SuppliesByRequestResource extends BaseResource
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
            'supply_id' => $this->supply_id ?? null,
            'supply_name' => $this->supply->name ?? null,
            'supply_code' => $this->supply->code ?? null,
            'unit' => $this->supply->unit ?? null,
            'quantity' => $this->quantity ?? null,
            'unit_price' => $this->unit_price ?? null,
            'item_name' => $this->item->name ?? null,
        ];
    }
}
