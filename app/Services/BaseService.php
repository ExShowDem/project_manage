<?php

namespace App\Services;

use App\Enums\CommonStatus;
use App\Enums\TaskStatus;
use App\Models\ProcessLog;
use App\Models\Stocktaking;
use App\Services\Api\RequestService;
use App\Services\Api\TaskService;
use App\Services\Api\ReceiptInputService;
use App\Services\Api\ReceiptOutputService;
use App\Services\Api\ReceiptTransferService;
use App\Services\Api\DeviceRoundRobinService;
use App\Services\Api\SubContractorService;
use App\Services\Api\CensorSubService;
use App\Services\Api\ContractSubService;
use App\Services\Api\PaymentOrderService;
use App\Services\Api\SupplierService;
use App\Services\Api\UserService;
use App\Services\Api\RoleService;
use App\Services\Api\ProjectService;
use App\Services\Api\ResourceService;
use App\Services\Api\ResourceTypeService;
use App\Services\Api\CategorySupplyService;
use App\Services\Api\ItemService;
use Illuminate\Support\Collection;
use App\Events\ActionLogEvent;
use App\Models\Traits\TaskableTrait;
use App\Models\Task;
use App\Models\Item;
use App\Models\RequestSupply;
use App\Models\SupplyEachRequest;
use App\Models\ContractSubContractor;
use App\Models\PaymentOrder;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Artisan;
use App\Models\ReceiptInput;
use App\Models\ReceiptOutput;
use App\Models\ReceiptTransfer;
use App\Models\Inventory;
use App\Enums\ReceiptOutputStatus;

class BaseService
{
    protected $model;

    public function __construct()
    {

    }

    public function create($params)
    {
        return $this->model->create($params);
    }

    protected function deleteRelatedTask($model, $taskableId, $taskableType)
    {
        $uses             = class_uses($model);
        $taskableTrait    = TaskableTrait::class;
        $useTaskableTrait = isset($uses[$taskableTrait]) && ($uses[$taskableTrait] === $taskableTrait);

        if ($useTaskableTrait)
        {
            $task = Task::where('taskable_id', $taskableId)->where('taskable_type', $taskableType)->first();

            if (!is_null($task))
            {
                $task->delete();
            }
        }
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        $this->deleteRelatedTask($this->model, $model->getAttributes()['id'], get_class($model));

        if (get_class($model) === ContractSubContractor::class)
        {
            $contractSubContractorId = $model->getAttributes()['id'];
            $paymentOrders = PaymentOrder::where('contract_subcontractor_id', $contractSubContractorId)->get();

            foreach ($paymentOrders as $paymentOrder)
            {
                $paymentOrderId = $paymentOrder->getAttributes()['id'];

                $this->deleteRelatedTask($paymentOrder, $paymentOrderId, PaymentOrder::class);

                $paymentOrder->delete();
            }
        }

        if (get_class($model) === Item::class)
        {
            $itemId          = $model->getAttributes()['id'];
            $requestSupplies = RequestSupply::where('item_id', $itemId)->get();

            foreach ($requestSupplies as $requestSupply)
            {
                $requestSupplyId = $requestSupply->getAttributes()['id'];

                $this->deleteRelatedTask($requestSupply, $requestSupplyId, RequestSupply::class);

                $requestEachSupplies = SupplyEachRequest::where('supplies_request_id', $requestSupplyId)->get();

                foreach ($requestEachSupplies as $requestEachSupply)
                {
                    $requestEachSupply->delete();
                }

                $requestSupply->delete();
            }
        }

        $pivotName = getPivotName($model, 'model');

        if (get_class($model) === ReceiptInput::class)
        {
            $this->inventoryService->updateInventory($model->getAttributes()['input_id'], $model->$pivotName, Inventory::INPUT, true);
        }

        if (get_class($model) === ReceiptOutput::class)
        {
            $this->inventoryService->updateInventory($model->getAttributes()['output_id'], $model->$pivotName, Inventory::OUTPUT, true);

            $this->undoUpdateDeliveryOnDemand($model);
        }

        if (get_class($model) === ReceiptTransfer::class)
        {
            $this->inventoryService->updateInventory($model->getAttributes()['input_id'], $model->$pivotName, Inventory::INPUT, true);
            $this->inventoryService->updateInventory($model->getAttributes()['output_id'], $model->$pivotName, Inventory::OUTPUT, true);
        }

        if ($pivotName !== '')
        {
            $this->logPivotAction('detach', $model, $pivotName);
        }

        if (get_class($model) === Project::class)
        {
            $exitCode = Artisan::call('db:seed', ['--class' => 'PermissionTableSeeder']);

            if ($exitCode !== 0)
            {
                throw new \Exception('Project Permissions did not sync properly. Please contact admin.');
            }
        }

        return $model->delete();
    }

