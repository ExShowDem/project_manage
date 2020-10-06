<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\DB;

class SupplyDeliveryOnDemandResource extends BaseResource
{ // The model is SupplyEachRequest
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $exportTypeMap = DB::table('export_types')
            ->select('id', 'name')
            ->get()
            ->keyBy('id')
            ->toArray();

        $inStock = $this->inventory->first() ? $this->inventory->first()->quantity : 0;

        return [
            'id' => $this->id,
            $this->mergeWhen($this->whenLoaded('request'), [
                'request_code' => $this->request->code ?? null,
                'request_name' => $this->request->name ?? null,
                'user_receive_name' => $this->request->userReceive->name ?? null
            ]),
            'offer_name' => $this->offer_name ?? null,
            'offer_code' => $this->offer_code ?? null,
            $this->mergeWhen($this->whenLoaded('item'), [
                'item_name' => $this->item->name ?? null
            ]),
            $this->mergeWhen($this->whenLoaded('supply'), [
                'supply_name' => $this->supply->name ?? null,
                'supply_id' => $this->supply->id ?? null,

                $this->mergeWhen($this->whenLoaded('item'), [
                    'type' => is_null($this->supply) || is_null($this->item) ? '' : $this->getItemSupplyType($this->supply->items->where('pivot.supply_id', $this->supply->id)->where('pivot.item_id', $this->item->id)->first()->pivot->type ?? null),
                ]),
            ]),
            'quantity' => $this->quantity - $this->quantity_actual,
            'date_arrival' => $this->date_arrival,
            'quantity_in_stock' => $inStock,
            'quantity_actual' => $this->quantity_actual,
            'label_status' => $this->quantity == $this->quantity_actual ? 'Xong' : 'Chưa xong',
            'label_status_class' => $this->quantity == $this->quantity_actual ? 'label-success' : 'label-warning',
            'export_type_id' => $this->export_type,
            'export_type_name' => $exportTypeMap[$this->export_type]->name,
        ];
    }
}
