<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-tasks"></i>
                    <span class="title">Quản lý công việc</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if (auth()->user()->can('tasks.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.tasks.index', ['projectId' => request()->projectId]) }}?view_style=task"
                           class="nav-link ">
                            <i class="fa fa-tasks"></i>
                            <span class="title">Công việc cần xử lý</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('work_plan.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.work_plan.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Kế hoạch công việc</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('resources.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.resources.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Quản lý nguồn lực</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('resource_types.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.resource_types.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Loại nguồn lực</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-calendar-o"></i>
                    <span class="title">Quản lý vật tư</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if (auth()->user()->can('plans.supplies.read'))
                   {{-- <li class="nav-item start">
                        <a href="{{ route('admin.projects.plans.supplies', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Kế hoạch vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>--}}
                    @endif
                    @if (auth()->user()->can('items.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.items.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-calendar-o"></i>
                            <span class="title">Theo dõi sử dụng vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('supplies.request_from_project.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.requests.supplies', ['projectId' => request()->projectId, 'target' => 'project']) }}"
                           class="nav-link ">
                            <i class="fa fa-calendar-o"></i>
                            <span class="title">Yêu cầu vật tư dự án</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('supplies.request_from_company.read'))
                   {{-- <li class="nav-item start">
                        <a href="{{ route('admin.projects.requests.supplies', ['projectId' => request()->projectId, 'target' => 'company']) }}"
                           class="nav-link ">
                            <span class="title">Yêu cầu vật tư công ty</span>
                            <span class="selected"></span>
                        </a>
                    </li>--}}
                    @endif
                </ul>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-list"></i>
                    <span class="title">Danh mục vật tư</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    @if (auth()->user()->can('supplies.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.supplies.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-list"></i>
                            <span class="title">Vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                        @if (auth()->user()->can('devices.read'))
                            <li class="nav-item start">
                                <a href="{{ route('admin.projects.devicesdefault.index', ['projectId' => request()->projectId]) }}"
                                   class="nav-link ">
                                    <i class="fa fa-list"></i>
                                    <span class="title">Thiết bị</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                        @endif

                    @if (auth()->user()->can('category_supplies.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.category-supplies.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Nhóm vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-industry"></i>
                    <span class="title">Quản lý kho</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                   {{-- @if (auth()->user()->can('offer_buys.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.offer-buys.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <span class="title">Đề xuất mua vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif--}}
                    @if (auth()->user()->can('invoices.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.invoices.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Hoá đơn mua vật tư</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('receipt_inputs.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.receipt-inputs.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Nhập kho</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('receipt_outputs.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.receipt-outputs.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Xuất kho</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('receipt_transfers.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.receipt-transfers.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Chuyển kho</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('delivery_on_demands.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.delivery_on_demand.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Xuất kho theo yêu cầu</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('inventories_detail.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.detail.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Chi tiết kho</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('stocktaking.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.inventories.stocktaking.index', ['projectId' => request()->projectId]) }}" class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Kiểm kê kho</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif


                    @if (auth()->user()->can('device_input.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.input.index', ['projectId' => request()->projectId]) }}" class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Nhập kho thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_purchase.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.purchase.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Mua thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                   {{-- @if (auth()->user()->can('device_requested_purchase.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.requested_purchase.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Mua thiết bị theo yêu cầu</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif--}}
                    @if (auth()->user()->can('device_contract.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.contract.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-industry"></i>
                            <span class="title">Hóa đơn mua thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-user-circle"></i>
                    <span class="title">Quản lý thiết bị</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if (auth()->user()->can('device_estimates.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.estimates.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Dự trù thiết bị tổng</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_monthly_estimates.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.monthly_estimates.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Dự trù thiết bị tháng</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_issuance.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.issuance.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Phiếu đề nghị cấp thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_transfer.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.transfer.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Kế hoạch điều chuyển thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_purchase_request.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devicespurchase.purchase_request.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Yêu cầu mua mới thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_round_robin.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.round_robin.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Luân chuyển thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_project.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.project.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Xuất thiết bị tới dự án</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_company.read') && false)
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.company.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Trả thiết bị về công ty</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_bill.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.bill.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Xuất bill</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_stats.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.stats.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Thống kê thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_maintenance.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.maintenance.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Bảo trì, sửa chữa</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_clearance.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.clearance.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Thanh lý thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('device_rental.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.devices.rental.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-user-circle"></i>
                            <span class="title">Cho thuê thiết bị</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-sitemap"></i>
                    <span class="title">Đối tác</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if (auth()->user()->can('suppliers.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.suppliers.index', ['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-sitemap"></i>
                            <span class="title">Nhà cung cấp</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                   {{-- @if (auth()->user()->can('customers.read'))
                    <li class="nav-item start ">
                        <a href="{{ route('admin.projects.suppliers.index', ['projectId' => request()->projectId, 'type' => 'customer']) }}"
                           class="nav-link ">
                            <i class="fa fa-sitemap"></i>
                            <span class="title">Khách hàng</span>
                        </a>
                    </li>
                    @endif--}}
                </ul>
            </li>
          {{--  @if (auth()->user()->can('users.read'))
            <li class="nav-item start">
                <a href="{{ route('admin.projects.users.index', ['projectId' => request()->projectId]) }}"
                   class="nav-link">
                    <i class="fa fa-users"></i>
                    <span class="title">Tài khoản</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endif
            @if (auth()->user()->can('roles.read'))
            <li class="nav-item start">
                <a href="{{ route('admin.projects.roles.index', ['projectId' => request()->projectId]) }}"
                   class="nav-link">
                    <i class="fa fa-sitemap"></i>
                    <span class="title">Tổ chức</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endif--}}
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-suitcase"></i>
                    <span class="title">Nhà thầu phụ</span>
                    <span class="selected"></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if (auth()->user()->can('sub_contractors.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.sub-contractors.index',['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-suitcase"></i>
                            <span class="title">Danh sách NTP</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('censor_sub.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.censor-sub.index',['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-suitcase"></i>
                            <span class="title">Danh sách hồ sơ NTP</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('contract_sub.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.contract-sub.index',['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-suitcase"></i>
                            <span class="title">Danh sách hợp đồng NTP</span>
                            <span class="selected"></span>


                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->can('payment_order.read'))
                    <li class="nav-item start">
                        <a href="{{ route('admin.projects.payment-order.index',['projectId' => request()->projectId]) }}"
                           class="nav-link ">
                            <i class="fa fa-suitcase"></i>
                            <span class="title">Danh sách đề nghị thanh toán NTP</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @if (auth()->user()->can('report.read'))
            <li class="nav-item start">
                <a href="{{ route('admin.projects.report', ['projectId' => request()->projectId]) }}">
                    <i class="fa fa fa-users"></i>
                    <span class="title">Báo cáo</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
