<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskDetailResource;
use App\Http\Resources\TaskResource;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OfferBuy;
use App\Models\PaymentOrder;
use App\Models\Plan;
use App\Models\ReceiptInput;
use App\Models\ReceiptOutput;
use App\Models\ReceiptTransfer;
use App\Models\RequestSupply;
use App\Models\Stocktaking;
use App\Models\DeviceClearance;
use App\Models\DeviceInventory;
use App\Models\DeviceMaintainence;
use App\Models\DeviceRental;
use App\Models\DeviceReturnToCompany;
use App\Models\DeviceToProject;
use App\Models\DeviceInput;
use App\Models\DeviceEstimate;
use App\Models\DeviceMonthlyEstimate;
use App\Models\DeviceIssuance;
use App\Models\DeviceTransfer;
use App\Models\DevicePurchaseRequest;
use App\Models\DevicePurchase;
use App\Models\DeviceRoundRobin;
use App\Models\DeviceContract;
use App\Services\Api\TaskService;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    protected $taskService;
    protected $module = 'tasks';

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function getMyTasks(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'project_id');
        $tasks = TaskResource::apiPaginate($this->taskService->getMyTasks($params), $request);
        return $this->responseSuccess($tasks);
    }

    public function show(Request $request, $id)
    {
        $item = new TaskDetailResource($this->taskService->find($id, ['project', 'taskable']));

        return $this->responseSuccess(compact('item'));
    }

    public function approve(Request $request, $id)
    {
        $task = $this->taskService->find($id, ['taskable']);
        $this->checkPermission($task);

        if ($this->taskService->getHandledTask($task)) {
            return $this->responseError('api.code.task.handled_task');
        }

        $inputs = $request->all();

        $result = $this->taskService->approve($id, $inputs);

        return $this->responseSuccess(compact('result'));
    }

    public function forward(Request $request, $id)
    {
        $task = $this->taskService->find($id, ['taskable']);

        if ($this->taskService->getHandledTask($task)) {
            return $this->responseError('api.code.task.handled_task');
        }

        $inputs = $request->all();
        $inputs['created_by'] = auth()->id();

        $result = $this->taskService->forward($id, $inputs);
        if ($result) {
            return $this->responseSuccess(compact('result'));
        }

        return $this->responseError('api.code.common.update_failed');
    }

    public function return(Request $request, $id)
    {
        $task = $this->taskService->find($id, ['taskable']);
        $this->checkPermission($task);

        if ($this->taskService->getHandledTask($task)) {
            return $this->responseError('api.code.task.handled_task');
        }

        $inputs = $request->all();

        $result = $this->taskService->return($id, $inputs);

        return $this->responseSuccess(compact('result'));
    }

    public function cancel(Request $request, $id)
    {
        $task = $this->taskService->find($id, ['taskable']);
        $this->checkPermission($task);

        if ($this->taskService->getHandledTask($task)) {
            return $this->responseError('api.code.task.handled_task');
        }

        $inputs = $request->all();

        $result = $this->taskService->cancel($id, $inputs);

        return $this->responseSuccess(compact('result'));
    }

    private function checkPermission($task)
    {
        $user = auth()->user();
        $permission = '';

        switch ($task->taskable_type) {
            case Plan::class:
                $permission = 'plans.supplies.approve';
                break;
            case RequestSupply::class:
                if ($task->taskable->isFromCompany()) {
                    $permission = 'supplies.request_from_company.approve';
                } else {
                    $permission = 'supplies.request_from_project.approve';
                }
                break;
            case ReceiptInput::class:
                $permission = 'receipt_inputs.approve';
                break;
            case ReceiptOutput::class:
                $permission = 'receipt_outputs.approve';
                break;
            case ReceiptTransfer::class:
                $permission = 'receipt_transfers.approve';
                break;
            case OfferBuy::class:
                $permission = 'offer_buys.approve';
                break;
            case Stocktaking::class:
                $permission = 'stocktaking.approve';
                break;
            case PaymentOrder::class:
                $permission = 'payment_order.approve';
                break;
            case Item::class:
                $permission = 'items.approve';
                break;
            case Invoice::class:
                $permission = 'invoices.approve';
                break;
            case DeviceClearance::class:
                $permission = 'device_clearance.approve';
                break;
            case DeviceInventory::class:
                $permission = 'device_inventory.approve';
                break;
            case DeviceMaintainence::class:
                $permission = 'device_maintainence.approve';
                break;
            case DeviceRental::class:
                $permission = 'device_rental.approve';
                break;
            case DeviceReturnToCompany::class:
                $permission = 'device_company.approve';
                break;
            case DeviceToProject::class:
                $permission = 'device_project.approve';
                break;
            case DeviceInput::class:
                $permission = 'device_input.approve';
                break;
            case DeviceEstimate::class:
                $permission = 'device_estimates.approve';
                break;
            case DeviceMonthlyEstimate::class:
                $permission = 'device_monthly_estimates.approve';
                break;
            case DeviceIssuance::class:
                $permission = 'device_issuance.approve';
                break;
            case DeviceTransfer::class:
                $permission = 'device_transfer.approve';
                break;
            case DevicePurchaseRequest::class:
                $permission = 'device_purchase_request.approve';
                break;
            case DevicePurchase::class:
                $permission = 'device_purchase.approve';
                break;
            case DeviceRoundRobin::class:
                $permission = 'device_round_robin.approve';
                break;
            case DeviceContract::class:
                $permission = 'device_contract.approve';
                break;
        }

        abort_if(!$user->can($permission), 403);
    }
}
