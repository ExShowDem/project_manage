<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

class ItemResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $typesMap = DB::table('item_supplier_types')
            ->select('id', 'name')
            ->get()
            ->keyBy('id')
            ->toArray();

        foreach ($this->supplies as $key => $supply) 
        {
            if ($supply->pivot->type)
            {
                $this->supplies[$key]->pivot->type_text = $typesMap[$supply->pivot->type]->name;
            }
            else
            {
                $this->supplies[$key]->pivot->type_text = '';
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'created_by' => $this->user ? : getSubstituteForDeleted(),
            'project' => $this->project ? new ProjectResource($this->whenLoaded('project')) : getSubstituteForDeleted(),
            'supplies'  => $this->supplies,
            'files'    => $this->files,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->format('d/m/Y') : null,
            'can_see_price' => auth()->user()->can('items.see_price') ?? false,
            'status' => $this->status->value,
            'status_label' => $this->status->description,
            'status_label_class' => CommonStatus::getLabelClass($this->status->value),
            'progress' => $this->progress,
        ];
    }
}
