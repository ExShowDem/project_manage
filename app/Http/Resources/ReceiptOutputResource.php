<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ReceiptOutputResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $itemName = Item::find($this->requestSupply->item_id)->name;

        foreach ($this->supplies as $key => $supply)
        {
            $this->supplies[$key]->pivot->item_name = $itemName;
        }

        return [
            'id' => $this->id,
            'output_id' => $this->output_id,
            'receiver_type_id' => $this->receiver_type,
            'receiver_type_name' => $this->receiverType->name ?? null,
            'receiver_id' => $this->input_id,
            'receiver_name' => $this->receiver_name,
            'code' => $this->code,
            'date_output' => $this->date_output,
            'reason' => $this->reason,
            'supplies' => $this->supplies,
            'files' => AttachFileResource::collection($this->whenLoaded('files')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'request_supply' => new RequestSupplyResource($this->requestSupply),
            'offer_buy' => $this->offerBuy,
            'supplier' => $this->supplier ? : getSubstituteForDeleted(),
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'project_id' => $this->project_id,
            'creator' => $this->creator ? : getSubstituteForDeleted(),
        ];
    }
}
