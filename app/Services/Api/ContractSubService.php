<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Models\ContractSubContractor as Model;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class ContractSubService extends BaseService
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getListContractSub($params)
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

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        if (isset($searchOption['project']))
        {
            $query = $query->where('project_id', '=', $searchOption['project']);
        }

        if (isset($searchOption['subcontractor']))
        {
            $query = $query->with('subContractor')->whereHas('subContractor', function ($query) use ($searchOption) {
                return $query->where('id', '=', $searchOption['subcontractor']);
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $selected = Arr::only($inputs, [
            'subcontractor_id',
            'project_id',
            'contract_sign_date',
            'process',
            'contract_form',
            'contract_number',
            'contract_annex_value',
            'contract_value',
            'contract_value_vat',
            'value_custody_warranty',
            'content',
            'date_warranty',
            'construction_items'
        ]);

        $selected['contract_value']     = isset($selected['contract_value'])     ? $selected['contract_value']                  : null;
        $selected['contract_value_vat'] = isset($selected['contract_value_vat']) ? $selected['contract_value_vat']              : null;
        $selected['contract_sign_date'] = isset($selected['contract_sign_date']) ? carbon_date($selected['contract_sign_date']) : null;
        $selected['date_warranty']      = isset($selected['date_warranty'])      ? carbon_date($selected['date_warranty'])      : null;
        $selected['status']             = CommonStatus::detectStatusByAction($inputs['action']);

        if (isset($inputs['created_by']) && !is_array($inputs['created_by']))
        {
            $selected['created_by'] = $inputs['created_by'];
        }

        return [$selected, [], getPivotName($inputs, 'input')];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'contract_sub');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name']       = $entry->project->name;
        $inputs['subcontractor_name'] = $entry->subContractor->name;

        $this->logObjectData($entry, $inputs);
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'contract_sub');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name']       = $entry->project ? $entry->project->name : 'N/A';
        $inputs['subcontractor_name'] = $entry->subContractor ? $entry->subContractor->name : 'N/A';

        $this->logObjectData($entry, $inputs);
    }

    public function getNoticeBody($attributes)
    {
        return 'Danh sách hợp đồng nhà thầu phụ bị đổi: [' . $attributes['subcontractor_name'] . ']';
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

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
