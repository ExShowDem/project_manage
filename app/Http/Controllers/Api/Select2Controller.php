<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReceiverType;
use App\Enums\BorrowerType;
use App\Enums\SupplierType;
use App\Services\Api\CategorySupplyService;
use App\Services\Api\ContractSubService;
use App\Services\Api\InvoiceService;
use App\Services\Api\ItemService;
use App\Services\Api\OfferBuyService;
use App\Services\Api\ProjectService;
use App\Services\Api\ReceiverTypeService;
use App\Services\Api\RequestService;
use App\Services\Api\ResourceService;
use App\Services\Api\ResourceTypeService;
use App\Services\Api\RoleService;
use App\Services\Api\SubContractorService;
use App\Services\Api\SupplierService;
use App\Services\Api\SupplyService;
use App\Services\Api\UnitService;
use App\Services\Api\UserService;
use App\Services\Api\BorrowerTypeService;
use App\Services\Api\DeviceProjectService;
use App\Services\Api\DeviceService;
use App\Services\Api\DeviceMonthlyEstimateTypeService;
use App\Services\Api\DeviceTransferDirectionService;
use App\Services\Api\DevicePurchaseRequestService;
use App\Services\Api\DevicePurchaseService;
use App\Services\Api\DeviceMonthlyEstimatesService;
use App\Services\Api\DeviceEstimatesService;
use App\Services\Api\ItemSupplierTypeService;
use App\Services\Api\DeviceIssuanceService;
use App\Services\Api\ExportTypeService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Select2Controller extends BaseController
{
    protected $supplyService;
    protected $roleService;
    protected $projectService;
    protected $userService;
    protected $itemService;
    protected $categorySupplyService;
    protected $unitService;
    protected $supplierService;
    protected $subContractorService;
    protected $contractSubService;
    protected $invoiceService;
    protected $requestSupplyService;
    protected $offerBuyService;
    protected $resourceTypeService;
    protected $receiverTypeService;
    protected $resourceService;
    protected $borrowerTypeService;
    protected $deviceProjectService;
    protected $deviceService;
    protected $deviceMonthlyEstimateTypeService;
    protected $deviceTransferDirectionService;
    protected $devicePurchaseRequestService;
    protected $devicePurchaseService;
    protected $deviceMonthlyEstimatesService;
    protected $itemSupplierTypeService;
    protected $deviceIssuanceService;
    protected $exportTypeService;
    protected $deviceEstimatesService;

    public function __construct(
        SupplyService $supplyService,
        RoleService $roleService,
        ProjectService $projectService,
        UserService $userService,
        ItemService $itemService,
        CategorySupplyService $categorySupplyService,
        UnitService $unitService,
        SupplierService $supplierService,
        SubContractorService $subContractorService,
        ContractSubService $contractSubService,
        InvoiceService $invoiceService,
        RequestService $requestSupplyService,
        OfferBuyService $offerBuyService,
        ResourceTypeService $resourceTypeService,
        ReceiverTypeService $receiverTypeService,
        ResourceService $resourceService,
        BorrowerTypeService $borrowerTypeService,
        DeviceProjectService $deviceProjectService,
        DeviceService $deviceService,
        DeviceMonthlyEstimateTypeService $deviceMonthlyEstimateTypeService,
        DeviceTransferDirectionService $deviceTransferDirectionService,
        DevicePurchaseRequestService $devicePurchaseRequestService,
        DevicePurchaseService $devicePurchaseService,
        DeviceMonthlyEstimatesService $deviceMonthlyEstimatesService,
        ItemSupplierTypeService $itemSupplierTypeService,
        DeviceIssuanceService $deviceIssuanceService,
        ExportTypeService $exportTypeService,
        DeviceEstimatesService $deviceEstimatesService
    )
    {
        $this->supplyService = $supplyService;
        $this->roleService = $roleService;
        $this->projectService = $projectService;
        $this->userService = $userService;
        $this->itemService = $itemService;
        $this->categorySupplyService = $categorySupplyService;
        $this->unitService = $unitService;
        $this->supplierService = $supplierService;
        $this->subContractorService = $subContractorService;
        $this->contractSubService = $contractSubService;
        $this->invoiceService = $invoiceService;
        $this->requestSupplyService = $requestSupplyService;
        $this->offerBuyService = $offerBuyService;
        $this->resourceTypeService = $resourceTypeService;
        $this->receiverTypeService = $receiverTypeService;
        $this->resourceService = $resourceService;
        $this->borrowerTypeService = $borrowerTypeService;
        $this->deviceProjectService = $deviceProjectService;
        $this->deviceService = $deviceService;
        $this->deviceMonthlyEstimateTypeService = $deviceMonthlyEstimateTypeService;
        $this->deviceTransferDirectionService = $deviceTransferDirectionService;
        $this->devicePurchaseRequestService = $devicePurchaseRequestService;
        $this->devicePurchaseService = $devicePurchaseService;
        $this->deviceMonthlyEstimatesService = $deviceMonthlyEstimatesService;
        $this->itemSupplierTypeService = $itemSupplierTypeService;
        $this->deviceIssuanceService = $deviceIssuanceService;
        $this->exportTypeService = $exportTypeService;
        $this->deviceEstimatesService = $deviceEstimatesService;
    }

    public function getSupplies(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
            'code',
            'unit_id'
        ];

        $supplies = $this->supplyService->getListSelect2($params, $select);

        if (isset($params['search_option']) && isset($params['search_option']['with_inventory']))
        {
            $projectId = $params['search_option']['with_inventory'];
            $inventoryModel = resolve('App\Models\Inventory');
            $searchOption1 = ['end_date' => Carbon::now()->format('d/m/Y')];

            foreach ($supplies['data'] as &$supply) 
            {
                $periodInput1 = $inventoryModel->getQuantityInputInPeriod($supply['id'], $projectId, $searchOption1);
                $periodOutput1 = $inventoryModel->getQuantityOutputInPeriod($supply['id'], $projectId, $searchOption1);
                list($stock1, $diff1) = $inventoryModel->getStock($supply['id'], $projectId, $searchOption1, true);
                list($periodInput1, $periodOutput1) = $inventoryModel->adjustQuantities($periodInput1, $periodOutput1, $diff1);
                $quantityEndPeriod1 = $periodInput1 - $periodOutput1;
                $supply['system_quantity'] = $quantityEndPeriod1;
            }
        }

        return $supplies;
    }

    public function getDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'other_option');

        $select = [
            'id',
            'name',
            'code',
            'unit_id'
        ];

        $devices = $this->deviceService->getListSelect2($params, $select);

        if (isset($params['other_option']))
        {
            list($inputDevices, $projectDevices, $companyDevices, $clearanceDevices) = get_existing_device_quantities();

            $refProjectId         = (int) $params['other_option']['refProjectId']; 
            list($gains, $losses) = get_passed_around_devices($refProjectId);

            foreach ($devices['data'] as $key => $device) 
            {
                if (isset($inputDevices[$device['id']]))
                {
                    $devices['data'][$key]['existing_quantity'] = $inputDevices[$device['id']]->sum_quantity;
                }
                else
                {
                    $devices['data'][$key]['existing_quantity'] = 0;
                }

                if (isset($projectDevices[$device['id']]) && $devices['data'][$key]['existing_quantity'] > 0)
                {
                    $devices['data'][$key]['existing_quantity'] -= $projectDevices[$device['id']]->sum_quantity;
                }

                if (isset($companyDevices[$device['id']]))
                {
                    $devices['data'][$key]['existing_quantity'] += $companyDevices[$device['id']]->sum_quantity_returned;
                }

                if (isset($clearanceDevices[$device['id']]) && $devices['data'][$key]['existing_quantity'] > 0)
                {
                    $devices['data'][$key]['existing_quantity'] -= $clearanceDevices[$device['id']]->sum_quantity;
                }

                if ($refProjectId === 1)
                {
                    if (isset($gains[$device['id']]))
                    {
                        $devices['data'][$key]['existing_quantity'] += $gains[$device['id']]->sum_quantity;
                    }

                    if (isset($losses[$device['id']]) && $devices['data'][$key]['existing_quantity'] > 0)
                    {
                        $devices['data'][$key]['existing_quantity'] -= $losses[$device['id']]->sum_quantity;
                    }
                }
                else
                {
                    $devices['data'][$key]['existing_quantity'] = 0;

                    if (isset($gains[$device['id']]))
                    {
                        $devices['data'][$key]['existing_quantity'] += $gains[$device['id']]->sum_quantity;
                    }

                    if (isset($losses[$device['id']]) && $devices['data'][$key]['existing_quantity'] > 0)
                    {
                        $devices['data'][$key]['existing_quantity'] -= $losses[$device['id']]->sum_quantity;
                    }
                }
            }
        }

        return $devices;
    }

    public function getEstimateDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->deviceService->getEstimateListSelect2($params);
    }

    public function getMonthlyEstimateDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->deviceService->getMonthlyEstimateListSelect2($params);
    }

    public function getIssuanceDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->deviceService->getIssuanceListSelect2($params);
    }

    public function getPurchaseRequestDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->deviceService->getPurchaseRequestListSelect2($params);
    }

    public function getPurchaseDevices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->deviceService->getPurchaseListSelect2($params);
    }

    public function getCategorySupplies(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->categorySupplyService->getListSelect2($params, $select);
    }

    public function getRoles(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->roleService->getList($params, $select)->apiPaginate($params['per_page'] ?? null);
    }

    public function getProjects(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'hide_first_project');

        $select = [
            'id',
            'name',
        ];

        return $this->projectService->getList($params, $select)->apiPaginate($params['per_page'] ?? null);
    }

    public function getUsers(Request $request)
    {
        $params = $request->only('per_page', 'search_option');
        $select = [
            'id',
            'name',
        ];

        return $this->userService->getList($params, $select)->apiPaginate($params['per_page'] ?? null);
    }

    public function getItems(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        $results = $this->itemService->getListItems($params)->apiPaginate($params['per_page'] ?? null);

        return $results;
    }

    public function getUnits(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->unitService->getListSelect2($params, $select);
    }

    public function getSuppliers(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->supplierService->getListSelect2($params, $select);
    }

    public function getSubContractors(Request $request)
    {

        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->subContractorService->getListSelect2($params, $select);
    }

    public function getContractSub(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'contract_number',
            'contract_value',
            'contract_value_vat'
        ];
        return $this->contractSubService->getListSelect2($params, $select);
    }

    public function getInvoices(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'contract_number',
        ];

        return $this->invoiceService->getListSelect2($params, $select);
    }

    public function getRequestSupplies(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'supplies_requests.id',
            'supplies_requests.name',
            'supplies_requests.code',
        ];

        return $this->requestSupplyService->getListSelect2($params, $select);
    }
    
    public function getOfferBuys(Request $request)
    {
        $params = $request->only('per_page', 'search_option');


        $select = [
            'id',
            'name',
        ];

        return $this->offerBuyService->getListSelect2($params, $select);
    }

    public function getResourceTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->resourceTypeService->getListSelect2($params, $select);
    }

    public function getResources(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        return $this->resourceService->getListSelect2($params);
    }

    public function getReceiverTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->receiverTypeService->getListSelect2($params, $select);
    }

    public function getReceivers(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        switch ($request->receiver_type) 
        {
            case ReceiverType::SUPPLIER:
                $result = $this->supplierService->getListSelect2($params, $select, SupplierType::SUPPLIER);
                break;
            case ReceiverType::PROJECT:
                $result = $this->projectService->getListSelect2($params, $select);
                break;
            case ReceiverType::USER:
                $result = $this->userService->getListSelect2($params, $select);
                break;
        }

        return $result;
    }

    public function getBorrowerTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->borrowerTypeService->getListSelect2($params, $select);
    }

    public function getExportTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->exportTypeService->getListSelect2($params, $select);
    }

    public function getBorrowers(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        switch ($request->borrower_type) 
        {
            case BorrowerType::SUPPLIER:
                $result = $this->supplierService->getListSelect2($params, $select, SupplierType::SUPPLIER);
                break;
            case BorrowerType::CUSTOMER:
                $result = $this->supplierService->getListSelect2($params, $select, SupplierType::CUSTOMER);
                break;
            case BorrowerType::PROJECT:
                $result = $this->projectService->getListSelect2($params, $select);
                break;
            case BorrowerType::USER:
                $result = $this->userService->getListSelect2($params, $select);
                break;
        }

        return $result;
    }

    public function getDevicesToProjects(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'code',
        ];

        return $this->deviceProjectService->getListSelect2($params, $select);
    }

    public function getMonthlyEstimateDeviceTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->deviceMonthlyEstimateTypeService->getListSelect2($params, $select);
    }

    public function getDeviceTransferDirections(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->deviceTransferDirectionService->getListSelect2($params, $select);
    }

    public function getDevicePurchaseRequests(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->devicePurchaseRequestService->getListSelect2($params, $select);
    }

    public function getDevicePurchases(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->devicePurchaseService->getListSelect2($params, $select);
    }

    public function getDeviceEstimates(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->deviceEstimatesService->getListSelect2($params, $select);
    }

    public function getDeviceMonthlyEstimates(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->deviceMonthlyEstimatesService->getListSelect2($params, $select);
    }

    public function getItemSupplierTypes(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'name',
        ];

        return $this->itemSupplierTypeService->getListSelect2($params, $select);
    }

    public function getDeviceIssuances(Request $request)
    {
        $params = $request->only('per_page', 'search_option');

        $select = [
            'id',
            'code',
        ];

        return $this->deviceIssuanceService->getListSelect2($params, $select);
    }
}
