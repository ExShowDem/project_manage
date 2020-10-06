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

class ReportService extends BaseService
{
    public function __construct(Task $task, RequestSupply $request_supplies, Invoice $invoice, DevicePurchase $device_purchase)
    {
        $this->modeltask = $task;
        $this->modelrequestsupplies = $request_supplies;
        $this->modelinvoice = $invoice;
        $this->modeldevicepurchase = $device_purchase;
    }

    public function getCountReport($params)
    {
        //dd( $params);
        if(isset($params['project_id']) && $params['project_id'] != 0) {
            // query task
            $query_task = DB::table('tasks')->whereNull('deleted_at');

            if (isset($params['search_option']['date_from'])) {
                $query_task = $query_task->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

            }
            if (isset($params['search_option']['date_to'])) {
                $query_task = $query_task->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
            }

            if (isset($params['search_option']['roles'])) {

                $getArrayUser = DB::table('model_has_roles')
                    ->select('model_id')->where('role_id',$params['search_option']['roles'])
                    ->groupBy('model_id')
                    ->get()->toArray();
                $arrayIdUser = array();
                foreach($getArrayUser as $val){
                    $arrayIdUser[] = $val->model_id;
                }
                $query_task = $query_task->whereIn('to_user', $arrayIdUser);
            }

            if (isset($params['search_option']['users'])) {
                $query_task = $query_task->where('to_user', $params['search_option']['users']);
            }

            $query_task = $query_task->where('project_id', $params['project_id']);


            $result['total_task'] = $query_task->count();
            $result['needhandle_task'] = 0;
            $result['processed_task'] = 0;
            $result['returned_task'] = 0;
            $statusAllTask = $query_task->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->get()->toArray();

            foreach($statusAllTask as $valueStatusTask){
                if($valueStatusTask->status == 1) $result['needhandle_task'] = $valueStatusTask->total;
                if($valueStatusTask->status == 3) $result['processed_task'] = $valueStatusTask->total;
                /*if($valueStatusTask['status'] == 3) $result['returned_task'] = $valueStatusTask['total'];*/
            }





            //query category supplies

            $query_requestsupplies = DB::table('supplies_requests')->whereNull('deleted_at');

            if (isset($params['search_option']['date_from'])) {
                $query_requestsupplies = $query_requestsupplies->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

            }
            if (isset($params['search_option']['date_to'])) {
                $query_requestsupplies = $query_requestsupplies->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
            }

            if (isset($params['search_option']['roles'])) {

                $getArrayUser = DB::table('model_has_roles')
                    ->select('model_id')->where('role_id',$params['search_option']['roles'])
                    ->groupBy('model_id')
                    ->get()->toArray();
                $arrayIdUser = array();
                foreach($getArrayUser as $val){
                    $arrayIdUser[] = $val->model_id;
                }
                $query_requestsupplies = $query_requestsupplies->whereIn('created_by', $arrayIdUser);
            }

            if (isset($params['search_option']['users'])) {
                $query_requestsupplies = $query_requestsupplies->where('created_by', $params['search_option']['users']);
            }

            $query_requestsupplies = $query_requestsupplies->where('project_id', $params['project_id']);


            $result['total_requestsupplies'] = $query_requestsupplies->count();
            $result['needhandle_requestsupplies'] = 0;
            $result['processed_requestsupplies'] = 0;
            $result['returned_requestsupplies'] = 0;
            $statusAllRequestsupplies = $query_requestsupplies->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->get()->toArray();

            foreach($statusAllRequestsupplies as $valueStatusRequestsupplies){
                if($valueStatusRequestsupplies->status == 2) $result['needhandle_requestsupplies'] = $valueStatusRequestsupplies->total;
                if($valueStatusRequestsupplies->status == 3) $result['processed_requestsupplies'] = $valueStatusRequestsupplies->total;
                if($valueStatusRequestsupplies->status == 4) $result['returned_requestsupplies'] = $valueStatusRequestsupplies->total;
            }


            // query invoice
            $query_invoice = DB::table('invoices')->whereNull('deleted_at');

            if (isset($params['search_option']['date_from'])) {
                $query_invoice = $query_invoice->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

            }
            if (isset($params['search_option']['date_to'])) {
                $query_invoice = $query_invoice->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
            }

            if (isset($params['search_option']['roles'])) {

                $getArrayUser = DB::table('model_has_roles')
                    ->select('model_id')->where('role_id',$params['search_option']['roles'])
                    ->groupBy('model_id')
                    ->get()->toArray();
                $arrayIdUser = array();
                foreach($getArrayUser as $val){
                    $arrayIdUser[] = $val->model_id;
                }
                $query_invoice = $query_invoice->whereIn('created_by', $arrayIdUser);
            }

            if (isset($params['search_option']['users'])) {
                $query_invoice = $query_invoice->where('created_by', $params['search_option']['users']);
            }

            $query_invoice = $query_invoice->where('project_id', $params['project_id']);

            $result['total_invoice'] = $query_invoice->count();
            $result['needhandle_invoice'] = 0;
            $result['processed_invoice'] = 0;
            $result['returned_invoice'] = 0;
            $statusAllInvoice = $query_invoice->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->get()->toArray();

            foreach($statusAllInvoice as $valueStatusInvoice){
                if($valueStatusInvoice->status == 2) $result['needhandle_invoice'] = $valueStatusInvoice->total;
                if($valueStatusInvoice->status == 3) $result['processed_invoice'] = $valueStatusInvoice->total;
                if($valueStatusInvoice->status == 4) $result['returned_invoice'] = $valueStatusInvoice->total;
            }


            // query device purchase
            $query_devicepurchase = DB::table('device_purchases')->whereNull('deleted_at');

            if (isset($params['search_option']['date_from'])) {
                $query_devicepurchase = $query_devicepurchase->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

            }
            if (isset($params['search_option']['date_to'])) {
                $query_devicepurchase = $query_devicepurchase->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
            }

            if (isset($params['search_option']['roles'])) {

                $getArrayUser = DB::table('model_has_roles')
                    ->select('model_id')->where('role_id',$params['search_option']['roles'])
                    ->groupBy('model_id')
                    ->get()->toArray();
                $arrayIdUser = array();
                foreach($getArrayUser as $val){
                    $arrayIdUser[] = $val->model_id;
                }
                $query_devicepurchase = $query_devicepurchase->whereIn('creator_id', $arrayIdUser);
            }

            if (isset($params['search_option']['users'])) {
                $query_devicepurchase = $query_devicepurchase->where('creator_id', $params['search_option']['users']);
            }


            $query_devicepurchase = $query_devicepurchase->where('project_id', $params['project_id']);

            $result['total_devicepurchase'] = $query_devicepurchase->count();
            $result['needhandle_devicepurchase'] = 0;
            $result['processed_devicepurchase'] = 0;
            $result['returned_devicepurchase'] = 0;
            $statusAllDevicepurchase = $query_devicepurchase->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->get()->toArray();

            foreach($statusAllDevicepurchase as $valueStatusDevicepurchase){
                if($valueStatusDevicepurchase->status == 2) $result['needhandle_devicepurchase'] = $valueStatusDevicepurchase->total;
                if($valueStatusDevicepurchase->status == 3) $result['processed_devicepurchase'] = $valueStatusDevicepurchase->total;
                if($valueStatusDevicepurchase->status == 4) $result['returned_devicepurchase'] = $valueStatusDevicepurchase->total;
            }

        }else{

            if(isset($params['search_option']['projects'])){
                $project_id = $params['search_option']['projects'];
                // query task
                $query_task = DB::table('tasks')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_task = $query_task->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_task = $query_task->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }
                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_task = $query_task->whereIn('to_user', $arrayIdUser);
                }
                if (isset($params['search_option']['users'])) {
                    $query_task = $query_task->where('to_user', $params['search_option']['users']);
                }

                $query_task = $query_task->where('project_id', $project_id);


                $result['total_task'] = $query_task->count();
                $result['needhandle_task'] = 0;
                $result['processed_task'] = 0;
                $result['returned_task'] = 0;
                $statusAllTask = $query_task->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach($statusAllTask as $valueStatusTask){
                    if($valueStatusTask->status == 1) $result['needhandle_task'] = $valueStatusTask->total;
                    if($valueStatusTask->status == 3) $result['processed_task'] = $valueStatusTask->total;
                    //if($valueStatusTask['status'] == 3) $result['returned_task'] = $valueStatusTask['total'];
                }





                //query category supplies

                $query_requestsupplies = DB::table('supplies_requests')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_requestsupplies = $query_requestsupplies->whereIn('created_by', $arrayIdUser);
                }

