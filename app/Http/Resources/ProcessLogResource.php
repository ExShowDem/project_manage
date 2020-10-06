<?php

namespace App\Http\Resources;

use App\Enums\CommonStatus;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use App\Models\User;

class ProcessLogResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'process_user_id' => $this->process_user_id,
            'process_user_name' => $this->processUser->name ?? null,
            'process_name' => $this->name,
            'process_status' => CommonStatus::getDescription($this->status),
            'process_status_code' => $this->status,
            'process_status_class' => CommonStatus::getLabelClass($this->status),
            'date' => Carbon::parse($this->created_at)->toDateString(),
            'time' => Carbon::parse($this->created_at)->toTimeString(),
            'data_object' => $this->data_object,
        ];

        if ($this->status === CommonStatus::FORWARDED)
        {
            $result['to'] = User::find($this->data_object['forward_data']['to'])->toArray();
            $result['cc'] = User::find($this->data_object['forward_data']['cc'])->toArray();

            if (empty($result['cc']))
            {
                unset($result['cc']);
            }
        }

        return $result;
    }
}
