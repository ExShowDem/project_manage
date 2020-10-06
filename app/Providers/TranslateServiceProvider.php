<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class TranslateServiceProvider extends ServiceProvider
{
    protected $dict = [
        'created_at'  => 'Thời gian tạo',
        'modified_at' => 'Thời gian đổi',
        'deleted_at' => 'Thời gian xóa',

        'project_id' => 'Dự án',
        'creator_id' => 'Người tạo',
        'created_date' => 'Ngày tạo',
        'name' => 'Tên',
        'code' => 'Mã',
        'reason' => 'Lý do',
        'status' => 'Trạng thái',

        'unit_price' => 'Đơn giá',
        'quantity' => 'Số lượng',
        'unit_id' => 'Đơn vị',
        'unit_name' => 'Đơn vị',
        'unit' => 'Đơn vị',
        'note' => 'Ghi chú',
        'note_request' => 'Ghi chú',
        'comment' => 'Ghi chú',

        'end_date' => 'Ngày kết thúc',
        'unit_price_budget' => 'Đơn giá chưa Vat',
        'type' => 'Loại',
        'type_text' => 'Loại',
        'is_vlk' => 'Vật liệu khác?',

        'receiver_type' => 'Loại bên nhận',
        'receiver' => 'Bên nhận',
        'content_offer' => 'Nội dung đề xuất',
        'estimate_quantity' => 'SL dự toán',
        'cumulative' => 'SL lũy kế',
        'approved_cum' => 'SL lũy kế đã duyệt',
        'input_cumulative' => 'SL lũy kế thực nhập',
        'date_arrival_request' => 'Ngày cần về',

        'parent_id' => 'Vật tư cấp cha',
        'category_supplies_id' => 'Vật tư thuộc nhóm',
        'material_code' => 'Mã hiệu',
        'size' => 'Kích thước',
        'specification' => 'Quy cách',
        'supplier' => 'Nhà sản xuất',
        'color' => 'Màu sắc',
        'intensity' => 'Cường độ',
        'density' => 'Tỉ trọng',
        'standard' => 'Tiêu chuẩn',
        'source' => 'Nguồn gốc',

        'suppliers' => 'Nhà cung cấp',
        'contract_date' => 'Ngày hoá đơn',
        'offer_buy_id' => 'Số yêu cầu',
        'contract_number' => 'Số hoá đơn',
        'existing_quantity' => 'SL chứng từ',
        'accumulate' => 'SL lũy kế',
        'discount' => 'CK (%)',
        'other_cost' => 'Phí khác',
        'tax' => 'Thuế suất',

        'output_id' => 'Bên xuất',
        'date_input' => 'Ngày nhập',
        'date_output' => 'Ngày xuất',
        'input_id' => 'Bên nhập',
        'request_id' => 'Số yêu cầu',
        'original_quantity' => 'SL chứng từ',
        'difference_reason' => 'Lý do chênh SL',
        'quantity_in_stock' => 'SL tồn kho',
        'accumulated_quantity' => 'SL lũy kế',

        'date_transfer' => 'Ngày chuyển',
        'quantity_system' => 'SL tồn hệ thống',
        'quantity_actual' => 'SL tồn thực tế',

        'company' => 'Nơi xuất',
        'purchase_request_id' => 'Yêu cầu mua thiết bị',
        'mass' => 'Khối lượng dự trù',
        'mass1' => 'BP.TB cung cấp',
        'price' => 'Đơn giá',
        'rent' => 'Thuê ngoài',
        'rent_price' => 'Đơn giá thuê',
        'mass2' => 'Khối lượng đầu tư',
        'estimated_unit_price' => 'Đơn giá đầu tư',
        'input_time' => 'Ngày dự trù cấp',
        'return_time' => 'Ngày dự trù trả',
        'days_used' => 'Tổng ngày sử dụng',
        'device_estimate_id' => 'ID Dự trù thiết bị tổng',

        'intention' => 'Mục đích',
        'batch1' => 'Đợt 1',
        'batch2' => 'Đợt 2',
        'batch3' => 'Đợt 3',
        'batch4' => 'Đợt 4',
        'batch5' => 'Đợt 5',
        'batch6' => 'Đợt 6',
        'quantity1' => 'SL 1',
        'quantity2' => 'SL 2',
        'quantity3' => 'SL 3',
        'quantity4' => 'SL 4',
        'quantity5' => 'SL 5',
        'quantity6' => 'SL 6',
        'total_quantity' => 'SL tổng',

        'monthly_estimates_id' => 'Dự trù tháng',
        'monthly_estimated_quantity' => 'SL dự trù tháng',
        'supply_date' => 'Ngày cung cấp',
        'return_date' => 'Ngày trả',
        'supply_date1' => 'Ngày cung cấp (P.TB)',
        'has_surpassed_estimates_label' => 'Vượt dự trù',

        'issued_quantity' => 'SL đề nghị',
        'carrier_type' => 'Loại xe',
        'carrier_number' => 'Số xe',
        'transfer_unit' => 'Đơn vị vận chuyển',
        'from_project' => 'Nơi xuất',
        'from_project_text' => 'Nơi xuất',
        'to_project' => 'Nơi đến',
        'to_project_text' => 'Nơi đến',
        'sent' => 'Thời gian đi',
        'arrived' => 'Thời gian đến',

        'place_id' => 'Nơi mua',
        'requested_quantity' => 'SL yêu cầu',
        'estimate_id' => 'Dự trù tổng',
        'date' => 'Ngày',
        'estimated_quantity' => 'SL dự trù',
        'required_return_date' => 'Ngày cần về',

        'needed_quantity' => 'SL cần mua',
        'borrower_type' => 'Loại đơn vị thuê',
        'borrower_id' => 'Đơn vị thuê',
        'start_date' => 'Ngày bất đầu',

        'email' => 'Email',
        'phone_number' => 'Điện thoại',
        'address' => 'Địa chỉ',
        'tax_code' => 'Mã số thuế',
        'branch' => 'Ngành kinh doanh',

        'roles' => 'Vai trò',
        'password' => 'Mật khẩu',
        'password_confirm' => 'Xác nhận mật khẩu',
        'image' => 'Ảnh',

        'subcontractors' => 'Tên nhà thầu phụ',
        'package' => 'Gói thầu',
        'date_browsing' => 'Ngày trình duyệt',
        'date_approve' => 'Ngày phê duyệt',
        'link' => 'Link',

        'subcontractor_id' => 'Tên nhà thầu phụ',
        'construction_items' => 'Hạng mục thi công',
        'contract_sign_date' => 'Ngày ký hợp đồng',
        'process' => 'Tiến độ',
        'progress' => 'Tiến độ',
        'contract_form' => 'Hình thức hợp đồng',
        'contract_number' => 'Số hợp đồng',
        'contract_annex_value' => 'Giá trị phụ lục hợp đồng',
        'contract_value' => 'Giá trị hợp đồng (chưa VAT) (VND)',
        'contract_value_vat' => 'Giá trị hợp đồng (có VAT) (VND)',
        'value_custody_warranty' => 'Giá Trị tạm giữ bảo hành',
        'content' => 'Nội dung',
        'date_warranty' => 'Thời Gian Bảo Hành',

        'contract_subcontractor_id' => 'Số hợp đồng',
        'payment_date' => 'Ngày thanh toán',
        'type_payment' => 'Loại thanh toán',
        'settlement_value' => 'Giá Trị',

        'representative' => 'Người đại diện',
        'bank' => 'Ngân hàng',
        'account_name' => 'Số tài khoản',
        'account_owner' => 'Chủ tài khoản',

        'from_user' => 'Người tạo',
        'to_user' => 'Bên nhận',
        'assignee_type' => 'Loại bên nhận',
        'due_date' => 'Ngày hoàn thành',
        'overdue_notified' => 'hông báo quá hạn',
        'process_date' => 'Ngày xử lý',
        'taskable_type' => 'Chức năng',
        'taskable_id' => 'ID Chức năng',

        'targetable_type' => 'Chức năng',
        'targetable_id' => 'ID Chức năng',

        'process_user_id' => 'Người xử lý',
        'table_type' => 'Chức năng/Loại bảng',
        'table_id' => 'ID Bảng',
        
        'target' => 'Loại (Dự án/Công ty)',
        'item_id' => 'ID Hàng mục',
        'date_arrival' => 'Ngày đến',
        'supply_id' => 'ID Vật tư',
        'supplies_request_id' => 'ID Yêu cầu vật tư',
        'request_supply_id' => 'ID Yêu cầu vật tư',
        'data_object' => 'Nội dung',

        'supplies_id' => 'ID Vật tư',
        'quantity_needed' => 'SL cần thiết',

        'inventory_id' => 'ID kiểm kê',
        'stocktaking_id' => 'ID Kiểm kê kho',

        'description' => 'Mô tả',
        'purchase_id' => 'ID Mua',
        'devices_id' => 'ID Thiết bị',
        'device_contract_id' => 'ID Yêu cầu thiết bị',
        'total_price' => 'Thành tiền',
        'total' => 'Thành tiền',
    ];

    public function boot()
    {
        $dic = $this->dict;
        // https://pineco.de/laravel-blade-filters/
        Str::macro('translate', function ($value, $lang = 'vn') use ($dic) {
            return isset($dic[$value]) ? $dic[$value] : $value;
        });
    }
}