    public function find($id, $with = null)
    {
        $query = $this->model;

        if ($with)
        {
            $query = $query->with($with);
        }

        return $query->find($id);
    }

    public function update($id, $params)
    {
        $model = $this->model->find($id);

        $model->update($params);

        return $this->model->find($id);
    }

    protected function forwardProcess(&$model, $inputs)
    {
        if (isset($inputs['forward_data'])) {
            $inputs = new Collection($inputs);
            $inputsForward = $inputs->all();
            $model->update(['status' => CommonStatus::FORWARDED]);

            TaskService::createTask($model, $inputsForward);
        }

        if ($this instanceof ReceiptInputService || $this instanceof ReceiptTransferService)
        {
            $inputs['project_id'] = $inputs['input_id'];
        }
        elseif ($this instanceof ReceiptOutputService)
        {
            $inputs['project_id'] = $inputs['output_id'];
        }
        elseif ($this instanceof DeviceRoundRobinService)
        {
            $inputs['project_id'] = $inputs['from_project_id'];
        }
        elseif ($this instanceof ProjectService)
        {
            unset($inputs['image_base64']);
            $model->status = CommonStatus::CREATED;
        } 
        elseif ($this instanceof RoleService)
        {
            $model->status = CommonStatus::CREATED;
        }
        elseif ($this instanceof SubContractorService)
        {
            $model->status = CommonStatus::CREATED;
        }
        elseif ($this instanceof SupplierService)
        {
            $model->status = CommonStatus::CREATED;
        }
        elseif ($this instanceof ResourceService || $this instanceof ResourceTypeService)
        {
            $model->status = CommonStatus::CREATED;
        }
        elseif ($this instanceof RequestService) 
        {
            $inputs['to_user_name'] = $model->receiver_name;
        } 
        elseif ($this instanceof CategorySupplyService) 
        {
            $model->status = CommonStatus::CREATED;
        }
        elseif ($model instanceof Stocktaking) 
        {
            $inputs['project_name'] = $model->project->name ?? null;
        } 
        elseif ($model instanceof Item) 
        {
            $inputs['project_name'] = $model->project->name ?? null;
        }

        $this->logObjectData($model, $inputs);

        return false;
    }

    protected function logObjectData($model, $inputs)
    {
        ProcessLog::create([
            'project_id' => $inputs['project_id'] ?? 0,
            'process_user_id' => auth()->id(),
            'status' => $model->status,
            'table_id' => $model->id,
            'table_type' => get_class($model),
            'comment' => '',
            'name' => $model->created_at == $model->updated_at ? 'Tạo mới' : 'Cập nhật',
            'data_object' => $inputs,
        ]);
    }

    public function tracking($id, $table = null)
    {
        $type = $table ?: get_class($this->model);

        $items = ProcessLog::where([
//            'project_id' => '',
            'table_type' => $type,
            'table_id' => $id,
        ])->get();

        return $items;
    }

    public function logPivotAction($type, $entry, $pivotName, $pivotData = [])
    {
        $parentModel      = get_class($entry);
        $relationInstance = $entry->$pivotName();
        $pivotTable       = $relationInstance->getTable();
        $foreignPivot     = $relationInstance->getForeignPivotKeyName();
        $attributes       = $entry->getAttributes();
        $parentKeyValue   = $attributes[$relationInstance->getParentKeyName()];

        switch ($type)
        {
            case 'attach':

                if ($pivotName === 'roles')
                {
                    $entry->assignRole($pivotData);
                }
                elseif ($pivotName === 'permissions')
                {
                    $entry->givePermissionTo($pivotData);
                }
                else
                {
                    $relationInstance->$type($pivotData);
                }

                $after  = DB::table($pivotTable)->where($foreignPivot, $parentKeyValue)->get()->all();
                $params = ['before' => [], 'after'  => $after];

                break;

            case 'sync':

                $before  = DB::table($pivotTable)->where($foreignPivot, $parentKeyValue)->get()->all();

                if ($pivotName === 'roles')
                {
                    // syncRoles are not used here because the regular way works equally as well.
                    $changes = $relationInstance->$type($pivotData);
                }
                elseif ($pivotName === 'permissions')
                {
                    $changes = $entry->syncPermissions($pivotData);
                }
                else
                {
                    $changes = $relationInstance->$type($pivotData);
                }

                $after   = DB::table($pivotTable)->where($foreignPivot, $parentKeyValue)->get()->all();
                $params  = ['before' => $before, 'after'  => $after];

                break;

            case 'detach':

                $before = DB::table($pivotTable)->where($foreignPivot, $parentKeyValue)->get()->all();
                $relationInstance->$type();
                $params = ['before' => $before, 'after'  => []];

                break;
        }

        if ($type === 'sync' && $this instanceof RoleService)
        {
            $befores = [];
            $afters  = [];

            foreach ($before as $b)
            {
                $befores[] = $b->permission_id;
            }

            foreach ($after as $a)
            {
                $afters[] = $a->permission_id;
            }

            if (array_diff($befores, $afters))
            {
                $this->logAction($parentModel, $relationInstance, 'update_pivot', $foreignPivot, $parentKeyValue, $params);
            }
        }
        elseif ($type === 'sync')
        {
            if (!empty($changes['attached']) || !empty($changes['detached']) || !empty($changes['updated']))
            {
                $this->logAction($parentModel, $relationInstance, 'update_pivot', $foreignPivot, $parentKeyValue, $params);
            }
        }
        else
        {
            $this->logAction($parentModel, $relationInstance, 'update_pivot', $foreignPivot, $parentKeyValue, $params);
        }
    }

