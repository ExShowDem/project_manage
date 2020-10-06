<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => App\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => App\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */

        'model_morph_key' => 'model_id',
    ],

    /*
     * When set to true, the required permission/role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'spatie.permission.cache',

        /*
         * When checking for a permission against a model by passing a Permission
         * instance to the check, this key determines what attribute on the
         * Permissions model is used to cache against.
         *
         * Ideally, this should match your preferred way of checking permissions, eg:
         * `$user->can('view-posts')` would be 'name'.
         */

        'model_key' => 'name',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

    'features' => [
            'Dashboard' => [
                'dashboard.read',
                'dashboard.create',
                'dashboard.update',
                'dashboard.delete',
                'dashboard.approve',
                'dashboard.see_price',
            ],
            'Quản lý công việc' => [
                'Công việc cần xử lý' => [
                    'tasks.read',
                    'tasks.create',
                    'tasks.update',
                    'tasks.delete',
                    'tasks.approve',
                    'tasks.see_price',
                ],
                /* 'Kế hoạch công việc' => [
                     'work_plan.read',
                     'work_plan.create',
                     'work_plan.update',
                     'work_plan.delete',
                     'work_plan.approve',
                     'work_plan.see_price',
                 ],
                 'Quản lý nguồn lực' => [
                     'resources.read',
                     'resources.create',
                     'resources.update',
                     'resources.delete',
                     'resources.approve',
                     'resources.see_price',
                 ],
                 'Loại nguồn lực' => [
                     'resource_types.read',
                     'resource_types.create',
                     'resource_types.update',
                     'resource_types.delete',
                     'resource_types.approve',
                     'resource_types.see_price',
                 ],*/
            ],
            'Quản lý vật tư' => [
                /*  'Kế hoạch vật tư' => [
                      'plans.supplies.read',
                      'plans.supplies.create',
                      'plans.supplies.update',
                      'plans.supplies.delete',
                      'plans.supplies.approve',
                      'plans.supplies.see_price',
                  ],*/
                'Theo dõi sử dụng vật tư' => [
                    'items.read',
                    'items.create',
                    'items.update',
                    'items.delete',
                    'items.approve',
                    'items.see_price',
                ],
                'Yêu cầu vật tư dự án' => [
                    'supplies.request_from_project.read',
                    'supplies.request_from_project.create',
                    'supplies.request_from_project.update',
                    'supplies.request_from_project.delete',
                    'supplies.request_from_project.approve',
                    'supplies.request_from_project.see_price',
                ],
                /*'Yêu cầu vật tư công ty' => [
                    'supplies.request_from_company.read',
                    'supplies.request_from_company.create',
                    'supplies.request_from_company.update',
                    'supplies.request_from_company.delete',
                    'supplies.request_from_company.approve',
                    'supplies.request_from_company.see_price',
                ],*/
            ],
            'Danh mục vật tư' => [
                'Vật tư' => [
                    'supplies.read',
                    'supplies.create',
                    'supplies.update',
                    'supplies.delete',
                    'supplies.approve',
                    'supplies.see_price',
                ],
                'Thiết bị' => [
                    'devices.read',
                    'devices.create',
                    'devices.update',
                    'devices.delete',
                    'devices.approve',
                    'devices.see_price',
                ],
                /* 'Nhóm vật tư' => [
                     'category_supplies.read',
                     'category_supplies.create',
                     'category_supplies.update',
                     'category_supplies.delete',
                     'category_supplies.approve',
                     'category_supplies.see_price',
                 ],*/
            ],
            'Quản lý kho' => [

                'Đề xuất mua vật tư' => [
                    'offer_buys.read',
                    'offer_buys.create',
                    'offer_buys.update',
                    'offer_buys.delete',
                    'offer_buys.approve',
                    'offer_buys.see_price',
                ],
                'Hoá đơn mua vật tư' => [
                    'invoices.read',
                    'invoices.create',
                    'invoices.update',
                    'invoices.delete',
                    'invoices.approve',
                    'invoices.see_price',
                ],
                'Nhập kho' => [
                    'receipt_inputs.read',
                    'receipt_inputs.create',
                    'receipt_inputs.update',
                    'receipt_inputs.delete',
                    'receipt_inputs.approve',
                    'receipt_inputs.see_price',
                ],
                'Xuất kho' => [
                    'receipt_outputs.read',
                    'receipt_outputs.create',
                    'receipt_outputs.update',
                    'receipt_outputs.delete',
                    'receipt_outputs.approve',
                    'receipt_outputs.see_price',
                ],
                'Chuyển kho' => [
                    'receipt_transfers.read',
                    'receipt_transfers.create',
                    'receipt_transfers.update',
                    'receipt_transfers.delete',
                    'receipt_transfers.approve',
                    'receipt_transfers.see_price',
                ],
                'Xuất kho theo yêu cầu' => [
                    'delivery_on_demands.read',
                    'delivery_on_demands.create',
                    'delivery_on_demands.update',
                    'delivery_on_demands.delete',
                    'delivery_on_demands.approve',
                    'delivery_on_demands.see_price',
                ],
                'Chi tiết kho' => [
                    'inventories_detail.read',
                    'inventories_detail.create',
                    'inventories_detail.update',
                    'inventories_detail.delete',
                    'inventories_detail.approve',
                    'inventories_detail.see_price',
                ],
                'Kiểm kê kho' => [
                    'stocktaking.read',
                    'stocktaking.create',
                    'stocktaking.update',
                    'stocktaking.delete',
                    'stocktaking.approve',
                    'stocktaking.see_price',
                ],
                'Nhập kho thiết bị' => [
                    'device_input.read',
                    'device_input.create',
                    'device_input.update',
                    'device_input.delete',
                    'device_input.approve',
                    'device_input.see_price',
                ],
                'Mua thiết bị' => [
                    'device_purchase.read',
                    'device_purchase.create',
                    'device_purchase.update',
                    'device_purchase.delete',
                    'device_purchase.approve',
                    'device_purchase.see_price',
                ],
                'Mua thiết bị theo yêu cầu' => [
                    'device_requested_purchase.read',
                    'device_requested_purchase.create',
                    'device_requested_purchase.update',
                    'device_requested_purchase.delete',
                    'device_requested_purchase.approve',
                    'device_requested_purchase.see_price',
                ],
                'Hóa đơn mua thiết bị' => [
                    'device_contract.read',
                    'device_contract.create',
                    'device_contract.update',
                    'device_contract.delete',
                    'device_contract.approve',
                    'device_contract.see_price',
                ],
            ],
        'Quản lý thiết bị' => [


            'Dự trù thiết bị tổng' => [
                'device_estimates.read',
                'device_estimates.create',
                'device_estimates.update',
                'device_estimates.delete',
                'device_estimates.approve',
                'device_estimates.see_price',
            ],
            'Dự trù thiết bị tháng' => [
                'device_monthly_estimates.read',
                'device_monthly_estimates.create',
                'device_monthly_estimates.update',
                'device_monthly_estimates.delete',
                'device_monthly_estimates.approve',
                'device_monthly_estimates.see_price',
            ],
            'Phiếu đề nghị cấp thiết bị' => [
                'device_issuance.read',
                'device_issuance.create',
                'device_issuance.update',
                'device_issuance.delete',
                'device_issuance.approve',
                'device_issuance.see_price',
            ],
            'Kế hoạch điều chuyển thiết bị' => [
                'device_transfer.read',
                'device_transfer.create',
                'device_transfer.update',
                'device_transfer.delete',
                'device_transfer.approve',
                'device_transfer.see_price',
            ],
            'Yêu cầu mua mới thiết bị' => [
                'device_purchase_request.read',
                'device_purchase_request.create',
                'device_purchase_request.update',
                'device_purchase_request.delete',
                'device_purchase_request.approve',
                'device_purchase_request.see_price',
            ],
            /*
            'Luân chuyển thiết bị' => [
                'device_round_robin.read',
                'device_round_robin.create',
                'device_round_robin.update',
                'device_round_robin.delete',
                'device_round_robin.approve',
                'device_round_robin.see_price',
            ],*/
            /*
             'Xuất thiết bị tới dự án' => [
                 'device_project.read',
                 'device_project.create',
                 'device_project.update',
                 'device_project.delete',
                 'device_project.approve',
                 'device_project.see_price',
             ],
             'Trả thiết bị về công ty' => [
                 'device_company.read',
                 'device_company.create',
                 'device_company.update',
                 'device_company.delete',
                 'device_company.approve',
                 'device_company.see_price',
             ],*/
            'Xuất bill' => [
                'device_bill.read',
                'device_bill.create',
                'device_bill.update',
                'device_bill.delete',
                'device_bill.approve',
                'device_bill.see_price',
            ],
            'Thống kê thiết bị' => [
                'device_stats.read',
                'device_stats.create',
                'device_stats.update',
                'device_stats.delete',
                'device_stats.approve',
                'device_stats.see_price',
            ],
            /* 'Kiểm kê thiết bị' => [
                 'device_inventory.read',
                 'device_inventory.create',
                 'device_inventory.update',
                 'device_inventory.delete',
                 'device_inventory.approve',
                 'device_inventory.see_price',
             ],*/
         /*   'Bảo trì, sửa chữa' => [
                'device_maintainence.read',
                'device_maintainence.create',
                'device_maintainence.update',
                'device_maintainence.delete',
                'device_maintainence.approve',
                'device_maintainence.see_price',
            ],*/
            'Thanh lý thiết bị' => [
                'device_clearance.read',
                'device_clearance.create',
                'device_clearance.update',
                'device_clearance.delete',
                'device_clearance.approve',
                'device_clearance.see_price',
            ],
            'Cho thuê thiết bị' => [
                'device_rental.read',
                'device_rental.create',
                'device_rental.update',
                'device_rental.delete',
                'device_rental.approve',
                'device_rental.see_price',
            ],
        ],
        'Đối tác' => [
            'Nhà cung cấp' => [
                'suppliers.read',
                'suppliers.create',
                'suppliers.update',
                'suppliers.delete',
                'suppliers.approve',
                'suppliers.see_price',
            ],
            'Khách hàng' => [
                'customers.read',
                'customers.create',
                'customers.update',
                'customers.delete',
                'customers.approve',
                'customers.see_price',
            ],
        ],
        'Nhà thầu phụ' => [
            'Danh sách NTP' => [
                'sub_contractors.read',
                'sub_contractors.create',
                'sub_contractors.update',
                'sub_contractors.delete',
                'sub_contractors.approve',
                'sub_contractors.see_price',
            ],
            'Danh sách hồ sơ NTP' => [
                'censor_sub.read',
                'censor_sub.create',
                'censor_sub.update',
                'censor_sub.delete',
                'censor_sub.approve',
                'censor_sub.see_price',
            ],
            'Danh sách hợp đồng NTP' => [
                'contract_sub.read',
                'contract_sub.create',
                'contract_sub.update',
                'contract_sub.delete',
                'contract_sub.approve',
                'contract_sub.see_price',
            ],
            'Danh sách đề nghị thanh toán NTP' => [
                'payment_order.read',
                'payment_order.create',
                'payment_order.update',
                'payment_order.delete',
                'payment_order.approve',
                'payment_order.see_price',
            ],
        ],
        'Tài khoản' => [
            'users.read',
            'users.create',
            'users.update',
            'users.delete',
            'users.approve',
            'users.see_price',
        ],
        'Tổ chức' => [
            'roles.read',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.approve',
            'roles.see_price',
        ],
        'Báo cáo' => [
            'report.read',
            // 'report.create',
            // 'report.update',
            // 'report.delete',
            // 'report.approve',
            // 'report.see_price',
        ],
        'Dự án' => [
            'projects.read',
            'projects.create',
            'projects.update',
            'projects.delete',
            'projects.approve',
            'projects.see_price',
        ],
    ]
];