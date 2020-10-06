<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;
use Carbon\Carbon;

class RequestSupplyResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $suppliesModel = resolve('App\Models\Supplies');

        foreach ($this->supplies as $key => $supply) 
        {
            $this->supplies[$key]->type_id = $this->item ? $this->item->supplies->where('pivot.supply_id', $supply->id)->first()->pivot->type : null;

            $this->supplies[$key]->type = $suppliesModel->getItemSupplyType($this->supplies[$key]->pivot->type_id);

//            $this->supplies[$key]->pivot->date_arrival = isset($supply->pivot->date_arrival) ? Carbon::parse($supply->pivot->date_arrival)->format(config('api.date_format')) : '';
        }

        return [
            'id' => $this->id,
            'receiver_type_id' => $this->receiver_type,
            'receiver_type_name' => $this->receiverType->name ?? null,
            'to_user_id' => $this->to_user,
            'to_user_name' => $this->receiver_name,
            'code' => $this->code,
            'name' => $this->name,
            'supplies' => $this->supplies,
            'files'    => $this->files,
            'comments' => $this->comments,
            'created_date' => $this->created_date,
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project_id' => $this->project_id,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
            'item' => $this->item ? : getSubstituteForDeleted(),
            'content_offer' => $this->content_offer,
            'progress' => $this->progress,
        ];
    }
}
