<?php

namespace App\Http\Resources;

use App\Enums\CensorSubContractorType;
use App\Enums\CommonStatus;
use Carbon\Carbon;

class ContractSubResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $paymentOrderModel = resolve('App\Models\PaymentOrder');

        return [
            'id' => $this->id,
            'subcontractor' => $this->subContractor ? : getSubstituteForDeleted(),
            'project' => $this->project ? : getSubstituteForDeleted(),
            'contract_sign_date' => $this->contract_sign_date ? Carbon::parse($this->contract_sign_date)->format(config('api.date_format')) : '',
            'process' => $this->process,
            'contract_form' => $this->contract_form,
            'contract_number' => $this->contract_number,
            'contract_annex_value' => $this->contract_annex_value,
            'contract_annex_value_adjusted' => $paymentOrderModel->calcAnnexValue($this, $paymentOrderModel->getSumSettleByIdContractSubCon($this->id)),
            'contract_value' => $this->contract_value,
            'contract_value_vat' => $this->contract_value_vat,
            'value_custody_warranty' => $this->value_custody_warranty,
            'remain_value' => $this->getRemainValueAttribute(),
            'content' => $this->content,
            'date_warranty' => $this->date_warranty ? Carbon::parse($this->date_warranty)->format(config('api.date_format')) : '',
            'created_by' => $this->createdBy,
            'status_label' => CommonStatus::getDescription($this->status),
            'status' => $this->status,
            'files' => $this->files,
            'construction_items' => $this->construction_items,
        ];
    }
}
