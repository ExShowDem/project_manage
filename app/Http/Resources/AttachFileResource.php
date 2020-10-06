<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'link' => $this->link,
            'real_name' => $this->real_name,
            'extension' => $this->extension,
            'fileable_type' => $this->fileable_type,
            'fileable_id' => $this->fileable_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'user_created' => $this->userCreated,
            'user_created_name' => $this->userCreatedName,
            'deleted_at' => $this->deleted_at,
            'user_deleted' => $this->userDeleted,
            'user_deleted_name' => $this->userDeletedName,
        ];
    }
}