    public function logAction($parentModel, $relationInstance, $method, $keyName, $keyValue, $params)
    {
        $pivotTable    = $relationInstance->getTable();//item_supplies
        $qParentKey    = $relationInstance->getQualifiedParentKeyName();//items.id
        $parentKey     = $relationInstance->getParentKeyName();//id
        $qForeignPivot = $relationInstance->getQualifiedForeignPivotKeyName();//item_supplies.item_id
        $qRelatedPivot = $relationInstance->getQualifiedRelatedPivotKeyName();//item_supplies.supply_id
        $relatedPivot  = $relationInstance->getRelatedPivotKeyName();//supply_id
        $relatedKey    = $relationInstance->getRelatedKeyName();//id
        $relationName  = $relationInstance->getRelationName();//supplies

        $params['relation']         = $relationName;
        $params['parent_key_name']  = $parentKey;
        $params['related_key_name'] = $relatedPivot;

        event(new ActionLogEvent($parentModel, $pivotTable, $method, $keyName, $keyValue, $params));
    }

    protected function createGeneric($inputs, $key)
    {
        try
        {
            list($inputs, $pivotData, $pivotName) = $this->smoothDataBeforeSave($inputs);

            DB::beginTransaction();

            $entry = self::create($inputs);

            if (method_exists($this, 'createSpec'))
            {
                $this->createSpec($entry, $inputs, $pivotData);
            }

            if ($pivotName !== '')
            {
                $this->logPivotAction('attach', $entry, $pivotName, $pivotData);
            }

            $this->forwardProcess($entry, $inputs);

            DB::commit();

            if ($this instanceof ReceiptOutputService && $entry->status->value === ReceiptOutputStatus::APPROVED)
            {
                $this->updateItemProgress($inputs, $pivotData);
                $this->updateRequestProgress($inputs, $pivotData);
            }

            $result = [
                'error' => false,
                'response' => $entry,
            ];
        }
        catch (\Exception $e)
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => [$key => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    protected function updateGeneric($id, $inputs, $key)
    {
        try
        {
            list($inputs, $pivotData, $pivotName) = $this->smoothDataBeforeSave($inputs);

            DB::beginTransaction();

            $inputs1 = [];

            foreach ($inputs as $key => $value)
            {
                if (!is_array($value))
                {
                    $inputs1[$key] = $value;
                }
            }

            $entry = self::update($id, $inputs1);

            if (method_exists($this, 'updateSpec'))
            {
                $this->updateSpec($entry, $inputs, $pivotData);
            }

            if ($pivotName !== '')
            {
                $this->logPivotAction('sync', $entry, $pivotName, $pivotData);
            }

            if (isset($inputs['status']) && $inputs['status'] === CommonStatus::APPROVED)
            {
                $tasks = Task::where(['taskable_type' => get_class($this->model), 'taskable_id' => $id])->get();

                if (!is_null($tasks))
                {
                    foreach ($tasks as $task) 
                    {
                        $task->status = CommonStatus::APPROVED;
                        $task->save();
                    }
                }
            }

            $this->forwardProcess($entry, $inputs);

            DB::commit();

            if ($this instanceof ReceiptOutputService && $entry->status->value === ReceiptOutputStatus::APPROVED)
            {
                $this->updateItemProgress($inputs, $pivotData);
                $this->updateRequestProgress($inputs, $pivotData);
            }

            $this->updateNotify($entry, $id, $inputs);

            $result = [
                'error' => false,
                'response' => $entry,
            ];
        }
        catch (\Exception $e)
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => [$key => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    protected function updateNotify($entry, $id, $inputs = null)
    {
        $parentModel      = get_class($entry);
        $attributes       = $entry->getAttributes();
        $toUserIds        = getNotifiedUsers($entry);
        $type             = typeToModelLookup($parentModel, false);
        list(, , $urlKey) = detectModel($type, false);

        if ($this instanceof ReceiptInputService || $this instanceof ReceiptTransferService)
        {
            $attributes['project_id'] = $attributes['input_id'];
        }
        elseif ($this instanceof ReceiptOutputService)
        {
            $attributes['project_id'] = $attributes['output_id'];
        }
        elseif ($this instanceof DeviceRoundRobinService)
        {
            $attributes['project_id'] = $attributes['from_project_id'];
        }
        elseif ($this instanceof SubContractorService || $this instanceof SupplierService
            || $this instanceof ResourceTypeService || $this instanceof ResourceService)
        {
            $attributes['project_id'] = $inputs['current_project_id'];
        }
        elseif ($this instanceof UserService || $this instanceof RoleService)
        {
            if (isset($inputs['current_project_id']) && $inputs['current_project_id'] !== 'undefined')
            {
                $attributes['project_id'] = $inputs['current_project_id'];
            }
            else
            {
                $attributes['project_id'] = 1;
            }
        }
        elseif ($this instanceof ProjectService)
        {
            $attributes['project_id'] = $id;
        }

        if (method_exists($this, 'getSpecNoticeUrl'))
        {
            $url = $this->getSpecNoticeUrl($attributes, $urlKey, $id);
        }
        else
        {
            $url = route($urlKey . '.show', ['projectId' => $attributes['project_id'], 'id' => $id]);
        }

        if ($this instanceof ContractSubService || $this instanceof PaymentOrderService)
        {
            $attributes['subcontractor_name'] = $entry->Subcontractor->name;
        }

        if ($this instanceof CensorSubService)
        {
            $attributes['subcontractor_name'] = $entry->package;
        }

        $notice = [
            'url'             => $url,
            'body'            => $this->getNoticeBody($attributes),
            'project_id'      => $attributes['project_id'],
            'targetable_id'   => $id,
            'targetable_type' => $parentModel,
        ];

        $notice['content'] = $notice['body'];

        sendNotifications($toUserIds, $notice);
    }

    protected function preSmooth($inputs, $pivotName = '', $fields)
    {
        if (isset($fields['status']) && $fields['status'] === true)
        {
            if (isset($inputs['action']) && $inputs['action'] === 'complete')
            {
                $inputs['status'] = CommonStatus::APPROVED;
            }
            elseif (!isset($inputs['id']))
            {
                $inputs['status'] = CommonStatus::CREATED;
            }
        }

        if (isset($fields['dates']) && !empty($fields['dates']))
        {
            foreach ($fields['dates'] as $date)
            {
                if (isset($inputs[ $date['field'] ]))
                {
                    $inputs[ $date['field'] ] = Carbon::createFromFormat($date['fromFormat'], $inputs[ $date['field'] ])->format($date['toFormat']);
                }
            }
        }

        if ($pivotName !== '')
        {
            $mustHaveQuantity = 'quantity';

            if (isset($fields['quantity']) && $fields['quantity'] !== '')
            {
                $mustHaveQuantity = $fields['quantity'];
            }

            foreach ($inputs[$pivotName] as $key => $pivot)
            {
                if ($this instanceof ItemService && (boolean) $pivot['is_vlk'])
                {
                    continue;
                }

                if (!isset($pivot[$mustHaveQuantity]))
                {
                    unset($inputs[$pivotName][$key]);
                }
                elseif (!is_numeric($pivot[$mustHaveQuantity]))
                {
                    unset($inputs[$pivotName][$key]);
                }
                elseif (is_numeric($pivot[$mustHaveQuantity]) 
                    && (floatval($pivot[$mustHaveQuantity]) === 0 || intval($pivot[$mustHaveQuantity]) === 0))
                {
                    unset($inputs[$pivotName][$key]);
                }
            }
        }

        return $inputs;
    }

    protected function postSmooth($inputs, $pivotData)
    {
        if (empty($pivotData))
        {
            throw new \Exception('Bạn Chưa Nhập Số Lượng.');
        }

        return [$inputs, $pivotData];
    }
}
