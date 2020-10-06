<?php

namespace App\Http\Resources;

class SubContractorResource extends BaseResource
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
            'type' => $this->type,
            'code' => $this->code,
            'tax_code' => $this->tax_code,
            'representative' => $this->representative,
            'bank' => $this->bank,
            'account_name' => $this->account_name,
            'account_owner' => $this->account_owner,
        ];
    }
}
