<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;
use App\Enums\TaskStatus;
use Carbon\Carbon;

class ProcessLogDetailResource extends BaseResource
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
            'process_user_id' => $this->process_user_id,
            'data_object' => $this->data_object,
            'creator' => $this->processUser,
        ];
    }
}
