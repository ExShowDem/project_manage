<?php

namespace App\Services\Api;

use App\Enums\CommonStatus;
use App\Models\CensorSubContractor;
use App\Models\Subcontractor;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CensorSubService extends BaseService
{
    public function __construct(CensorSubContractor $censorSub)
    {
        $this->model = $censorSub;
    }

    public function getListCensorSub($params)
    {
        $query = $this->model;

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->with('subcontractors')->whereHas('subcontractors', function ($query) use ($searchOption) {
                return $query->where('name', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['date_browsing_from']) && isset($searchOption['date_browsing_till'])) 
        {
            $query = $query
                ->where('date_browsing', '>=', carbon_date($searchOption['date_browsing_from'])->startOfDay())
                ->where('date_browsing', '<=', carbon_date($searchOption['date_browsing_till'])->endOfDay());
        }

        if (isset($searchOption['date_approve_from']) && isset($searchOption['date_approve_till'])) 
        {
            $query = $query
                ->where('date_approve', '>=', carbon_date($searchOption['date_approve_from'])->startOfDay())
                ->where('date_approve', '<=', carbon_date($searchOption['date_approve_till'])->endOfDay());
        }

        if (isset($searchOption['subcontractorType']))
        {
            $query = $query->where('type', '=', $searchOption['subcontractorType']);
        }

        if (isset($searchOption['status']))
        {
            $query = $query->where('status', '=', $searchOption['status']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        $pivotName = getPivotName($inputs, 'input');

        $inputs['date_browsing'] = isset($inputs['date_browsing']) ? carbon_date($inputs['date_browsing']) : null;
        $inputs['date_approve']  = isset($inputs['date_approve'])  ? carbon_date($inputs['date_approve'])  : null;
        $inputs['status']        = CommonStatus::detectStatusByAction($inputs['action']);

        return [$inputs, $inputs[$pivotName], $pivotName];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'censor_sub');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name'] = $entry->project->name;
        $inputs['subcontractor_record'] = [];

        foreach ($pivotData as $subcontractorId) 
        {
            $subcontractorName = Subcontractor::find($subcontractorId);
            $inputs['subcontractor_record'][$subcontractorId] = $subcontractorName;
        }

        $this->logObjectData($entry, $inputs);
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'censor_sub');
    }

    public function updateSpec(&$entry, &$inputs, &$pivotData)
    {
        $inputs['project_name']       = $entry->project->name;
        $inputs['subcontractor_record'] = [];

        foreach ($pivotData as $subcontractorId) 
        {
            $subcontractorName = Subcontractor::find($subcontractorId);
            $inputs['subcontractor_record'][$subcontractorId] = $subcontractorName;
        }

        $this->logObjectData($entry, $inputs);
    }

    public function getNoticeBody($attributes)
    {
        return 'Danh sách hồ sơ nhà thầu phụ bị đổi: [' . $attributes['subcontractor_name'] . ']';
    }
}
