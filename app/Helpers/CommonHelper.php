<?php

use App\Models\MppTask;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;
use App\Models\Subcontractor;
use App\Models\CensorSubContractor;
use App\Models\ContractSubContractor;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OfferBuy;
use App\Models\PaymentOrder;
use App\Models\ReceiptInput;
use App\Models\ReceiptOutput;
use App\Models\ReceiptTransfer;
use App\Models\RequestSupply;
use App\Models\Stocktaking;
use App\Models\TicketImport;
use App\Models\Task;
use App\Models\DeviceBill;
use App\Models\DeviceClearance;
use App\Models\DeviceInventory;
use App\Models\DeviceMaintainence;
use App\Models\DeviceRental;
use App\Models\DeviceReturnToCompany;
use App\Models\DeviceStats;
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
use App\Models\Supplier;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\CategorySupplies;
use Carbon\Carbon;

function carbon_date($date, $format = 'd/m/Y')
{
    return $date ? Carbon::createFromFormat($format, $date) : null;
}

function generate_code_for_model(Model $model, $projectId = null)
{
    switch (get_class($model))
    {
        case CensorSubContractor::class:
            $prefix = 'HSNTP';
            break;
        case ContractSubContractor::class:
            $prefix = 'HDNTP';
            break;
        case Invoice::class:
            $prefix = 'HD';
            break;
        case Item::class:
            $prefix = 'HM';
            break;
        case OfferBuy::class:
            $prefix = 'DXM';
            break;
        case PaymentOrder::class:
            $prefix = 'DNTT';
            break;
        case Plan::class:
            $prefix = 'KH';
            break;
        case ReceiptInput::class:
            $prefix = 'NK';
            break;
        case ReceiptOutput::class:
            $prefix = 'XK';
            break;
        case ReceiptTransfer::class:
            $prefix = 'CK';
            break;
        case RequestSupply::class:
            $projectCode = Project::find($projectId)->code;
            $projectCode = str_replace(' ', '', $projectCode);
            $numberValue = RequestSupply::where('project_id', '=', $projectId)->count() + 1;

            $prefix = $projectCode . $numberValue;
            break;
        case Stocktaking::class:
            $prefix = 'KK';
            break;
        case Task::class:
            $prefix = '';
            break;
        case DeviceBill::class:
            $prefix = 'DB';
            break;
        case DeviceClearance::class:
            $prefix = 'DC';
            break;
        case DeviceInventory::class:
            $prefix = 'DI';
            break;
        case DeviceMaintainence::class:
            $prefix = 'DM';
            break;
        case DeviceRental::class:
            $prefix = 'DR';
            break;
        case DeviceReturnToCompany::class:
            $prefix = 'DRTC';
            break;
        case DeviceStats::class:
            $prefix = 'DS';
            break;
        case DeviceToProject::class:
            $prefix = 'DTP';
            break;
        case DeviceInput::class:
            $prefix = 'DIN';
            break;
        case DeviceEstimate::class:
            $prefix = 'DES';
            break;
        case DeviceMonthlyEstimate::class:
            $prefix = 'DMES';
            break;
        case DeviceIssuance::class:
            $prefix = 'DIS';
            break;
        case DeviceTransfer::class:
            $prefix = 'DT';
            break;
        case DevicePurchaseRequest::class:
            $prefix = 'DPR';
            break;
        case DevicePurchase::class:
            $prefix = 'DP';
            break;
        case DeviceContract::class:
            $prefix = 'DCO';
            break;
        default:
            $prefix = '';
            break;
    }

    if (RequestSupply::class === get_class($model))
    {
        return $prefix;
    }
    else
    {
        return $prefix . date('dmy') . ($model->count() + 1);
    }
}

function typeToModelLookup($key, $isTypeToModel = true)
{
    $dict = [
        'sub-contractors' => Subcontractor::class,
        'censor-sub' => CensorSubContractor::class,
        'contract-sub' => ContractSubContractor::class,
        'invoice' => Invoice::class,
        'item' => Item::class,
        'offer-buy' => OfferBuy::class,
        'payment-order' => PaymentOrder::class,
        'plan' => Plan::class,
        'receipt-input' => ReceiptInput::class,
        'receipt-output' => ReceiptOutput::class,
        'receipt-transfer' => ReceiptTransfer::class,
        'request' => RequestSupply::class,
        'stocktaking' => Stocktaking::class,
        'device-bill' => DeviceBill::class,
        'device-clearance' => DeviceClearance::class,
        'device-inventory' => DeviceInventory::class,
        'device-maintainence' => DeviceMaintainence::class,
        'device-rental' => DeviceRental::class,
        'device-company' => DeviceReturnToCompany::class,
        'device-stats' => DeviceStats::class,
        'device-project' => DeviceToProject::class,
        'device-input' => DeviceInput::class,
        'device-estimates' => DeviceEstimate::class,
        'device-monthly-estimates' => DeviceMonthlyEstimate::class,
        'device-issuance' => DeviceIssuance::class,
        'device-transfer' => DeviceTransfer::class,
        'device-purchase-request' => DevicePurchaseRequest::class,
        'device-purchase' => DevicePurchase::class,
        'device-round-robin' => DeviceRoundRobin::class,
        'device-contract' => DeviceContract::class,
        'supplier' => Supplier::class,
        'user' => User::class,
        'role' => Role::class,
        'project' => Project::class,
        'resource' => Resource::class,
        'resource-type' => ResourceType::class,
        'category-supply' => CategorySupplies::class,
    ];

    if (!$isTypeToModel)
    {
        $dict = array_flip($dict);
    }

    return $dict[$key];
}

