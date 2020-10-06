<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Models\Invoice as Model;
use App\Services\BaseService;

class InvoiceService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getInvoices($params)
    {
        $query = $this->model;

        if (isset($params['project_id']) && $params['project_id']) 
        {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->where('contract_number', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('contract_date', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('contract_date', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($searchOption['supplier'])) 
        {
            $query = $query->where('supplier_id', '=', $searchOption['supplier']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->with(['project', 'supplier', 'request']);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) use ($inputs) {

            $quantity         = $pivot['quantity']   ?? 0;
            $accumulate       = $pivot['accumulate'] ?? 0;
            $existingQuantity = $pivot['existing_quantity'] ?? 0;

            if ($accumulate + $quantity > $existingQuantity)
            {
                throw new \Exception('Số lượng + SL lũy kế hóa đơn không phải lớn hơn SL hóa đơn chứng Từ');
            }

            if (isset($inputs['id']))
            {
                $prevQuantity = $pivot['prev_quantity'] ?? 0;
                $cumulative   = $accumulate;// + ($quantity - $prevQuantity);
            }
            else
            {
                $cumulative = $accumulate;// + $quantity;
            }

            return [
                'quantity'   => $quantity,
                'unit_price' => $pivot['unit_price'] ?? 0,
                'discount'   => $pivot['discount']   ?? 0,
                'other_cost' => $pivot['other_cost'] ?? 0,
                'tax'        => $pivot['tax']        ?? 0,
                'cumulative' => $cumulative,
                'existing_quantity' => $existingQuantity,
            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'invoice');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'invoice');
    }

    public function getNoticeBody($attributes)
    {
        return 'Hoá đơn mua vật tư bị đổi: ' . ' [' . $attributes['contract_number'] . ']';
    }

    public function updateStatus($id, $status)
    {
        return parent::update($id, ['status' => $status]);
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->where('contract_number', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['for_receipt_input'])) 
        {
            $query = $query->where('status', CommonStatus::APPROVED);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
