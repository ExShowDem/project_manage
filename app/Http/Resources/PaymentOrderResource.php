<?php

namespace App\Http\Resources;

use App\Enums\CensorSubContractorType;
use App\Enums\CommonStatus;
use App\Enums\PaymentOrderStatus;
use App\Enums\PaymentOrderType;
use Carbon\Carbon;

class PaymentOrderResource extends BaseResource
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
            'subcontractor' => $this->subContractor ? : getSubstituteForDeleted(),
            'code' => $this->code,
            'project' => $this->project ? : getSubstituteForDeleted(),
            'contract_sub' => $this->contractSub ? : getSubstituteForDeleted(),
            'created_by' => $this->createdBy,
            'payment_date' => $this->payment_date ? Carbon::parse($this->payment_date)->format(config('api.date_format')) : '',
            'content' => $this->content,
            'type_payment' => $this->type_payment,
            'type_payment_label' => PaymentOrderType::getDescription($this->type_payment),
            'contract_value' => $this->contract_value,
            'settlement_value' => $this->settlement_value,
            'status_label' => PaymentOrderStatus::getDescription($this->status),
            'status' => $this->status,
            'files' => $this->files,
            'total_money_paid' => $this->total_money_paid,
            'contract_annex_value' => $this->annex_value ? : $this->calcAnnexValue($this->contractSub, $this->getSumSettle()),
            'value_custody_warranty' => $this->contractSub ? $this->contractSub->value_custody_warranty : 0,
            'date_warranty' => Carbon::parse($this->contractSub->date_warranty)->format(config('api.date_format')) ?? '',
        ];
    }
}