function detectModel($request, $isRequest = true)
{
    if ($isRequest)
    {
        $type = strtolower($request->get('type'));
    }
    else
    {
        $type = $request;
    }

    switch ($type)
    {
        case 'sub-contractors':
            return [
                Subcontractor::class,
                'sub_contractors',
                'admin.projects.sub-contractors',
            ];
        case 'censor-sub':
            return [
                CensorSubContractor::class,
                'censor_sub_contractor',
                'admin.projects.censor-sub',
            ];
        case 'contract-sub':
            return [
                ContractSubContractor::class,
                'contract_sub_contractor',
                'admin.projects.contract-sub',
            ];
        case 'invoice':
            return [
                Invoice::class,
                'invoices',
                'admin.projects.invoices',
            ];
        case 'item':
            return [
                Item::class,
                'item',
                'admin.projects.items',
            ];
        case 'offer-buy':
            return [
                OfferBuy::class,
                'offer_buys',
                'admin.projects.offer-buys',
            ];
        case 'payment-order':
            return [
                PaymentOrder::class,
                'payment_order',
                'admin.projects.payment-order',
            ];
        case 'plan':
            return [
                Plan::class,
                'plan',
                'admin.projects.plans.supplies',
            ];
        case 'receipt-input':
            return [
                ReceiptInput::class,
                'receipt_input',
                'admin.projects.inventories.receipt-inputs',
            ];
        case 'receipt-output':
            return [
                ReceiptOutput::class,
                'receipt_outputs',
                'admin.projects.inventories.receipt-outputs',
            ];
        case 'receipt-transfer':
            return [
                ReceiptTransfer::class,
                'receipt_transfer',
                'admin.projects.inventories.receipt-transfers',
            ];
        case 'request':
            return [
                RequestSupply::class,
                'request',
                'admin.projects.requests.supplies',
            ];
        case 'stocktaking':
            return [
                Stocktaking::class,
                'stocktaking',
                'admin.projects.inventories.stocktaking',
            ];
        case 'mpptask':
            return [
                MppTask::class,
                'mpptasks',
                'admin.projects.plans.supplies',
            ];
        // case 'ticket_import':
        //     return [
        //         TicketImport::class,
        //         'ticket_import',
        //         '',
        //     ];
        case 'device-bill':
            return [
                DeviceBill::class,
                'device_bill',
                'admin.projects.devices.bill',
            ];
        case 'device-clearance':
            return [
                DeviceClearance::class,
                'device_clearance',
                'admin.projects.devices.clearance',
            ];
        case 'device-inventory':
            return [
                DeviceInventory::class,
                'device_inventory',
                'admin.projects.devices.inventory',
            ];
        case 'device-maintainence':
            return [
                DeviceMaintainence::class,
                'device_maintainence',
                'admin.projects.devices.maintainence',
            ];
        case 'device-rental':
            return [
                DeviceRental::class,
                'device_rental',
                'admin.projects.devices.rental',
            ];
        case 'device-company':
            return [
                DeviceReturnToCompany::class,
                'device_company',
                'admin.projects.devices.company',
            ];
        case 'device-stats':
            return [
                DeviceStats::class,
                'device_stats',
                'admin.projects.devices.stats',
            ];
        case 'device-project':
            return [
                DeviceToProject::class,
                'device_project',
                'admin.projects.devices.project',
            ];
        case 'device-input':
            return [
                DeviceInput::class,
                'device_input',
                'admin.projects.devices.input',
            ];
        case 'device-estimates':
            return [
                DeviceEstimate::class,
                'device_estimates',
                'admin.projects.devices.estimates',
            ];
        case 'device-monthly-estimates':
            return [
                DeviceMonthlyEstimate::class,
                'device_monthly_estimates',
                'admin.projects.devices.monthly_estimates',
            ];
        case 'device-issuance':
            return [
                DeviceIssuance::class,
                'device_issuance',
                'admin.projects.devices.issuance',
            ];
        case 'device-transfer':
            return [
                DeviceTransfer::class,
                'device_transfer',
                'admin.projects.devices.transfer',
            ];
        case 'device-purchase-request':
            return [
                DevicePurchaseRequest::class,
                'device_purchase_request',
                'admin.projects.devices.purchase_request',
            ];
        case 'device-purchase':
            return [
                DevicePurchase::class,
                'device_purchase',
                'admin.projects.devices.purchase',
            ];
        case 'device-round-robin':
            return [
                DeviceRoundRobin::class,
                'device_round_robin',
                'admin.projects.devices.round_robin',
            ];
        case 'device-contract':
            return [
                DeviceContract::class,
                'device_contract',
                'admin.projects.devices.contract',
            ];
        case 'supplier':
            return [
                Supplier::class,
                'supplier',
                'admin.projects.suppliers',
            ];
        case 'user':
            return [
                User::class,
                'user',
                'admin.projects.users',
            ];
        case 'role':
            return [
                Role::class,
                'role',
                'admin.projects.roles',
            ];
        case 'project':
            return [
                Project::class,
                'project',
                'admin.projects',
            ];
        case 'resource':
            return [
                Resource::class,
                'resource',
                'admin.projects.resources',
            ];
        case 'resource-type':
            return [
                ResourceType::class,
                'resource_type',
                'admin.projects.resource_types',
            ];
        case 'category-supply':
            return [
                CategorySupplies::class,
                'category_supply',
                'admin.projects.category-supplies',
            ];
    }
}

