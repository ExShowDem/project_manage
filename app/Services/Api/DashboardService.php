<?php

namespace App\Services\Api;


use App\Models\ProcessLog;
use App\Services\BaseService;
use App\Models\Task;
use App\Models\RequestSupply;
use App\Models\DevicePurchase;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\RoleTree;

class DashboardService extends BaseService
{
    public function __construct(Task $task, RequestSupply $request_supplies, Invoice $invoice, DevicePurchase $device_purchase)
    {
        $this->modeltask = $task;
        $this->modelrequestsupplies = $request_supplies;
        $this->modelinvoice = $invoice;
        $this->modeldevicepurchase = $device_purchase;
    }

    public function getCountDashboard($params)
    {
        // query task
        $query_task = $this->modeltask;

        if (isset($params['search_option']['date_from'])) {
            $query_task = $query_task->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

        }
        if (isset($params['search_option']['date_to'])) {
            $query_task = $query_task->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
        }
        if (isset($params['search_option']['users'])) {
            $query_task = $query_task->where('to_user', $params['search_option']['users']);
        }

        $query_task = $query_task->where('project_id', $params['project_id']);

        $query_task->orderBy('id', 'desc');
        $result['total_task'] = $query_task->count();
        $result['needhandle_task'] = $query_task->where('status', 1)->count();
        $result['processed_task'] = $result['total_task']-$result['needhandle_task'];
        //dd($result['total_task']);
        
        //query category supplies

        $query_requestsupplies = $this->modelrequestsupplies;

        if (isset($params['search_option']['date_from'])) {
            $query_requestsupplies = $query_requestsupplies->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

        }
        if (isset($params['search_option']['date_to'])) {
            $query_requestsupplies = $query_requestsupplies->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
        }

        if (isset($params['search_option']['users'])) {
            $query_requestsupplies = $query_requestsupplies->where('to_user', $params['search_option']['users']);
        }

        $query_requestsupplies = $query_requestsupplies->where('project_id', $params['project_id']);

        $query_requestsupplies->orderBy('id', 'desc');
        $result['total_requestsupplies'] = $query_requestsupplies->count();
        $result['needhandle_requestsupplies'] = $query_requestsupplies->where('status', 1)->count();
        $result['processed_requestsupplies'] = $result['total_requestsupplies']-$result['needhandle_requestsupplies'];


        // query invoice
        $query_invoice = $this->modelinvoice;

        if (isset($params['search_option']['date_from'])) {
            $query_invoice = $query_invoice->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

        }
        if (isset($params['search_option']['date_to'])) {
            $query_invoice = $query_invoice->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
        }

        $query_invoice = $query_invoice->where('project_id', $params['project_id']);

        $query_invoice->orderBy('id', 'desc');
        $result['total_invoice'] = $query_invoice->count();
        $result['needhandle_invoice'] = $query_invoice->where('status', 1)->count();
        $result['processed_invoice'] = $result['total_invoice']-$result['needhandle_invoice'];


        // query device purchase
        $query_devicepurchase = $this->modeldevicepurchase;

        if (isset($params['search_option']['date_from'])) {
            $query_devicepurchase = $query_devicepurchase->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

        }
        if (isset($params['search_option']['date_to'])) {
            $query_devicepurchase = $query_devicepurchase->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
        }

        $query_devicepurchase = $query_devicepurchase->where('project_id', $params['project_id']);

        $query_devicepurchase->orderBy('id', 'desc');
        $result['total_devicepurchase'] = $query_devicepurchase->count();
        $result['needhandle_devicepurchase'] = $query_devicepurchase->where('status', 1)->count();
        $result['processed_devicepurchase'] = $result['total_devicepurchase']-$result['needhandle_devicepurchase'];
        
        
        
        return $result;
    }

}