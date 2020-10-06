<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => 'auth:api', 'as' => 'api.'], function () {
    /**
     * Projects
     */
    Route::resource('projects', 'ProjectController',
        ['only' => ['index', 'store', 'destroy', 'show', 'update']]);

    /**
     * Users
     */
    Route::resource('users', 'UserController',
        ['only' => ['index', 'store', 'destroy', 'show', 'update']]);

    Route::post('/push', 'UserController@storePush');

    /**
     * Roles
     */
    Route::resource('roles', 'RoleController',
        ['only' => ['index', 'store', 'destroy', 'show', 'update']]);

    Route::group(['prefix' => 'role-tree', 'as' => 'role_tree.'], function () {
        Route::post('/', 'RoleController@rearrange')->name('rearrange');
    });

    /**
     * Work plan
     */
    Route::group(['prefix' => 'work-plan', 'as' => 'work_plan.'], function () {
        Route::post('/', 'WorkPlanController@store')->name('store');
        Route::get('/', 'WorkPlanController@index')->name('index');

        Route::get('/{id}', 'WorkPlanController@show')->name('show');

        /**
         * Task
         */
        Route::post('/{id}/task/import', 'MppTaskController@import')->name('tasks.import');
        Route::resource('/{id}/task', 'MppTaskController', ['only' => ['store', 'update', 'destroy']]);
        Route::get('/{id}/task/{taskId}', 'MppTaskController@show')->name('tasks.show');
        Route::post('/{id}/task/{taskId}/update-info', 'MppTaskController@updateInfo')->name('tasks.update_info');
        Route::post('/{id}/task/{taskId}/resource', 'WorkPlanController@addResourceForTask')->name('tasks.resources');

        Route::delete('/{id}/task/{taskId}/resource/{resourceId}', 'MppTaskController@deleteResource')->name('tasks.resources.destroy');
        /**
         * Link
         */
        Route::resource('/{id}/link', 'MppLinkController', ['only' => ['store', 'update', 'destroy']]);
    });
    /**
     * Plan
     */
    Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
        Route::get('supplies', 'PlanController@planSupplies')->name('supplies')->middleware('permission:plans.supplies.read');
        Route::post('supplies', 'PlanController@create')->name('supplies.store')->middleware('permission:plans.supplies.create');
        Route::delete('supplies/{id}', 'PlanController@deletePlanSupplies')->name('supplies.destroy')->middleware('permission:plans.supplies.delete');
        Route::get('supplies/{id}', 'PlanController@showPlanSupplies')->name('supplies.show')->middleware('permission:plans.supplies.read');
        Route::put('supplies/{id}', 'PlanController@update')->name('supplies.update')->middleware('permission:plans.supplies.update');
        Route::get('supplies/{id}/tracking', 'PlanController@tracking')->name('supplies.tracking')->middleware('permission:plans.supplies.read');
    });

    /**
     * Items
     */
    Route::group(['as' => 'items.'], function () {
        Route::get('items', 'ItemController@index')->name('index')->middleware('permission:items.read');
        Route::post('items/store', 'ItemController@store')->name('store')->middleware('permission:items.create');
        Route::get('items/{id}', 'ItemController@show')->name('show')->middleware('permission:items.read');
        Route::put('items/{id}', 'ItemController@update')->name('update')->middleware('permission:items.update');
        Route::delete('items/{id}', 'ItemController@destroy')->name('destroy')->middleware('permission:items.delete');
        Route::get('items/{id}/tracking', 'ItemController@tracking')->name('tracking')->middleware('permission:items.create');
    });

    Route::group(['as' => 'suppliers.'], function () {
        Route::get('suppliers', 'SupplierController@index')->name('index')->middleware('permission:suppliers.read');
        Route::post('suppliers/store', 'SupplierController@store')->name('store')->middleware('permission:suppliers.create');
        Route::get('suppliers/{id}', 'SupplierController@show')->name('show')->middleware('permission:suppliers.read');
        Route::put('suppliers/{id}', 'SupplierController@update')->name('update')->middleware('permission:suppliers.update');
        Route::delete('suppliers/{id}', 'SupplierController@destroy')->name('destroy')->middleware('permission:suppliers.delete');
        Route::get('suppliers/{id}/tracking', 'SupplierController@tracking')->name('tracking')->middleware('permission:suppliers.create');
    });

    // not done
    Route::group(['prefix' => 'requests', 'as' => 'requests.'], function () {
        Route::get('supplies', 'RequestController@requestSupplies')->name('supplies');
        Route::post('supplies/store', 'RequestController@create')->name('supplies.store');
        Route::get('supplies/{id}', 'RequestController@showRequestSupplies')->name('supplies.show');
        Route::put('supplies/{id}/edit', 'RequestController@update')->name('supplies.update');
        Route::delete('supplies/{id}', 'RequestController@deleteRequestSupplies')->name('supplies.destroy');
        Route::get('supplies/{id}/tracking', 'RequestController@tracking')->name('supplies.tracking');

    });

    /**
     * Supplies
     */
    Route::group(['as' => 'supplies.'], function () {
        Route::get('supplies', 'SuppliesController@index')->name('index')->middleware('permission:supplies.read');
        Route::post('supplies/store', 'SuppliesController@store')->name('store')->middleware('permission:supplies.create');
        Route::get('supplies/{id}', 'SuppliesController@show')->name('show')->middleware('permission:supplies.read');
        Route::put('supplies/{id}', 'SuppliesController@update')->name('update')->middleware('permission:supplies.update');
        Route::delete('supplies/{id}', 'SuppliesController@destroy')->name('destroy')->middleware('permission:supplies.delete');
        Route::get('supplies-by-request/{requestSupplyId}/{projectId}', 'SuppliesController@getSuppliesByRequest')->name('supplies-by-request');
        Route::get('supplies-by-offer/{offerBuyId}/{projectId}', 'SuppliesController@getSuppliesByOffer')->name('supplies-by-offer');
        Route::get('exportable-quantity/{itemId}/{supplyId}', 'SuppliesController@getExportableQuantity')->name('supplies.exportable-quantity');
    });

    /**
     * Category Supplies
     */
    Route::group(['as' => 'category_supplies.'], function () {
        Route::get('category-supplies', 'CategorySuppliesController@index')->name('index')->middleware('permission:category_supplies.read');
        Route::post('category-supplies/store', 'CategorySuppliesController@store')->name('store')->middleware('permission:category_supplies.create');
        Route::get('category-supplies/{id}', 'CategorySuppliesController@show')->name('show')->middleware('permission:category_supplies.read');
        Route::put('category-supplies/{id}', 'CategorySuppliesController@update')->name('update')->middleware('permission:category_supplies.update');
        Route::delete('category-supplies/{id}', 'CategorySuppliesController@destroy')->name('destroy')->middleware('permission:category_supplies.delete');
    });

    /**
     * Invoice
     */
    Route::group(['as' => 'invoices.'], function () {
        Route::get('invoices', 'InvoiceController@index')->name('index')->middleware('permission:invoices.read');
        Route::post('invoices', 'InvoiceController@create')->name('store')->middleware('permission:invoices.create');
        Route::delete('invoices/{id}', 'InvoiceController@deleteInvoice')->name('destroy')->middleware('permission:invoices.delete');
        Route::get('invoices/{id}', 'InvoiceController@showInvoice')->name('show')->middleware('permission:invoices.read');
        Route::put('invoices/{id}', 'InvoiceController@update')->name('update')->middleware('permission:invoices.update');
        Route::get('invoices/{id}/tracking', 'InvoiceController@tracking')->name('tracking');

        /**
         * Ticket Import
         */
        Route::post('invoices/{id}/ticket-import/create', 'TicketImportController@store')->name('ticket-import.store');
    });


    /**
     * Offer Buy
     */
    Route::group(['as' => 'offer-buys.'], function () {
        Route::get('offer-buys', 'OfferBuyController@index')->name('index')->middleware('permission:offer_buys.read');
        Route::post('offer-buys', 'OfferBuyController@create')->name('store')->middleware('permission:offer_buys.create');
        Route::delete('offer-buys/{id}', 'OfferBuyController@deleteOfferBuy')->name('destroy')->middleware('permission:offer_buys.delete');
        Route::get('offer-buys/{id}', 'OfferBuyController@showOfferBuy')->name('show')->middleware('permission:offer_buys.read');
        Route::put('offer-buys/{id}', 'OfferBuyController@update')->name('update')->middleware('permission:offer_buys.update');
        Route::get('offer-buys/{id}/supplies', 'OfferBuyController@getSuppliesByOfferBuyId')->name('supplies');
        Route::get('offer-buys/{id}/tracking', 'OfferBuyController@tracking')->name('tracking');
    });

    /**
     * Sub-contractor
     */
    Route::group(['as' => 'sub-contractors.'], function () {
        Route::get('sub-contractors', 'SubContractorController@index')->name('index')->middleware('permission:sub_contractors.read');
        Route::post('sub-contractors', 'SubContractorController@store')->name('store')->middleware('permission:sub_contractors.create');
        Route::delete('sub-contractors/{id}', 'SubContractorController@destroy')->name('destroy')->middleware('permission:sub_contractors.delete');
        Route::get('sub-contractors/{id}', 'SubContractorController@show')->name('show')->middleware('permission:sub_contractors.read');
        Route::put('sub-contractors/{id}', 'SubContractorController@update')->name('update')->middleware('permission:sub_contractors.update');
        Route::get('sub-contractors/{id}/tracking', 'SubContractorController@tracking')->name('tracking');
    });

    /**
     * Censor Sub-contractor
     */
    Route::group(['as' => 'censor-sub.'], function () {
        Route::get('censor-sub', 'CensorSubController@index')->name('index')->middleware('permission:censor_sub.read');
        Route::post('censor-sub', 'CensorSubController@store')->name('store')->middleware('permission:censor_sub.create');
        Route::delete('censor-sub/{id}', 'CensorSubController@destroy')->name('destroy')->middleware('permission:censor_sub.delete');
        Route::get('censor-sub/{id}', 'CensorSubController@show')->name('show')->middleware('permission:censor_sub.read');
        Route::put('censor-sub/{id}', 'CensorSubController@update')->name('update')->middleware('permission:censor_sub.update');
        Route::get('censor-sub/{id}/tracking', 'CensorSubController@tracking')->name('tracking');
    });

    /**
     * Censor Sub-contractor
     */
    Route::group(['as' => 'contract-sub.'], function () {
        Route::get('contract-sub', 'ContractSubController@index')->name('index')->middleware('permission:contract_sub.read');
        Route::post('contract-sub', 'ContractSubController@store')->name('store')->middleware('permission:contract_sub.create');
        Route::delete('contract-sub/{id}', 'ContractSubController@destroy')->name('destroy')->middleware('permission:contract_sub.delete');
        Route::get('contract-sub/{id}', 'ContractSubController@show')->name('show')->middleware('permission:contract_sub.read');
        Route::put('contract-sub/{id}', 'ContractSubController@update')->name('update')->middleware('permission:contract_sub.update');
        Route::get('contract-sub/{id}/tracking', 'ContractSubController@tracking')->name('tracking');
    });

    /**
     * Payment-Order
     */
    Route::group(['as' => 'payment-order.'], function () {
        Route::get('payment-order', 'PaymentOrderController@index')->name('index')->middleware('permission:payment_order.read');
        Route::post('payment-order', 'PaymentOrderController@store')->name('store')->middleware('permission:payment_order.create');
        Route::delete('payment-order/{id}', 'PaymentOrderController@destroy')->name('destroy')->middleware('permission:payment_order.delete');
        Route::get('payment-order/{id}', 'PaymentOrderController@show')->name('show')->middleware('permission:payment_order.read');
        Route::put('payment-order/{id}', 'PaymentOrderController@update')->name('update')->middleware('permission:payment_order.update');
        Route::get('payment-order/{id}/tracking', 'PaymentOrderController@tracking')->name('tracking');
    });

    Route::group(['prefix' => 'inventories', 'as' => 'inventories.'], function () {
        /**
         * Receipt Inputs
         */
        Route::group(['as' => 'receipt-inputs.'], function () {
            Route::get('receipt-inputs', 'ReceiptInputController@index')->name('index')->middleware('permission:receipt_inputs.read');
            Route::post('receipt-inputs', 'ReceiptInputController@create')->name('store')->middleware('permission:receipt_inputs.create');
            Route::delete('receipt-inputs/{id}', 'ReceiptInputController@deleteReceiptInput')->name('destroy')->middleware('permission:receipt_inputs.delete');
            Route::get('receipt-inputs/{id}', 'ReceiptInputController@showReceiptInput')->name('show')->middleware('permission:receipt_inputs.read');
            Route::put('receipt-inputs/{id}', 'ReceiptInputController@update')->name('update')->middleware('permission:receipt_inputs.update');
            Route::get('receipt-inputs/{id}/tracking', 'ReceiptInputController@tracking')->name('tracking');
        });

        /**
         * Receipt Outputs
         */
        Route::group(['as' => 'receipt-outputs.'], function () {
            Route::get('receipt-outputs', 'ReceiptOutputController@index')->name('index')->middleware('permission:receipt_outputs.read');
            Route::post('receipt-outputs', 'ReceiptOutputController@create')->name('store')->middleware('permission:receipt_outputs.create');
            Route::delete('receipt-outputs/{id}', 'ReceiptOutputController@deleteReceiptOutput')->name('destroy')->middleware('permission:receipt_outputs.delete');
            Route::get('receipt-outputs/{id}', 'ReceiptOutputController@showReceiptOutput')->name('show')->middleware('permission:receipt_outputs.read');
            Route::put('receipt-outputs/{id}', 'ReceiptOutputController@update')->name('update')->middleware('permission:receipt_outputs.update');
            Route::get('receipt-outputs/{supplyIds}/{projectId}/{exportType}/information', 'ReceiptOutputController@getSuppliesInformation')->name('supplies-information');
            Route::get('receipt-outputs/{id}/tracking', 'ReceiptOutputController@tracking')->name('tracking');

        });

        /**
         * Receipt Transfers
         */
        Route::group(['as' => 'receipt-transfers.'], function () {
            Route::get('receipt-transfers', 'ReceiptTransferController@index')->name('index')->middleware('permission:receipt_transfers.read');
            Route::post('receipt-transfers', 'ReceiptTransferController@create')->name('store')->middleware('permission:receipt_transfers.create');
            Route::delete('receipt-transfers/{id}', 'ReceiptTransferController@deleteReceiptTransfer')->name('destroy')->middleware('permission:receipt_transfers.delete');
            Route::get('receipt-transfers/{id}', 'ReceiptTransferController@showReceiptTransfer')->name('show')->middleware('permission:receipt_transfers.read');
            Route::put('receipt-transfers/{id}', 'ReceiptTransferController@update')->name('update')->middleware('permission:receipt_transfers.update');
            Route::get('receipt-transfers/{id}/tracking', 'ReceiptTransferController@tracking')->name('tracking');
        });

        Route::group(['as' => 'delivery_on_demand.'], function () {
            Route::get('delivery-on-demand', 'InventoriesController@getSuppliesForDeliveryOnDemand')->name('index')->middleware('permission:delivery_on_demands.read');
        });

        Route::get('/detail', 'InventoriesController@detail')->name('detail.index')->middleware('permission:inventories_detail.read');

        Route::group(['as' => 'stocktaking.', 'prefix' => 'stocktaking'], function () {
            Route::get('/', 'StocktakingController@getListStocktaking')->name('index')->middleware('permission:stocktaking.read');
            Route::post('/', 'StocktakingController@storeStocktaking')->name('store')->middleware('permission:stocktaking.create');
            Route::get('/{id}', 'StocktakingController@showStocktaking')->name('show')->middleware('permission:stocktaking.read');
            Route::get('/{id}/tracking', 'StocktakingController@trackingStocktaking')->name('tracking')->middleware('permission:stocktaking.read');
            Route::put('/{id}', 'StocktakingController@updateStocktaking')->name('update')->middleware('permission:stocktaking.update');
            Route::delete('/{id}', 'StocktakingController@deleteStocktaking')->name('destroy')->middleware('permission:stocktaking.delete');
        });

        Route::get('/quantity/{supplyId}/{inputId}/{outputId}', 'InventoriesController@getQuantity')->name('quantity');
        Route::get('/quantity-in-stock/{supplyId}/{projectId}', 'InventoriesController@getQuantityInStock')->name('quantity_in_stock');
    });

    Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
        Route::get('devices', 'DevicesController@index')->name('index')->middleware('permission:devices.read');
        Route::post('devices/store', 'DevicesController@store')->name('store')->middleware('permission:devices.create');
        Route::get('devices/{id}', 'DevicesController@show')->name('show')->middleware('permission:devices.read');
        Route::put('devices/{id}', 'DevicesController@update')->name('update')->middleware('permission:devices.update');
        Route::delete('devices/{id}', 'DevicesController@destroy')->name('destroy')->middleware('permission:devices.delete');
        Route::get('/existing_quantity/{projectId}/{deviceId}', 'DevicesController@getExistingQuantity')->name('existing_quantity')->middleware('permission:devices.read');

        Route::group(['as' => 'project.', 'prefix' => 'project'], function () {
            Route::get('/', 'DeviceToProjectController@index')->name('index')->middleware('permission:device_project.read');
            Route::post('/', 'DeviceToProjectController@store')->name('store')->middleware('permission:device_project.create');
            Route::get('/{id}', 'DeviceToProjectController@show')->name('show')->middleware('permission:device_project.read');
            Route::put('/{id}', 'DeviceToProjectController@update')->name('update')->middleware('permission:device_project.update');
            Route::delete('/{id}', 'DeviceToProjectController@destroy')->name('destroy')->middleware('permission:device_project.delete');
            Route::get('/{id}/tracking', 'DeviceToProjectController@tracking')->name('tracking');
            Route::get('/{id}/devices', 'DeviceToProjectController@getDevicesByDevicesToProjectId')->name('devices');
        });

        Route::group(['as' => 'company.', 'prefix' => 'company'], function () {
            Route::get('/', 'DeviceReturnToCompanyController@index')->name('index')->middleware('permission:device_company.read');
            Route::post('/', 'DeviceReturnToCompanyController@store')->name('store')->middleware('permission:device_company.create');
            Route::get('/{id}', 'DeviceReturnToCompanyController@show')->name('show')->middleware('permission:device_company.read');
            Route::put('/{id}', 'DeviceReturnToCompanyController@update')->name('update')->middleware('permission:device_company.update');
            Route::delete('/{id}', 'DeviceReturnToCompanyController@destroy')->name('destroy')->middleware('permission:device_company.delete');
            Route::get('/{id}/tracking', 'DeviceReturnToCompanyController@tracking')->name('tracking');
        });

        Route::group(['as' => 'inventory.', 'prefix' => 'inventory'], function () {
            Route::get('/', 'DeviceInventoryController@index')->name('index')->middleware('permission:device_inventory.read');
            Route::post('/', 'DeviceInventoryController@store')->name('store')->middleware('permission:device_inventory.create');
            Route::get('/{id}', 'DeviceInventoryController@show')->name('show')->middleware('permission:device_inventory.read');
            Route::put('/{id}', 'DeviceInventoryController@update')->name('update')->middleware('permission:device_inventory.update');
            Route::delete('/{id}', 'DeviceInventoryController@destroy')->name('destroy')->middleware('permission:device_inventory.delete');
            Route::get('/{id}/tracking', 'DeviceInventoryController@tracking')->name('tracking');
        });

        Route::group(['as' => 'maintainence.', 'prefix' => 'maintainence'], function () {
            Route::get('/', 'DeviceMaintainenceController@index')->name('index')->middleware('permission:device_maintainence.read');
            Route::post('/', 'DeviceMaintainenceController@store')->name('store')->middleware('permission:device_maintainence.create');
            Route::get('/{id}', 'DeviceMaintainenceController@show')->name('show')->middleware('permission:device_maintainence.read');
            Route::put('/{id}', 'DeviceMaintainenceController@update')->name('update')->middleware('permission:device_maintainence.update');
            Route::delete('/{id}', 'DeviceMaintainenceController@destroy')->name('destroy')->middleware('permission:device_maintainence.delete');
            Route::get('/{id}/tracking', 'DeviceMaintainenceController@tracking')->name('tracking');
        });

        Route::group(['as' => 'clearance.', 'prefix' => 'clearance'], function () {
            Route::get('/', 'DeviceClearanceController@index')->name('index')->middleware('permission:device_clearance.read');
            Route::post('/', 'DeviceClearanceController@store')->name('store')->middleware('permission:device_clearance.create');
            Route::get('/{id}', 'DeviceClearanceController@show')->name('show')->middleware('permission:device_clearance.read');
            Route::put('/{id}', 'DeviceClearanceController@update')->name('update')->middleware('permission:device_clearance.update');
            Route::delete('/{id}', 'DeviceClearanceController@destroy')->name('destroy')->middleware('permission:device_clearance.delete');
            Route::get('/{id}/tracking', 'DeviceClearanceController@tracking')->name('tracking');
        });

        Route::group(['as' => 'rental.', 'prefix' => 'rental'], function () {
            Route::get('/', 'DeviceRentalController@index')->name('index')->middleware('permission:device_rental.read');
            Route::post('/', 'DeviceRentalController@store')->name('store')->middleware('permission:device_rental.create');
            Route::get('/{id}', 'DeviceRentalController@show')->name('show')->middleware('permission:device_rental.read');
            Route::put('/{id}', 'DeviceRentalController@update')->name('update')->middleware('permission:device_rental.update');
            Route::delete('/{id}', 'DeviceRentalController@destroy')->name('destroy')->middleware('permission:device_rental.delete');
            Route::get('/{id}/tracking', 'DeviceRentalController@tracking')->name('tracking');
        });

        Route::group(['as' => 'bill.', 'prefix' => 'bill'], function () {
            Route::get('/', 'DeviceBillController@index')->name('index')->middleware('permission:device_bill.read');
        });

        Route::group(['as' => 'stats.', 'prefix' => 'stats'], function () {
            Route::get('/', 'DeviceStatsController@index')->name('index')->middleware('permission:device_stats.read');
        });

        Route::group(['as' => 'input.', 'prefix' => 'input'], function () {
            Route::get('/', 'DeviceInputController@index')->name('index')->middleware('permission:device_input.read');
            Route::post('/', 'DeviceInputController@store')->name('store')->middleware('permission:device_input.create');
            Route::get('/{id}', 'DeviceInputController@show')->name('show')->middleware('permission:device_input.read');
            Route::put('/{id}', 'DeviceInputController@update')->name('update')->middleware('permission:device_input.update');
            Route::delete('/{id}', 'DeviceInputController@destroy')->name('destroy')->middleware('permission:device_input.delete');
            Route::get('/{id}/tracking', 'DeviceInputController@tracking')->name('tracking');

            Route::get('/{deviceId}/{projectId}', 'DeviceInputController@getExisting')->name('existing')->middleware('permission:device_input.read');
        });

        Route::group(['as' => 'estimates.', 'prefix' => 'estimates'], function () {
            Route::get('/', 'DeviceEstimatesController@index')->name('index')->middleware('permission:device_estimates.read');
            Route::post('/', 'DeviceEstimatesController@store')->name('store')->middleware('permission:device_estimates.create');
            Route::get('/{id}', 'DeviceEstimatesController@show')->name('show')->middleware('permission:device_estimates.read');
            Route::put('/{id}', 'DeviceEstimatesController@update')->name('update')->middleware('permission:device_estimates.update');
            Route::delete('/{id}', 'DeviceEstimatesController@destroy')->name('destroy')->middleware('permission:device_estimates.delete');
            Route::get('/{id}/tracking', 'DeviceEstimatesController@tracking')->name('tracking');
        });

        Route::group(['as' => 'monthly_estimates.', 'prefix' => 'monthly_estimates'], function () {
            Route::get('/', 'DeviceMonthlyEstimatesController@index')->name('index')->middleware('permission:device_monthly_estimates.read');
            Route::post('/', 'DeviceMonthlyEstimatesController@store')->name('store')->middleware('permission:device_monthly_estimates.create');
            Route::get('/{id}', 'DeviceMonthlyEstimatesController@show')->name('show')->middleware('permission:device_monthly_estimates.read');
            Route::put('/{id}', 'DeviceMonthlyEstimatesController@update')->name('update')->middleware('permission:device_monthly_estimates.update');
            Route::delete('/{id}', 'DeviceMonthlyEstimatesController@destroy')->name('destroy')->middleware('permission:device_monthly_estimates.delete');
            Route::get('/{id}/devices', 'DeviceMonthlyEstimatesController@getDevicesByDevicesMonthlyEstimatesId')->name('devices');
        });
        Route::get('monthly-estimates/{id}/tracking', 'DeviceMonthlyEstimatesController@tracking')->name('monthly-estimates.tracking');

        Route::group(['as' => 'issuance.', 'prefix' => 'issuance'], function () {
            Route::get('/', 'DeviceIssuanceController@index')->name('index')->middleware('permission:device_issuance.read');
            Route::post('/', 'DeviceIssuanceController@store')->name('store')->middleware('permission:device_issuance.create');
            Route::get('/{id}', 'DeviceIssuanceController@show')->name('show')->middleware('permission:device_issuance.read');
            Route::put('/{id}', 'DeviceIssuanceController@update')->name('update')->middleware('permission:device_issuance.update');
            Route::delete('/{id}', 'DeviceIssuanceController@destroy')->name('destroy')->middleware('permission:device_issuance.delete');
            Route::get('/{id}/devices', 'DeviceIssuanceController@getDevicesByDevicesIssuanceId')->name('devices');
            Route::get('/{id}/tracking', 'DeviceIssuanceController@tracking')->name('tracking');
        });

        Route::group(['as' => 'transfer.', 'prefix' => 'transfer'], function () {
            Route::get('/', 'DeviceTransferController@index')->name('index')->middleware('permission:device_transfer.read');
            Route::post('/', 'DeviceTransferController@store')->name('store')->middleware('permission:device_transfer.create');
            Route::get('/{id}', 'DeviceTransferController@show')->name('show')->middleware('permission:device_transfer.read');
            Route::put('/{id}', 'DeviceTransferController@update')->name('update')->middleware('permission:device_transfer.update');
            Route::delete('/{id}', 'DeviceTransferController@destroy')->name('destroy')->middleware('permission:device_transfer.delete');
            Route::get('/{id}/tracking', 'DeviceTransferController@tracking')->name('tracking');
        });

        Route::group(['as' => 'purchase_request.', 'prefix' => 'purchase_request'], function () {
            Route::get('/', 'DevicePurchaseRequestController@index')->name('index')->middleware('permission:device_purchase_request.read');
            Route::post('/', 'DevicePurchaseRequestController@store')->name('store')->middleware('permission:device_purchase_request.create');
            Route::get('/{id}', 'DevicePurchaseRequestController@show')->name('show')->middleware('permission:device_purchase_request.read');
            Route::put('/{id}', 'DevicePurchaseRequestController@update')->name('update')->middleware('permission:device_purchase_request.update');
            Route::delete('/{id}', 'DevicePurchaseRequestController@destroy')->name('destroy')->middleware('permission:device_purchase_request.delete');
            Route::get('/{id}/devices', 'DevicePurchaseRequestController@getDevicesByDevicesPurchaseRequestId')->name('devices');
        });
        Route::get('purchase-request/{id}/tracking', 'DevicePurchaseRequestController@tracking')->name('purchase-request.tracking');

        Route::group(['as' => 'purchase.', 'prefix' => 'purchase'], function () {
            Route::get('/', 'DevicePurchaseController@index')->name('index')->middleware('permission:device_purchase.read');
            Route::post('/', 'DevicePurchaseController@store')->name('store')->middleware('permission:device_purchase.create');
            Route::get('/{id}', 'DevicePurchaseController@show')->name('show')->middleware('permission:device_purchase.read');
            Route::put('/{id}', 'DevicePurchaseController@update')->name('update')->middleware('permission:device_purchase.update');
            Route::delete('/{id}', 'DevicePurchaseController@destroy')->name('destroy')->middleware('permission:device_purchase.delete');
            Route::get('/{id}/devices', 'DevicePurchaseController@getDevicesByDevicesPurchaseId')->name('devices');
            Route::get('/{id}/tracking', 'DevicePurchaseController@tracking')->name('tracking');
        });

        Route::group(['as' => 'requested_purchase.', 'prefix' => 'requested_purchase'], function () {
            Route::get('/', 'DeviceRequestedPurchaseController@index')->name('index')->middleware('permission:device_requested_purchase.read');
        });

        Route::group(['as' => 'round_robin.', 'prefix' => 'round_robin'], function () {
            Route::get('/', 'DeviceRoundRobinController@index')->name('index')->middleware('permission:device_round_robin.read');
            Route::post('/', 'DeviceRoundRobinController@store')->name('store')->middleware('permission:device_round_robin.create');
            Route::get('/{id}', 'DeviceRoundRobinController@show')->name('show')->middleware('permission:device_round_robin.read');
            Route::put('/{id}', 'DeviceRoundRobinController@update')->name('update')->middleware('permission:device_round_robin.update');
            Route::delete('/{id}', 'DeviceRoundRobinController@destroy')->name('destroy')->middleware('permission:device_round_robin.delete');
        });

        Route::group(['as' => 'contract.', 'prefix' => 'contract'], function () {
            Route::get('/', 'DeviceContractController@index')->name('index')->middleware('permission:device_contract.read');
            Route::post('/', 'DeviceContractController@store')->name('store')->middleware('permission:device_contract.create');
            Route::get('/{id}', 'DeviceContractController@show')->name('show')->middleware('permission:device_contract.read');
            Route::put('/{id}', 'DeviceContractController@update')->name('update')->middleware('permission:device_contract.update');
            Route::delete('/{id}', 'DeviceContractController@destroy')->name('destroy')->middleware('permission:device_contract.delete');
            Route::get('/{id}/tracking', 'DeviceContractController@tracking')->name('tracking');
        });
    });

    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
        Route::get('/', 'TaskController@getMyTasks')->name('index')->middleware('permission:tasks.read');
        Route::get('/{id}', 'TaskController@show')->name('show')->middleware('permission:tasks.read');
        Route::post('/{id}/approve', 'TaskController@approve')->name('approve')->middleware('permission:tasks.approve');
        Route::post('/{id}/forward', 'TaskController@forward')->name('forward');
        Route::post('/{id}/return', 'TaskController@return')->name('return');
        Route::post('/{id}/cancel', 'TaskController@cancel')->name('cancel');
        //Route::get('/filter', 'TaskController@getTaskSearch')->name('filter');
    });

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/', 'DashboardController@getCountDashboard')->name('index');
    });
    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('/', 'ReportController@getCountReport')->name('index');
    });
    /**
     * Notifications
     */
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', 'NotificationController@index')->name('index');
        Route::get('/update', 'NotificationController@update')->name('update');
    });

    /**
     * Resources
     */
    Route::group(['as' => 'resources.'], function () {
        Route::get('resources', 'ResourceController@index')->name('index')->middleware('permission:resources.read');
        Route::post('resources/store', 'ResourceController@store')->name('store')->middleware('permission:resources.create');
        Route::get('resources/{id}', 'ResourceController@show')->name('show')->middleware('permission:resources.read');
        Route::put('resources/{id}', 'ResourceController@update')->name('update')->middleware('permission:resources.update');
        Route::delete('resources/{id}', 'ResourceController@destroy')->name('destroy')->middleware('permission:resources.delete');
    });

    /**
     * Resource Types
     */
    Route::group(['as' => 'resource_types.'], function () {
        Route::get('resource_types', 'ResourceTypeController@index')->name('index')->middleware('permission:resource_types.read');
        Route::post('resource_types/store', 'ResourceTypeController@store')->name('store')->middleware('permission:resource_types.create');
        Route::get('resource_types/{id}', 'ResourceTypeController@show')->name('show')->middleware('permission:resource_types.read');
        Route::put('resource_types/{id}', 'ResourceTypeController@update')->name('update')->middleware('permission:resource_types.update');
        Route::delete('resource_types/{id}', 'ResourceTypeController@destroy')->name('destroy')->middleware('permission:resource_types.delete');
    });

    /**
     * Select2 data
     */
    Route::group(['prefix' => 'select2', 'as' => 'select2.'], function () {
        Route::get('supplies', 'Select2Controller@getSupplies')->name('supplies');
        Route::get('devices', 'Select2Controller@getDevices')->name('devices');
        Route::get('estimate-devices', 'Select2Controller@getEstimateDevices')->name('estimate_devices');
        Route::get('monthly-estimate-devices', 'Select2Controller@getMonthlyEstimateDevices')->name('monthly_estimate_devices');
        Route::get('issuance-devices', 'Select2Controller@getIssuanceDevices')->name('issuance_devices');
        Route::get('purchase-request-devices', 'Select2Controller@getPurchaseRequestDevices')->name('purchase_request_devices');
        Route::get('purchase-devices', 'Select2Controller@getPurchaseDevices')->name('purchase_devices');
        Route::get('category-supplies', 'Select2Controller@getCategorySupplies')->name('category_supplies');
        Route::get('roles', 'Select2Controller@getRoles')->name('roles');
        Route::get('projects', 'Select2Controller@getProjects')->name('projects');
        Route::get('users', 'Select2Controller@getUsers')->name('users');
        Route::get('items', 'Select2Controller@getItems')->name('items');
        Route::get('units', 'Select2Controller@getUnits')->name('units');
        Route::get('suppliers', 'Select2Controller@getSuppliers')->name('suppliers');
        Route::get('sub-contractors', 'Select2Controller@getSubContractors')->name('sub_contractors');
        Route::get('contract-sub', 'Select2Controller@getContractSub')->name('contract_sub');
        Route::get('invoices', 'Select2Controller@getInvoices')->name('invoices');
        Route::get('request-supplies', 'Select2Controller@getRequestSupplies')->name('request_supplies');
        Route::get('offer-buys', 'Select2Controller@getOfferBuys')->name('offer_buys');
        Route::get('resource-types', 'Select2Controller@getResourceTypes')->name('resource_types');
        Route::get('resources', 'Select2Controller@getResources')->name('resources');
        Route::get('receiver-types', 'Select2Controller@getReceiverTypes')->name('receiver_types');
        Route::get('receivers', 'Select2Controller@getReceivers')->name('receivers');
        Route::get('borrower-types', 'Select2Controller@getBorrowerTypes')->name('borrower_types');
        Route::get('export-types', 'Select2Controller@getExportTypes')->name('export_types');
        Route::get('borrowers', 'Select2Controller@getBorrowers')->name('borrowers');
        Route::get('devices-to-projects', 'Select2Controller@getDevicesToProjects')->name('devices_to_projects');
        Route::get('monthly-estimate-device-types', 'Select2Controller@getMonthlyEstimateDeviceTypes')->name('monthly_estimate_device_types');
        Route::get('transfer-directions', 'Select2Controller@getDeviceTransferDirections')->name('transfer_directions');
        Route::get('device-purchase-requests', 'Select2Controller@getDevicePurchaseRequests')->name('device_purchase_requests');
        Route::get('device-purchases', 'Select2Controller@getDevicePurchases')->name('device_purchases');
        Route::get('device-estimates', 'Select2Controller@getDeviceEstimates')->name('devices_estimates');
        Route::get('device-monthly-estimates', 'Select2Controller@getDeviceMonthlyEstimates')->name('devices_monthly_estimates');
        Route::get('item-supplier-types', 'Select2Controller@getItemSupplierTypes')->name('item_supplier_types');
        Route::get('device-issuances', 'Select2Controller@getDeviceIssuances')->name('device_issuances');
    });

    Route::group(['prefix' => 'autosuggest', 'as' => 'autosuggest.'], function () {
        Route::get('users/{name}', 'UserController@autosuggest')->name('users');
    });

    Route::group(['prefix' => 'export'], function () {
        Route::post('request-subcontract-xls', 'ExportController@requestSubcontractXls')->name('export.request-subcontract.xls');
        Route::post('request-subcontract-pdf', 'ExportController@requestSubcontractPdf')->name('export.request-subcontract.pdf');

        Route::post('request-censor-xls', 'ExportController@requestCensorXls')->name('export.request-censor.xls');
        Route::post('request-censor-pdf', 'ExportController@requestCensorPdf')->name('export.request-censor.pdf');

        Route::post('request-contractsub-xls', 'ExportController@requestContractsubXls')->name('export.request-contractsub.xls');
        Route::post('request-contractsub-pdf', 'ExportController@requestContractsubPdf')->name('export.request-contractsub.pdf');

        Route::post('request-paymentorder-xls', 'ExportController@requestPaymentorderXls')->name('export.request-paymentorder.xls');
        Route::post('request-paymentorder-pdf', 'ExportController@requestPaymentorderPdf')->name('export.request-paymentorder.pdf');

        Route::post('plan-pdf', 'ExportController@planPdf')->name('export.plan.pdf');
        Route::post('plan-xls', 'ExportController@planXls')->name('export.plan.xls');
        Route::post('items-pdf', 'ExportController@itemsPdf')->name('export.items.pdf');
        Route::post('items-xls', 'ExportController@itemsXls')->name('export.items.xls');
        Route::post('offer-buys-pdf', 'ExportController@offerBuyPdf')->name('export.offer-buys.pdf');
        Route::post('offer-buys-xls', 'ExportController@offerBuyXls')->name('export.offer-buys.xls');
        Route::post('request-supplies-pdf', 'ExportController@requestSuppliesPdf')->name('export.request-supplies.pdf');
        Route::post('request-supplies-xls', 'ExportController@requestSuppliesXls')->name('export.request-supplies.xls');
        Route::post('invoices-pdf', 'ExportController@invoicePdf')->name('export.invoices.pdf');
        Route::post('invoices-xls', 'ExportController@invoiceXls')->name('export.invoices.xls');
        Route::post('receipt-input-pdf', 'ExportController@receiptInputPdf')->name('export.receipt-input.pdf');
        Route::post('receipt-input-xls', 'ExportController@receiptInputXls')->name('export.receipt-input.xls');
        Route::post('receipt-output-pdf', 'ExportController@receiptOutputPdf')->name('export.receipt-output.pdf');
        Route::post('receipt-output-xls', 'ExportController@receiptOutputXls')->name('export.receipt-output.xls');

        Route::post('receipt-transfer-pdf', 'ExportController@receiptTransferPdf')->name('export.receipt-transfer.pdf');
        Route::post('receipt-transfer-xls', 'ExportController@receiptTransferXls')->name('export.receipt-transfer.xls');

        Route::post('stocktaking-pdf', 'ExportController@receiptStocktakingPdf')->name('export.stocktaking.pdf');
        Route::post('stocktaking-xls', 'ExportController@receiptStocktakingXls')->name('export.stocktaking.xls');

        Route::post('device-input-pdf', 'ExportController@deviceInputPdf')->name('export.device-input.pdf');
        Route::post('device-input-xls', 'ExportController@deviceInputXls')->name('export.device-input.xls');

        Route::post('device-estimate-pdf', 'ExportController@deviceEstimatePdf')->name('export.device-estimate.pdf');
        Route::post('device-estimate-xls', 'ExportController@deviceEstimateXls')->name('export.device-estimate.xls');
        Route::post('device-monthly-estimate-pdf', 'ExportController@deviceMonthlyEstimatePdf')->name('export.device-monthly-estimate.pdf');
        Route::post('device-monthly-estimate-xls', 'ExportController@deviceMonthlyEstimateXls')->name('export.device-monthly-estimate.xls');
        Route::post('device-issuance-pdf', 'ExportController@deviceIssuancePdf')->name('export.device-issuance.pdf');
        Route::post('device-issuance-xls', 'ExportController@deviceIssuanceXls')->name('export.device-issuance.xls');
        Route::post('device-transfer-pdf', 'ExportController@deviceTransferPdf')->name('export.device-transfer.pdf');
        Route::post('device-transfer-xls', 'ExportController@deviceTransferXls')->name('export.device-transfer.xls');

        Route::post('device-purchase-request-pdf', 'ExportController@devicePurchaseRequestPdf')->name('export.device-purchase-request.pdf');
        Route::post('device-purchase-request-xls', 'ExportController@devicePurchaseRequestXls')->name('export.device-purchase-request.xls');

        Route::post('device-purchase-pdf', 'ExportController@devicePurchasePdf')->name('export.device-purchase.pdf');
        Route::post('device-purchase-xls', 'ExportController@devicePurchaseXls')->name('export.device-purchase.xls');

        Route::post('device-contract-pdf', 'ExportController@deviceContractPdf')->name('export.device-contract.pdf');
        Route::post('device-contract-xls', 'ExportController@deviceContractXls')->name('export.device-contract.xls');

        Route::post('device-clearance-pdf', 'ExportController@deviceClearancePdf')->name('export.device-clearance.pdf');
        Route::post('device-clearance-xls', 'ExportController@deviceClearanceXls')->name('export.device-clearance.xls');

        Route::post('device-rental-pdf', 'ExportController@deviceRentalPdf')->name('export.device-rental.pdf');
        Route::post('device-rental-xls', 'ExportController@deviceRentalXls')->name('export.device-rental.xls');
    });

    Route::group(['prefix' => 'import'], function () {
        Route::post('supplies', 'ImportController@importSupplies')->name('import.supplies');
        Route::post('items', 'ImportController@importItems')->name('import.items');
        Route::post('subcontractors', 'ImportController@importSubcontractors')->name('import.subcontractors');
        Route::post('devices', 'ImportController@importDevices')->name('import.devices');
    });

    Route::group(['prefix' => 'file'], function () {
        Route::post('upload', 'AttachFileController@uploadFile')->name('file.upload');
        Route::delete('/{id}', 'AttachFileController@deleteFile')->name('file.destroy');
    });

    Route::group(['prefix' => 'comment'], function () {
        Route::post('store', 'CommentController@store')->name('comment.store');
    });

    Route::get('tracking/{processLog}', 'ProcessLogController@show')->name('log.detail');
    Route::get('files/history', 'AttachFileController@history')->name('files.history');

    Route::get('contractor', 'SubContractorController@getSubcontractors')->name('contractor.detail');
});
