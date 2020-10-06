<?php

namespace App\Services\Api;

use App\Models\RequestSupply as Model;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\CommonStatus;

class RequestService extends BaseService
{
    protected $supplyService;

    public function __construct(Model $model, SupplyService $supplyService)
    {
        $this->model         = $model;
        $this->supplyService = $supplyService;
    }

    public function getListItems($params)
    {
        $query = $this->model;

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['target'])) 
        {
            $query = $query->where('target', $searchOption['target']);
        }

        if (isset($searchOption['project_id'])) 
        {
            $query = $query->where('project_id', $searchOption['project_id']);
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['created_from']) && isset($searchOption['created_till'])) 
        {
            $query = $query
                ->where('created_date', '>=', carbon_date($searchOption['created_from'])->startOfDay())
                ->where('created_date', '<=', carbon_date($searchOption['created_till'])->endOfDay());
        }

        if (isset($searchOption['creator'])) 
        {
            $query = $query->where('created_by', '=', $searchOption['creator']);
        }

        if (isset($searchOption['recipient'])) 
        {
            $query = $query->where('to_user', '=', $searchOption['recipient']);
        }

        if (isset($searchOption['status'])) 
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->with(['project', 'creator']);
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'request_supply');
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'request_supply');
    }

    public function getNoticeBody($attributes)
    {
        return 'Yêu cầu bị đổi: ' . $attributes['name'] . ' [' . $attributes['code'] . ']';
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.show', ['projectId' => $attributes['project_id'], 'target' => $attributes['target'], 'id' => $id]);
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $fields = [
            'status' => true,
            'dates'  => [],
        ];

        $isEdit = isset($inputs['is_edit']) ? $inputs['is_edit'] : false;

        if (!$isEdit && carbon_date($inputs['created_date'])->lessThan(Carbon::now()->startOfDay()))
        {
            throw new \Exception('Ngày tạo không phải trước hôm nay.');
        }

        $inputs = $this->preSmooth($inputs, $pivotName, $fields);

        $pivotData = collect($inputs[$pivotName])->keyBy('id')->map(function ($pivot) use ($inputs, $isEdit) {

            $estimateQuantity  = isset($pivot['estimate_quantity']) ? (float) $pivot['estimate_quantity'] : 0;
            $cumulatedQuantity = isset($pivot['cumulative'])        ? (float) $pivot['cumulative'] : 0;
            $approvedCum       = isset($pivot['approved_cum'])      ? (float) $pivot['approved_cum'] : 0;
            $quantity          = isset($pivot['quantity'])          ? (float) $pivot['quantity'] : 0;
            $inputCumulative   = isset($pivot['input_cumulative'])  ? (float) $pivot['input_cumulative'] : 0;
            $remainder         = isset($pivot['remainder'])         ? (float) $pivot['remainder'] : 0;
            $progress          = isset($inputs['id']) ? $this->model->calcProgress($pivot['id'], $inputs['id'], $estimateQuantity) : 0;

            $asTask = isset($inputs['as_task']) ? $inputs['as_task'] : false;

            if ($asTask || $isEdit)
            {
                if ($quantity + $approvedCum > $estimateQuantity)
                {
                    throw new \Exception("SL yêu cầu + KL lũy kế yêu cầu không phải lớn hơn KL dự toán");
                }
            }
            else
            {
                if ($quantity + $cumulatedQuantity > $estimateQuantity)
                {
                    throw new \Exception("SL yêu cầu + KL lũy kế yêu cầu không phải lớn hơn KL dự toán");
                }
            }

            return [
                'project_id'   => $inputs['project_id'],
                'item_id'      => $inputs['item_id'],
                'quantity'     => $quantity,
                'unit_price'   => isset($pivot['unit_price'])   ? $pivot['unit_price']                : 0,
                'date_arrival' => isset($pivot['date_arrival']) ? ($isEdit ? $pivot['date_arrival'] : carbon_date($pivot['date_arrival'])) : null,
                'note'         => isset($pivot['note'])         ? $pivot['note']                      : '',
                'cumulative'   => $cumulatedQuantity,//$inputCumulative + $quantity,
                'estimate_quantity' => $estimateQuantity,
                'approved_cum'      => $approvedCum,
                'input_cumulative'  => $inputCumulative,
                'remainder'         => $remainder,
                'progress'          => $progress,

            ];
        })->toArray();

        $progressSum = collect($pivotData)->sum('progress')/count($pivotData);
        $inputs['progress'] = $progressSum;

        list($inputs, $pivotData) = $this->postSmooth($inputs, $pivotData);

        return [$inputs, $pivotData, $pivotName];
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
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['current_project_id'])) 
        {
            $query = $query->where('supplies_requests.project_id', '=', $searchOption['current_project_id']);
        }

        if (isset($searchOption['exclude_done'])) 
        {
            if (isset($searchOption['for_output'])) 
            {
                $sub = collect( 
                    DB::select( 
                        DB::raw('SELECT supplies_request_id FROM supply_each_request WHERE quantity_actual < quantity') 
                    ) 
                )
                    ->pluck('supplies_request_id')
                    ->toArray();

                $query = $query->whereIn('supplies_requests.id', $sub);
            }

            if (isset($searchOption['for_receipt_input'])) 
            {
                $sub = collect( 
                    DB::select( 
                        DB::raw('SELECT request_id FROM receipt_inputs JOIN receipt_input_supplies ON receipt_input_supplies.input_id=receipt_inputs.id WHERE original_quantity = quantity + cumulative') 
                    ) 
                )
                    ->pluck('request_id')
                    ->toArray();

                $query = $query->whereNotIn('supplies_requests.id', $sub);
            }

            if (isset($searchOption['for_invoice'])) 
            {
                $sub = collect( 
                    DB::select( 
                        DB::raw('SELECT request_id FROM invoices JOIN invoice_supplies ON invoice_supplies.invoice_id=invoices.id WHERE existing_quantity = quantity + cumulative') 
                    ) 
                )
                    ->pluck('request_id')
                    ->toArray();

                $query = $query->whereNotIn('supplies_requests.id', $sub);
            }
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
