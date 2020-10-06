<?php

namespace App\Services\Api;

use App\Models\OfferBuy as Model;
use App\Services\BaseService;

class OfferBuyService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getOfferBuys($params)
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
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('ticket_number', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('date_offer', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('date_offer', '<=', carbon_date($searchOption['date_till'])->endOfDay());
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

        return $query->with(['project', 'supplier']);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) {
            return [
                'quantity'     => isset($pivot['quantity'])     ? $pivot['quantity']                  : 0,
                'unit_price'   => isset($pivot['unit_price'])   ? $pivot['unit_price']                : 0,
                'date_arrival' => isset($pivot['date_arrival']) ? carbon_date($pivot['date_arrival']) : null,

            ];
        })->toArray();

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'offer_buy');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'offer_buy');
    }

    public function getNoticeBody($attributes)
    {
        return 'Đề xuất mua vật tư bị đổi: ' . $attributes['name'] . ' [' . $attributes['ticket_number'] . ']';
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
            $query = $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
        }

        if (isset($searchOption['current_project_id'])) 
        {
            $query = $query->where('project_id', '=', $searchOption['current_project_id']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
