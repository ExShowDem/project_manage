<?php

namespace App\Http\Resources;

class SupplierResource extends BaseResource
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
            'name' => $this->name,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'type' => $this->type,
            'tax_code' => $this->tax_code,
            'created_at' => $this->created_at,
            'branch' => $this->branch,
        ];
    }
}