                if (isset($params['search_option']['users'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_by', $params['search_option']['users']);
                }

                $query_requestsupplies = $query_requestsupplies->where('project_id', $project_id);


                $result['total_requestsupplies'] = $query_requestsupplies->count();
                $result['needhandle_requestsupplies'] = 0;
                $result['processed_requestsupplies'] = 0;
                $result['returned_requestsupplies'] = 0;
                $statusAllRequestsupplies = $query_requestsupplies->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach($statusAllRequestsupplies as $valueStatusRequestsupplies){
                    if($valueStatusRequestsupplies->status == 2) $result['needhandle_requestsupplies'] = $valueStatusRequestsupplies->total;
                    if($valueStatusRequestsupplies->status == 3) $result['processed_requestsupplies'] = $valueStatusRequestsupplies->total;
                    if($valueStatusRequestsupplies->status == 4) $result['returned_requestsupplies'] = $valueStatusRequestsupplies->total;
                }


                // query invoice
                $query_invoice = DB::table('invoices')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_invoice = $query_invoice->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_invoice = $query_invoice->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_invoice = $query_invoice->whereIn('created_by', $arrayIdUser);
                }

                if (isset($params['search_option']['users'])) {
                    $query_invoice = $query_invoice->where('created_by', $params['search_option']['users']);
                }


                $query_invoice = $query_invoice->where('project_id', $project_id);

                $result['total_invoice'] = $query_invoice->count();
                $result['needhandle_invoice'] = 0;
                $result['processed_invoice'] = 0;
                $result['returned_invoice'] = 0;
                $statusAllInvoice = $query_invoice->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach($statusAllInvoice as $valueStatusInvoice){
                    if($valueStatusInvoice->status == 2) $result['needhandle_invoice'] = $valueStatusInvoice->total;
                    if($valueStatusInvoice->status == 3) $result['processed_invoice'] = $valueStatusInvoice->total;
                    if($valueStatusInvoice->status == 4) $result['returned_invoice'] = $valueStatusInvoice->total;
                }


                // query device purchase
                $query_devicepurchase = DB::table('device_purchases')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_devicepurchase = $query_devicepurchase->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_devicepurchase = $query_devicepurchase->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_devicepurchase = $query_devicepurchase->whereIn('creator_id', $arrayIdUser);
                }

