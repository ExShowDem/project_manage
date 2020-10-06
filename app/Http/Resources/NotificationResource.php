<?php

namespace App\Http\Resources;

class NotificationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->fromUser->image = !is_null($this->fromUser->image) ? asset('storage/images/avatars/'.$this->fromUser->image) : asset('assets/root/images/avatar1.jpg');
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'from_user' => $this->from_user,
            'to_user' => $this->to_user,
            'content' => $this->content,
            'url' => $this->url,
            'targetable_id' => $this->targetable_id,
            'targetable_type' => $this->targetable_type,
            'is_read' => $this->is_read,
            'targetable' => $this->targetable,
            'fromUser' => $this->fromUser,
        ];
    }
}
