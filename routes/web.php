<?php

Route::redirect('/', '/login');

/*
If you are already logged out, then you visit /logout again, 
"GET method is not supported for this route. Supported methods: POST." error page shows.
Solution: https://stackoverflow.com/questions/48417762/logout-error-laravel
*/
Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});

Auth::routes([
    /* Send an email to authenticate user. */
    // https://stackoverflow.com/questions/29183348/how-to-disable-registration-new-users-in-laravel
    // https://laravel.com/docs/7.x/verification
    'verify' => false,
]);

Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'verified'], 'as' => 'admin.'], function () {
    /**
     * Profile
     */
    Route::get('error', 'ErrorController@index')->name('error');

    Route::get('profile', 'ProfileController@index')
        ->name('profile.index');
    Route::post('profile/update-information', 'ProfileController@updateInformation')
        ->name('profile.updateInformation');
    Route::post('profile/change-password', 'ProfileController@changePassword')
        ->name('profile.changePassword');

    /**
     * Projects
     */
    Route::resource('/projects', 'ProjectController', ['only' => ['index', 'create', 'edit']]);

    Route::resource('/analytic', 'AnalyticController', ['only' => ['index', 'create', 'edit']]);

    Route::group(['prefix' => '/projects/{projectId}', 'as' => 'projects.'], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('/report', 'ReportController@index')->name('report');

        Route::get('/notifications/{id}', 'NotificationController@read')->name('notifications.read');

        /**
         * Ke hoach cong viec
         */
        Route::group(['prefix' => 'work-plan', 'as' => 'work_plan.'], function () {
            Route::get('/', 'WorkPlanController@index')->name('index');
        });
        /**
         * Users
         */
        Route::resource('/users', 'UserController', ['only' => ['index', 'create', 'edit']]);

        Route::resource('/roles', 'RoleController', ['only' => ['index', 'create', 'edit']]);

        /**
         * Plans
         */
        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::get('supplies', 'PlanController@planSupplies')->name('supplies');
            Route::get('supplies/create', 'PlanController@create')->name('supplies.create');
            Route::get('supplies/{id}/edit', 'PlanController@editPlanSupplies')->name('supplies.edit');
            Route::get('supplies/{id}/tracking', 'PlanController@tracking')->name('supplies.tracking');
            Route::get('supplies/{id}/tracking/{log_id}', 'PlanController@trackingDetail')->name('supplies.tracking.detail');
            Route::get('supplies/{id}', 'PlanController@show')->name('supplies.show');
        });

        Route::group(['as' => 'items.'], function () {
            Route::get('items', 'ItemController@index')->name('index');
            Route::get('items/create', 'ItemController@create')->name('create');
            Route::get('items/{id}/edit', 'ItemController@edit')->name('edit');
            Route::get('items/{id}/tracking', 'ItemController@tracking')->name('tracking');
            Route::get('items/{id}/tracking/{log_id}', 'ItemController@trackingDetail')->name('tracking.detail');
            Route::get('items/{id}', 'ItemController@show')->name('show');
        });

        /**
         * Request supplies project
         */
        Route::group(['prefix' => 'requests', 'as' => 'requests.'], function () {
            Route::get('/supplies/{target}', 'RequestController@requestSupplies')->name('supplies')->where('target', 'company|project');
            Route::get('/supplies/{target}/create', 'RequestController@createRequestSupplies')->name('supplies.create')->where('target', 'company|project');
            Route::get('/supplies/{target}/{id}/edit', 'RequestController@editRequestSupplies')->name('supplies.edit')->where('target', 'company|project');
            Route::get('/supplies/{target}/{id}/tracking', 'RequestController@tracking')->name('supplies.tracking')->where('target', 'company|project');
            Route::get('/supplies/{target}/{id}/tracking/{log_id}', 'RequestController@trackingDetail')->name('supplies.tracking.detail')->where('target', 'company|project');
            Route::get('/supplies/{target}/{id}', 'RequestController@show')->name('supplies.show')->where('target', 'company|project');
        });

        /**
         * Supplies
         */
        Route::group(['as' => 'supplies.'], function () {
            Route::get('supplies', 'SuppliesController@index')->name('index');
            Route::get('supplies/create', 'SuppliesController@create')->name('create');
            Route::get('supplies/{id}/edit', 'SuppliesController@edit')->name('edit');
        });

        /**
         * Devices
         */
        Route::group(['as' => 'devices.'], function () {
            Route::get('devices', 'DevicesController@index')->name('index');
            Route::get('devices/create', 'DevicesController@create')->name('create');
            Route::get('devices/{id}/edit', 'DevicesController@edit')->name('edit');
        });
        Route::group(['as' => 'devicesdefault.'], function () {
            Route::get('devicesdefault', 'DevicesController@index')->name('index');
            Route::get('devicesdefault/create', 'DevicesController@create')->name('create');
            Route::get('devicesdefault/{id}/edit', 'DevicesController@edit')->name('edit');
        });

        /**
         * Supplier
         */
        Route::group(['as' => 'suppliers.'], function () {
            Route::get('suppliers', 'SupplierController@index')->name('index');
            Route::get('suppliers/create', 'SupplierController@create')->name('create');
            Route::get('suppliers/{id}/edit', 'SupplierController@edit')->name('edit');
            Route::get('suppliers/{id}/tracking', 'SupplierController@tracking')->name('tracking');
            Route::get('suppliers/{id}/tracking/{log_id}', 'SupplierController@trackingDetail')->name('tracking.detail');
        });

        /**
         * Category Supplies
         */
        Route::group(['as' => 'category-supplies.'], function () {
            Route::get('category-supplies', 'CategorySuppliesController@index')->name('index');
            Route::get('category-supplies/create', 'CategorySuppliesController@create')->name('create');
            Route::get('category-supplies/{id}/edit', 'CategorySuppliesController@edit')->name('edit');
        });

        /**
         * Invoices
         */
        Route::group(['as' => 'invoices.'], function () {
            Route::get('invoices', 'InvoiceController@index')->name('index');
            Route::get('invoices/create', 'InvoiceController@create')->name('create');
            Route::get('invoices/{id}/edit', 'InvoiceController@edit')->name('edit');
            Route::get('invoices/{id}/receipt-inputs/create', 'ReceiptInputController@createTicketImport')->name('receipt-input.create');
            Route::get('invoices/{id}/ticket-import/create', 'InvoiceController@createTicketImport')->name('ticket-import.create');
            Route::get('invoices/{id}/tracking', 'InvoiceController@tracking')->name('tracking');
            Route::get('invoices/{id}/tracking/{log_id}', 'InvoiceController@trackingDetail')->name('tracking.detail');
            Route::get('invoices/{id}', 'InvoiceController@show')->name('show');
        });

        /**
         * Offer Buys
         */
        Route::group(['as' => 'offer-buys.'], function () {
            Route::get('offer-buys', 'OfferBuyController@index')->name('index');
            Route::get('offer-buys/create', 'OfferBuyController@create')->name('create');
            Route::get('offer-buys/{id}/edit', 'OfferBuyController@edit')->name('edit');
            Route::get('offer-buys/{id}/invoice/create', 'OfferBuyController@createInvoice')->name('invoice.create');
            Route::get('offer-buys/{id}/tracking', 'OfferBuyController@tracking')->name('tracking');
            Route::get('offer-buys/{id}/tracking/{log_id}', 'OfferBuyController@trackingDetail')->name('tracking.detail');
            Route::get('offer-buys/{id}', 'OfferBuyController@show')->name('show');

        });

        /**
         * Subcontractor
         */
        Route::group(['as' => 'sub-contractors.'], function () {
            Route::get('sub-contractors/create', 'SubContractorController@create')->name('create');
            Route::get('sub-contractors', 'SubContractorController@index')->name('index');
            Route::get('sub-contractors/{id}/edit', 'SubContractorController@edit')->name('edit');
            Route::get('sub-contractors/{id}/tracking', 'SubContractorController@tracking')->name('tracking');
            Route::get('sub-contractors/{id}/tracking/{log_id}', 'SubContractorController@trackingDetail')->name('tracking.detail');
            Route::get('sub-contractors/{id}', 'SubContractorController@show')->name('show');
        });

        /**
         * Censor-Subcontractor
         */
        Route::group(['as' => 'censor-sub.'], function () {
            Route::get('censor-sub/create', 'CensorSubController@create')->name('create');
            Route::get('censor-sub', 'CensorSubController@index')->name('index');
            Route::get('censor-sub/{id}/edit', 'CensorSubController@edit')->name('edit');
            Route::get('censor-sub/{id}/tracking', 'CensorSubController@tracking')->name('tracking');
            Route::get('censor-sub/{id}/tracking/{log_id}', 'CensorSubController@trackingDetail')->name('tracking.detail');
            Route::get('censor-sub/{id}', 'CensorSubController@show')->name('show');
        });

        /**
         * Contract-Censor-Subcontractor
         */
        Route::group(['as' => 'contract-sub.'], function () {
            Route::get('contract-sub/create', 'ContractSubController@create')->name('create');
            Route::get('contract-sub', 'ContractSubController@index')->name('index');
            Route::get('contract-sub/{id}/edit', 'ContractSubController@edit')->name('edit');
            Route::get('contract-sub/{id}/tracking', 'ContractSubController@tracking')->name('tracking');
            Route::get('contract-sub/{id}/tracking/{log_id}', 'ContractSubController@trackingDetail')->name('tracking.detail');
            Route::get('contract-sub/{id}', 'ContractSubController@show')->name('show');
        });

        /**
         * Payment-Order
         */
        Route::group(['as' => 'payment-order.'], function () {
            Route::get('payment-order/create', 'PaymentOrderController@create')->name('create');
            Route::get('payment-order', 'PaymentOrderController@index')->name('index');
            Route::get('payment-order/{id}/edit', 'PaymentOrderController@edit')->name('edit');
            Route::get('payment-order/{id}/tracking', 'PaymentOrderController@tracking')->name('tracking');
            Route::get('payment-order/{id}/tracking/{log_id}', 'PaymentOrderController@trackingDetail')->name('tracking.detail');
            Route::get('payment-order/{id}', 'PaymentOrderController@show')->name('show');
        });

        Route::group(['prefix' => 'inventories', 'as' => 'inventories.'], function () {
            /**
             * Receipt Inputs
             */
            Route::group(['as' => 'receipt-inputs.'], function () {
                Route::get('receipt-inputs', 'ReceiptInputController@index')->name('index');
                Route::get('receipt-inputs/create', 'ReceiptInputController@create')->name('create');
                Route::get('receipt-inputs/{id}/edit', 'ReceiptInputController@edit')->name('edit');
                Route::get('receipt-inputs/{id}/tracking', 'ReceiptInputController@tracking')->name('tracking');
                Route::get('receipt-inputs/{id}/tracking/{log_id}', 'ReceiptInputController@trackingDetail')->name('tracking.detail');
                Route::get('receipt-inputs/{id}', 'ReceiptInputController@show')->name('show');
            });

            /**
             * Receipt Outputs
             */
            Route::group(['as' => 'receipt-outputs.'], function () {
                Route::get('receipt-outputs', 'ReceiptOutputController@index')->name('index');
                Route::get('receipt-outputs/create', 'ReceiptOutputController@create')->name('create');
                Route::get('receipt-outputs/{id}/edit', 'ReceiptOutputController@edit')->name('edit');
                Route::get('receipt-outputs/{id}/tracking', 'ReceiptOutputController@tracking')->name('tracking');
                Route::get('receipt-outputs/{id}/tracking/{log_id}', 'ReceiptOutputController@trackingDetail')->name('tracking.detail');
                Route::get('receipt-outputs/{id}', 'ReceiptOutputController@show')->name('show');
            });

            /**
             * Receipt Transfers
             */
            Route::group(['as' => 'receipt-transfers.'], function () {
                Route::get('receipt-transfers', 'ReceiptTransferController@index')->name('index');
                Route::get('receipt-transfers/create', 'ReceiptTransferController@create')->name('create');
                Route::get('receipt-transfers/{id}/edit', 'ReceiptTransferController@edit')->name('edit');
                Route::get('receipt-transfers/{id}/tracking', 'ReceiptTransferController@tracking')->name('tracking');
                Route::get('receipt-transfers/{id}/tracking/{log_id}', 'ReceiptTransferController@trackingDetail')->name('tracking.detail');
                Route::get('receipt-transfers/{id}', 'ReceiptTransferController@show')->name('show');
            });

            Route::group(['as' => 'delivery_on_demand.', 'prefix' => 'delivery-on-demand'], function () {
                Route::get('/', 'InventoriesController@deliveryOnDemand')->name('index');
                Route::get('/create', 'InventoriesController@createReceiptDeliveryOnDemand')->name('create');
            });

            Route::get('/detail', 'InventoriesController@detail')->name('detail.index');

            Route::group(['as' => 'stocktaking.', 'prefix' => 'stocktaking'], function () {
                Route::get('/', 'InventoriesController@stocktaking')->name('index');
                Route::get('/create', 'InventoriesController@createStocktaking')->name('create');
                Route::get('/{id}/edit', 'InventoriesController@editStocktaking')->name('edit');
                Route::get('/{id}', 'InventoriesController@showStocktaking')->name('show');
                Route::get('/{id}/tracking', 'InventoriesController@trackingStocktaking')->name('tracking');
                Route::get('/{id}/tracking/{log_id}', 'InventoriesController@trackingStocktakingDetail')->name('tracking.detail');
            });
        });
        Route::group(['prefix' => 'devicespurchase', 'as' => 'devicespurchase.'], function () {

            Route::resource('/project', 'DeviceToProjectController');
            Route::get('project/{id}/tracking', 'DeviceToProjectController@tracking')->name('project.tracking');

            Route::resource('/company', 'DeviceReturnToCompanyController');
            Route::get('company/{id}/tracking', 'DeviceReturnToCompanyController@tracking')->name('company.tracking');

            Route::resource('/inventory', 'DeviceInventoryController');
            Route::get('inventory/{id}/tracking', 'DeviceInventoryController@tracking')->name('inventory.tracking');

            Route::resource('/maintainence', 'DeviceMaintainenceController');
            Route::get('maintainence/{id}/tracking', 'DeviceMaintainenceController@tracking')->name('maintainence.tracking');

            Route::resource('/clearance', 'DeviceClearanceController');
            Route::get('clearance/{id}/tracking', 'DeviceClearanceController@tracking')->name('clearance.tracking');
            Route::get('clearance/{id}/tracking/{log_id}', 'DeviceClearanceController@trackingDetail')->name('clearance.tracking.detail');

            Route::resource('/rental', 'DeviceRentalController');
            Route::get('rental/{id}/tracking', 'DeviceRentalController@tracking')->name('rental.tracking');
            Route::get('rental/{id}/tracking/{log_id}', 'DeviceRentalController@trackingDetail')->name('rental.tracking.detail');

            Route::resource('/bill', 'DeviceBillController');
            Route::resource('/stats', 'DeviceStatsController');

            Route::resource('/input', 'DeviceInputController');
            Route::get('input/{id}/tracking', 'DeviceInputController@tracking')->name('input.tracking');
            Route::get('input/{id}/tracking/{log_id}', 'DeviceInputController@trackingDetail')->name('input.tracking.detail');

            Route::resource('/estimates', 'DeviceEstimatesController');
            Route::get('estimates/{id}/tracking', 'DeviceEstimatesController@tracking')->name('estimates.tracking');
            Route::get('estimates/{id}/tracking/{log_id}', 'DeviceEstimatesController@trackingDetail')->name('estimates.tracking.detail');

            Route::resource('/monthly_estimates', 'DeviceMonthlyEstimatesController');
            Route::get('monthly_estimates/{id}/tracking', 'DeviceMonthlyEstimatesController@tracking')->name('monthly_estimates.tracking');
            Route::get('monthly_estimates/{id}/tracking/{log_id}', 'DeviceMonthlyEstimatesController@trackingDetail')->name('monthly_estimates.tracking.detail');

            Route::resource('/issuance', 'DeviceIssuanceController');
            Route::get('issuance/{id}/tracking', 'DeviceIssuanceController@tracking')->name('issuance.tracking');
            Route::get('issuance/{id}/tracking/{log_id}', 'DeviceIssuanceController@trackingDetail')->name('issuance.tracking.detail');

            Route::resource('/transfer', 'DeviceTransferController');
            Route::get('transfer/{id}/tracking', 'DeviceTransferController@tracking')->name('transfer.tracking');
            Route::get('transfer/{id}/tracking/{log_id}', 'DeviceTransferController@trackingDetail')->name('transfer.tracking.detail');

            Route::resource('/purchase_request', 'DevicePurchaseRequestController');
            Route::get('purchase_request/{id}/tracking', 'DevicePurchaseRequestController@tracking')->name('purchase_request.tracking');
            Route::get('purchase_request/{id}/tracking/{log_id}', 'DevicePurchaseRequestController@trackingDetail')->name('purchase_request.tracking.detail');

            Route::resource('/purchase', 'DevicePurchaseController');
            Route::get('purchase/{id}/tracking', 'DevicePurchaseController@tracking')->name('purchase.tracking');
            Route::get('purchase/{id}/tracking/{log_id}', 'DevicePurchaseController@trackingDetail')->name('purchase.tracking.detail');

            Route::resource('/requested_purchase', 'DeviceRequestedPurchaseController');
            Route::resource('/round_robin', 'DeviceRoundRobinController');

            Route::resource('/contract', 'DeviceContractController');
            Route::get('contract/{id}/tracking', 'DeviceContractController@tracking')->name('contract.tracking');
            Route::get('contract/{id}/tracking/{log_id}', 'DeviceContractController@trackingDetail')->name('contract.tracking.detail');

        });
        Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
            Route::resource('/project', 'DeviceToProjectController');
            Route::get('project/{id}/tracking', 'DeviceToProjectController@tracking')->name('project.tracking');

            Route::resource('/company', 'DeviceReturnToCompanyController');
            Route::get('company/{id}/tracking', 'DeviceReturnToCompanyController@tracking')->name('company.tracking');

            Route::resource('/inventory', 'DeviceInventoryController');
            Route::get('inventory/{id}/tracking', 'DeviceInventoryController@tracking')->name('inventory.tracking');

            Route::resource('/maintainence', 'DeviceMaintainenceController');
            Route::get('maintainence/{id}/tracking', 'DeviceMaintainenceController@tracking')->name('maintainence.tracking');

            Route::resource('/clearance', 'DeviceClearanceController');
            Route::get('clearance/{id}/tracking', 'DeviceClearanceController@tracking')->name('clearance.tracking');
            Route::get('clearance/{id}/tracking/{log_id}', 'DeviceClearanceController@trackingDetail')->name('clearance.tracking.detail');

            Route::resource('/rental', 'DeviceRentalController');
            Route::get('rental/{id}/tracking', 'DeviceRentalController@tracking')->name('rental.tracking');
            Route::get('rental/{id}/tracking/{log_id}', 'DeviceRentalController@trackingDetail')->name('rental.tracking.detail');

            Route::resource('/bill', 'DeviceBillController');
            Route::resource('/stats', 'DeviceStatsController');

            Route::resource('/input', 'DeviceInputController');
            Route::get('input/{id}/tracking', 'DeviceInputController@tracking')->name('input.tracking');
            Route::get('input/{id}/tracking/{log_id}', 'DeviceInputController@trackingDetail')->name('input.tracking.detail');

            Route::resource('/estimates', 'DeviceEstimatesController');
            Route::get('estimates/{id}/tracking', 'DeviceEstimatesController@tracking')->name('estimates.tracking');
            Route::get('estimates/{id}/tracking/{log_id}', 'DeviceEstimatesController@trackingDetail')->name('estimates.tracking.detail');

            Route::resource('/monthly_estimates', 'DeviceMonthlyEstimatesController');
            Route::get('monthly_estimates/{id}/tracking', 'DeviceMonthlyEstimatesController@tracking')->name('monthly_estimates.tracking');
            Route::get('monthly_estimates/{id}/tracking/{log_id}', 'DeviceMonthlyEstimatesController@trackingDetail')->name('monthly_estimates.tracking.detail');

            Route::resource('/issuance', 'DeviceIssuanceController');
            Route::get('issuance/{id}/tracking', 'DeviceIssuanceController@tracking')->name('issuance.tracking');
            Route::get('issuance/{id}/tracking/{log_id}', 'DeviceIssuanceController@trackingDetail')->name('issuance.tracking.detail');

            Route::resource('/transfer', 'DeviceTransferController');
            Route::get('transfer/{id}/tracking', 'DeviceTransferController@tracking')->name('transfer.tracking');
            Route::get('transfer/{id}/tracking/{log_id}', 'DeviceTransferController@trackingDetail')->name('transfer.tracking.detail');

            Route::resource('/purchase_request', 'DevicePurchaseRequestController');
            Route::get('purchase_request/{id}/tracking', 'DevicePurchaseRequestController@tracking')->name('purchase_request.tracking');
            Route::get('purchase_request/{id}/tracking/{log_id}', 'DevicePurchaseRequestController@trackingDetail')->name('purchase_request.tracking.detail');

            Route::resource('/purchase', 'DevicePurchaseController');
            Route::get('purchase/{id}/tracking', 'DevicePurchaseController@tracking')->name('purchase.tracking');
            Route::get('purchase/{id}/tracking/{log_id}', 'DevicePurchaseController@trackingDetail')->name('purchase.tracking.detail');

            Route::resource('/requested_purchase', 'DeviceRequestedPurchaseController');
            Route::resource('/round_robin', 'DeviceRoundRobinController');

            Route::resource('/contract', 'DeviceContractController');
            Route::get('contract/{id}/tracking', 'DeviceContractController@tracking')->name('contract.tracking');
            Route::get('contract/{id}/tracking/{log_id}', 'DeviceContractController@trackingDetail')->name('contract.tracking.detail');
        });

        /**
         * Resources
         */
        Route::group(['as' => 'resources.'], function () {
            Route::get('resources', 'ResourceController@index')->name('index');
            Route::get('resources/create', 'ResourceController@create')->name('create');
            Route::get('resources/{id}/edit', 'ResourceController@edit')->name('edit');
        });

        /**
         * Resource Types
         */
        Route::group(['as' => 'resource_types.'], function () {
            Route::get('resource_types', 'ResourceTypeController@index')->name('index');
            Route::get('resource_types/create', 'ResourceTypeController@create')->name('create');
            Route::get('resource_types/{id}/edit', 'ResourceTypeController@edit')->name('edit');
        });

        /**
         * My tasks
         */
        Route::group(['as' => 'tasks.', 'prefix' => 'tasks'], function () {
            Route::get('/', 'TaskController@index')->name('index');
            Route::get('/{id}', 'TaskController@show')->name('show');
        });
    });

    /**
     * Users
     */
    Route::resource('/users', 'UserController', ['only' => ['index', 'create', 'edit']]);

    /**
     * Roles
     */
    Route::resource('/roles', 'RoleController', ['only' => ['index', 'create', 'edit']]);

    Route::group(['prefix' => 'gantt', 'as' => 'gantt.'], function () {
        Route::get('/', 'GanttController@index')->name('index');
    });

    Route::get('/action_log', 'ActionLogController@index')->name('action_log.index');
    Route::post('/action_log/restore', 'ActionLogController@restore');

    Route::get('/report', 'ReportController@reportAll')->name('report.all');
});

Route::get('back-to-login', function (\Illuminate\Http\Request $request) {
    $request->session()->flush();

    return redirect('/login');
})->name('back-to-login');


