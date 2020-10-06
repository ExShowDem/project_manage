<?php

namespace App\Services\Api;

use App\Enums\PaymentOrderStatus;
use App\Models\ContractSubContractor;
use App\Models\PaymentOrder as Model;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentOrderService extends BaseService
{
    protected $contractSubContractor;

    public function __construct(Model $model, ContractSubContractor $contractSubContractor)
    {
        $this->model                 = $model;
        $this->contractSubContractor = $contractSubContractor;
    }

    public function getListPaymentOrder($params)
    {
        $query = $this->model;

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->with('subContractor')->whereHas('subContractor', function ($query) use ($searchOption) {
                return $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['contract'])) 
        {
            $query = $query->where('contract_subcontractor_id', '=', $searchOption['contract']);
        }

        if (isset($searchOption['creator'])) 
        {
            $query = $query->where('created_by', '=', $searchOption['creator']);
        }

        if (isset($searchOption['paymentType']))
        {
            $query = $query->where('type_payment', '=', $searchOption['paymentType']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $this->checkSettlementValueBeforeStore($inputs);

        $selected = Arr::only($inputs, [
            'contract_subcontractor_id',
            'code',
            'subcontractor_id',
            'project_id',
            'payment_date',
            'content',
            'type_payment',
            'contract_value',
            'settlement_value',
            'annex_value',
        ]);

        $selected['payment_date'] = isset($selected['payment_date']) ? carbon_date($selected['payment_date']) : null;
        $selected['status']       = (isset($inputs['action']) && $inputs['action'] === 'complete') ? PaymentOrderStatus::PAID : PaymentOrderStatus::NOT_APPROVED;

        if (isset($inputs['created_by']) && !is_array($inputs['created_by']))
        {
            $selected['created_by'] = $inputs['created_by'];
        }

        if (isset($inputs['forward_data']))
        {
            $selected['forward_data'] = $inputs['forward_data'];
        }

        if (strtotime(Carbon::now()) > strtotime(Carbon::createFromFormat('d/m/Y', $inputs['date_warranty'])->format('d-m-Y')) && $inputs['settlement_value'] > $inputs['contract_annex_value'])
        {
            throw new \Exception('Giá trị lớn hơn giá trị còn lại');
        }

        if (strtotime(Carbon::now()) < strtotime(Carbon::createFromFormat('d/m/Y', $inputs['date_warranty'])->format('d-m-Y')) && (float) $inputs['settlement_value'] > $inputs['contract_annex_value'] - $inputs['value_custody_warranty'])
        {
            throw new \Exception('Giá trị lớn hơn giá trị còn lại - giá trị tạm giữ bảo hành');
        }

        return [$selected, [], getPivotName($inputs, 'input')];
    }

    /**
     * @param $input
     * @return bool|int
     */
    private function checkSettlementValueBeforeStore($input)
    {
        $contractSub = $this->contractSubContractor->find($input['contract_subcontractor_id']);

        // chưa hết thời gian bảo hành
        if ($contractSub->date_warranty->gt(now())) 
        {
            if ($input['settlement_value'] > ($contractSub->remain_value - $contractSub->value_custody_warranty)) 
            {
                //throw new \Exception('Đã nhận đủ tiền cho hợp đồng này. Còn tiền bảo hành chỉ được nhận khi hết thời gian bảo hành'); // case 3
            }
        } 
        else 
        {
            if ($input['settlement_value'] > $contractSub->remain_value) 
            {
                throw new \Exception('Đã thanh toán đủ tiền, không được phép tạo mới đề nghị thanh toán'); // case 4
            }
        }
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'payment_order');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name']                 = $entry->project->name;
        $inputs['subcontractor_name']           = $entry->subContractor->name;
        $inputs['contract_sub_contract_number'] = $entry->subContractor->name;
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'payment_order');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name']                 = $entry->project->name;
        $inputs['subcontractor_name']           = $entry->subContractor->name;
        $inputs['contract_sub_contract_number'] = $entry->subContractor->name;
        $inputs['created_by'] = auth()->id();
    }

    public function getNoticeBody($attributes)
    {
        return 'Danh sách đề nghị thanh toán nhà thầu phụ bị đổi: [' . $attributes['subcontractor_name'] . ']';
    }
}