function explode_ids($string)
{
    $array = isset($string)
        ? array_map('intval', explode(',', $string))
        : [];

    return $array;
}


function generate_attach_file_name_payment_order($project, $extension)
{
    return $project->code . now()->year . now()->month . $project->number_file . '.' . $extension;
}


function get_task_key($model, $isModel = true)
{
    if ($isModel)
    {
        $model = get_class($model);
    }

    switch ($model)
    {
        case Plan::class:
            return 'tasks.plan_supplies';

        case RequestSupply::class:
            return 'tasks.request_supplies';

        case ReceiptInput::class:
            return 'tasks.receipt_input';

        case ReceiptOutput::class:
            return 'tasks.receipt_output';

        case ReceiptTransfer::class:
            return 'tasks.receipt_transfer';

        case OfferBuy::class:
            return 'tasks.offer_buy';

        case Stocktaking::class:
            return 'tasks.stocktaking';

        case PaymentOrder::class:
            return 'tasks.payment_order';

        case Item::class:
            return 'tasks.items';

        case Invoice::class:
            return 'tasks.invoice';

        case DeviceClearance::class:
            return 'tasks.device_clearance';

        case DeviceInventory::class:
            return 'tasks.device_inventory';

        case DeviceMaintainence::class:
            return 'tasks.device_maintainence';

        case DeviceRental::class:
            return 'tasks.device_rental';

        case DeviceReturnToCompany::class:
            return 'tasks.device_company';

        case DeviceToProject::class:
            return 'tasks.device_project';

        case DeviceInput::class:
            return 'tasks.device_input';

        case DeviceEstimate::class:
            return 'tasks.device_estimates';

        case DeviceMonthlyEstimate::class:
            return 'tasks.device_monthly_estimates';

        case DeviceIssuance::class:
            return 'tasks.device_issuance';

        case DeviceTransfer::class:
            return 'tasks.device_transfer';

        case DevicePurchaseRequest::class:
            return 'tasks.device_purchase_request';

        case DevicePurchase::class:
            return 'tasks.device_purchase';

        case DeviceRoundRobin::class:
            return 'tasks.device_round_robin';

        case DeviceContract::class:
            return 'tasks.device_contract';

    }
}

function getSubstituteForDeleted()
{
    return ['id' => 0, 'name' => 'Đã xóa', 'code' => 'Đã xóa', 'contract_number' => 'Đã xóa', 'device_issuance_id' => 'Đã xóa'];
}

function errorMessage($e)
{
    if (config('app.debug'))
    {
        $error = $e->getMessage() . ' in ' . $e->getFile() . ' at ' . $e->getLine();
    }
    else
    {
        if (get_class($e) === "Exception")
        {
            $error = $e->getMessage();
        }
        else
        { // We don't want to display system errors in production environment
            $error = 'Hệ thống lỗi. Xin hãy liên hệ bộ phận IT để giúp đỡ.';
        }
    }

    return $error; 
}

function getPivotName($object, $type)
{
    $pivots = ['supplies', 'devices', 'subcontractors'];
    $pivotName = '';

    foreach ($pivots as $pivot) 
    {
        switch ($type) 
        {
            case 'model':

                if (method_exists($object, $pivot))
                {
                    $pivotName = $pivot;
                    break;
                }

            case 'input':

                if (array_key_exists($pivot, $object))
                {
                    $pivotName = $pivot;
                    break;
                }
        }
    }

    return $pivotName;
}