                if (isset($params['search_option']['users'])) {
                    $query_devicepurchase = $query_devicepurchase->where('creator_id', $params['search_option']['users']);
                }


                $query_devicepurchase = $query_devicepurchase->where('project_id', $project_id);

                $result['total_devicepurchase'] = $query_devicepurchase->count();
                $result['needhandle_devicepurchase'] = 0;
                $result['processed_devicepurchase'] = 0;
                $result['returned_devicepurchase'] = 0;
                $statusAllDevicepurchase = $query_devicepurchase->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach($statusAllDevicepurchase as $valueStatusDevicepurchase){
                    if($valueStatusDevicepurchase->status == 2) $result['needhandle_devicepurchase'] = $valueStatusDevicepurchase->total;
                    if($valueStatusDevicepurchase->status == 3) $result['processed_devicepurchase'] = $valueStatusDevicepurchase->total;
                    if($valueStatusDevicepurchase->status == 4) $result['returned_devicepurchase'] = $valueStatusDevicepurchase->total;
                }
                $result['project_id'] = $params['search_option']['projects'];
            }else {


                $query_task = DB::table('tasks')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_task = $query_task->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_task = $query_task->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }
                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_task = $query_task->whereIn('to_user', $arrayIdUser);
                }
                if (isset($params['search_option']['users'])) {
                    $query_task = $query_task->where('to_user', $params['search_option']['users']);
                }


                $result['total_task'] = $query_task->count();

                $result['needhandle_task'] = 0;
                $result['processed_task'] = 0;
                $result['returned_task'] = 0;
                $statusAllTask = $query_task->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach ($statusAllTask as $valueStatusTask) {
                    if ($valueStatusTask->status == 1) $result['needhandle_task'] = $valueStatusTask->total;
                    if ($valueStatusTask->status == 3) $result['processed_task'] = $valueStatusTask->total;
                    /*if ($valueStatusTask->status == 3) $result['returned_task'] = $valueStatusTask->total;*/
                }
               //    dd($statusAllTask);

                //query category supplies

                $query_requestsupplies = DB::table('supplies_requests')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_requestsupplies = $query_requestsupplies->whereIn('created_by', $arrayIdUser);
                }


                if (isset($params['search_option']['users'])) {
                    $query_requestsupplies = $query_requestsupplies->where('created_by', $params['search_option']['users']);
                }


                $result['total_requestsupplies'] = $query_requestsupplies->count();
                $result['needhandle_requestsupplies'] = 0;
                $result['processed_requestsupplies'] = 0;
                $result['returned_requestsupplies'] = 0;
                $statusAllRequestsupplies = $query_requestsupplies->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();
                foreach ($statusAllRequestsupplies as $valueStatusRequestsupplies) {
                    if ($valueStatusRequestsupplies->status == 2) $result['needhandle_requestsupplies'] = $valueStatusRequestsupplies->total;
                    if ($valueStatusRequestsupplies->status == 3) $result['processed_requestsupplies'] = $valueStatusRequestsupplies->total;
                    if ($valueStatusRequestsupplies->status == 4) $result['returned_requestsupplies'] = $valueStatusRequestsupplies->total;
                }
                //dd($statusAllRequestsupplies);


                // query invoice
                $query_invoice = DB::table('invoices')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_invoice = $query_invoice->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_invoice = $query_invoice->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_invoice = $query_invoice->whereIn('created_by', $arrayIdUser);
                }

                if (isset($params['search_option']['users'])) {
                    $query_invoice = $query_invoice->where('created_by', $params['search_option']['users']);
                }


                $result['total_invoice'] = $query_invoice->count();
                $result['needhandle_invoice'] = 0;
                $result['processed_invoice'] = 0;
                $result['returned_invoice'] = 0;
                $statusAllInvoice = $query_invoice->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach ($statusAllInvoice as $valueStatusInvoice) {
                    if ($valueStatusInvoice->status == 2) $result['needhandle_invoice'] = $valueStatusInvoice->total;
                    if ($valueStatusInvoice->status == 3) $result['processed_invoice'] = $valueStatusInvoice->total;
                    if ($valueStatusInvoice->status == 4) $result['returned_invoice'] = $valueStatusInvoice->total;
                }


                // query device purchase
                $query_devicepurchase = DB::table('device_purchases')->whereNull('deleted_at');

                if (isset($params['search_option']['date_from'])) {
                    $query_devicepurchase = $query_devicepurchase->where('created_at', '>=', carbon_date($params['search_option']['date_from'])->startOfDay());

                }
                if (isset($params['search_option']['date_to'])) {
                    $query_devicepurchase = $query_devicepurchase->where('created_at', '<=', carbon_date($params['search_option']['date_to'])->endOfDay());
                }

                if (isset($params['search_option']['roles'])) {

                    $getArrayUser = DB::table('model_has_roles')
                        ->select('model_id')->where('role_id',$params['search_option']['roles'])
                        ->groupBy('model_id')
                        ->get()->toArray();
                    $arrayIdUser = array();
                    foreach($getArrayUser as $val){
                        $arrayIdUser[] = $val->model_id;
                    }
                    $query_devicepurchase = $query_devicepurchase->whereIn('creator_id', $arrayIdUser);
                }

                if (isset($params['search_option']['users'])) {
                    $query_devicepurchase = $query_devicepurchase->where('creator_id', $params['search_option']['users']);
                }

                $result['total_devicepurchase'] = $query_devicepurchase->count();
                $result['needhandle_devicepurchase'] = 0;
                $result['processed_devicepurchase'] = 0;
                $result['returned_devicepurchase'] = 0;
                $statusAllDevicepurchase = $query_devicepurchase->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')->get()->toArray();

                foreach ($statusAllDevicepurchase as $valueStatusDevicepurchase) {
                    if ($valueStatusDevicepurchase->status == 2) $result['needhandle_devicepurchase'] = $valueStatusDevicepurchase->total;
                    if ($valueStatusDevicepurchase->status == 3) $result['processed_devicepurchase'] = $valueStatusDevicepurchase->total;
                    if ($valueStatusDevicepurchase->status == 4) $result['returned_devicepurchase'] = $valueStatusDevicepurchase->total;
                }
            }

        }

        return $result;
    }


}