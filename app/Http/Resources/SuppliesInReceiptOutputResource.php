<?php

namespace App\Http\Resources;

class SuppliesInReceiptOutputResource extends BaseResource
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
            'id' => $this->id ?? null,
            'name' => $this->supply->name ?? null,
            'code' => $this->supply->code ?? null,
            'unit' => $this->supply->unit ?? null,
            'quantity_needed' => $this->quantity ?? null, // SL cần xuất
            'unit_price' => $this->unit_price ?? null,
            'request_supply_id' => $this->supplies_request_id ?? null,
            'request_supply_name' => $this->request->name ?? null,
            'item_id' => $this->item->id ?? null,
            'item_name' => $this->item->name ?? null,
        ];
    }
}
