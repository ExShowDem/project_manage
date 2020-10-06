<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ActionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\ActionLogEvent;
use App\Models\User;

class ActionLogController extends BaseController
{
    private $dict = [
        'CategorySupplies' => 'Nhóm vật tư',
        'CensorSubContractor' => 'Hồ sơ NTP',
        'ContractSubContractor' => 'Hợp đồng NTP',
        'DeviceClearance' => 'Thanh lý thiết bị',
        'DeviceContract' => 'Hóa đơn mua thiết bị',
        'DeviceDetail' => 'Chi tiết thiết bị',
        'DeviceEstimate' => 'Dự trù thiết bị tổng',
        'DeviceInput' => 'Nhập kho thiết bị',
        'DeviceIssuance' => 'Phiếu đề nghị cấp thiết bị',
        'DeviceMonthlyEstimate' => 'Dự trù thiết bị tháng',
        'DevicePurchase' => 'Mua thiết bị',
        'DevicePurchaseRequest' => 'Yêu cầu mua mới thiết bị',
        'DeviceRental' => 'Cho thuê thiết bị',
        'Devices' => 'Thiết bị',
        'DeviceTransfer' => 'Kế hoạch điều chuyển thiết bị',
        'Inventory' => 'Kiểm kê',
        'InventoryLog' => 'Hồ sơ kiểm kê',
        'Invoice' => 'Hoá đơn mua vật tư',
        'Item' => 'Hàng mục',
        'Notification' => 'Thông báo',
        'OfferBuy' => 'Đề xuất mua vật tư',
        'PaymentOrder' => 'Đề nghị thanh toán NTP',
        'Permission' => 'Phép',
        'Plan' => 'Kể hoạch',
        'ProcessLog' => 'Hồ sơ lịch sử',
        'Project' => 'Dự án',
        'ReceiptInput' => 'Nhập kho',
        'ReceiptOutput' => 'Xuất kho',
        'ReceiptTransfer' => 'Chuyển kho',
        'RequestSupply' => 'Yêu cầu vật tư',
        'Resource' => 'Nguồn lực',
        'ResourceType' => 'Loại nguồn lực',
        'Role' => 'Vai trò/Chức vụ',
        'RoleTree' => 'Cây phân cấp vai trò',
        'Stocktaking' => 'Kiểm kê kho',
        'Subcontractor' => 'Nhà thầu phụ',
        'Supplier' => 'Nhà cung cấp',
        'Supplies' => 'Vật tư',
        'SupplyDetail' => 'Chi tiết vật tư',
        'SupplyEachRequest' => 'Vật tư yêu cầu',
        'Task' => 'Công việc cần xử lý',
        'Unit' => 'Đơn vị',
        'User' => 'Tài khoản',
        'UserProject' => 'Tài khoản - Dự án',
    ];

    public function __construct()
    {
        parent::__construct();
        $layout = 'root';

        view()->share('layout', $layout);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect(route('admin.error'));
        }

        $pageSize = config('api.pagination.per_page');
        if (($pageSizeInput = (int)$request->input('page_size')) > 0) {
            $pageSize = min($pageSizeInput, config('api.pagination.max_per_page'));
        }

        $logs = ActionLog::orderBy('time', 'DESC');

        if ($request->has('start_date') && !is_null($request->input('start_date')))
        {
            $formattedStart = Carbon::createFromFormat('m/d/Y', $request->input('start_date'))
                ->startOfDay()
                ->format('Y-m-d H:m:s');
                
            $logs = $logs->where('time', '>', $formattedStart);
        }

        if ($request->has('end_date') && !is_null($request->input('end_date')))
        {
            $formattedEnd = Carbon::createFromFormat('m/d/Y', $request->input('end_date'))
                ->startOfDay()
                ->format('Y-m-d H:m:s');
                
            $logs = $logs->where('time', '<', $formattedEnd);
        }

        if ($request->has('user') && !is_null($request->input('user')))
        {
            $logs = $logs->where('user_id', '=', $request->input('user'));
        }

        if ($request->has('model') && !is_null($request->input('model')))
        {
            $logs = $logs->where('model', 'LIKE', '%' . $request->input('model') . '%');
        }

        $logs = $logs->paginate(30);

        foreach($logs as $log)
        {
            $log['params'] = unserialize($log['params']);
        }

        $models = ActionLog::groupBy('model')->pluck('model');
        $modelsFormatted = [];

        foreach($models as $model)
        {
            $modelName = str_replace('App\\Models\\', '', $model);
            $modelsFormatted[$modelName] = $this->translate($modelName);
        }

        return view('root.action_log.index', ['logs' => $logs, 'users' => User::all(), 'models' => $modelsFormatted]);
    }

    private function translate($word)
    {
        return array_key_exists($word, $this->dict) ? $this->dict[$word] : $word;
    }

    public function restore(Request $request)
    {
        $logId         = $request->input('log_id');
        $log           = ActionLog::where('id', $logId)->first()->getAttributes();
        $log['params'] = unserialize($log['params']);

        switch ($log['method']) 
        {
            case 'update_pivot':
                $entry    = $log['model']::where($log['params']['parent_key_name'], $log['record_id'])->first();
                $syncData = [];

                foreach ($log['params']['before'] as $relation) 
                {
                    $relatedKeyName       = $log['params']['related_key_name'];
                    $relatedId            = $relation->$relatedKeyName;
                    $syncData[$relatedId] = (array) $relation;
                }

                $relation = $log['params']['relation'];
                $entry->$relation()->sync($syncData);

                $newBefore               = $log['params']['after'];
                $newAfter                = $log['params']['before'];
                $log['params']['before'] = $newBefore;
                $log['params']['after']  = $newAfter;
                $entry                   = new ActionLog;
                $entry->user_id          = $log['user_id'];
                $entry->user_name        = $log['user_name'];
                $entry->model            = $log['model'];
                $entry->table_name       = $log['table_name'];
                $entry->method           = $log['method'];
                $entry->record_key_name  = $log['record_key_name'];
                $entry->record_id        = $log['record_id'];
                $entry->params           = serialize($log['params']);

                $entry->save();

                break;
            case 'create':
                $entry = $log['model']::where($log['record_key_name'], $log['record_id'])->first();

                $entry->delete();

                break;
            case 'update':
                $entry   = $log['model']::where($log['record_key_name'], $log['record_id'])->first();
                $updates = array();

                foreach ($log['params']['before'] as $key => $value) 
                {
                    if ($key !== $log['record_key_name'])
                    {
                        $updates[$key] = $value;
                    }
                }

                $entry->update($updates);

                break;
            case 'delete':
                $entry = $log['model']::withTrashed()->find($log['record_id']);

                $entry->deleted_at = null;

                $entry->save();

                break;
            case 'soft_delete':
                $entry = $log['model']::onlyTrashed()->where($log['record_key_name'], $log['record_id'])->first();

                DB::table($log['table_name'])
                    ->where($log['record_key_name'], $log['record_id'])
                    ->update([$entry->getDeletedAtColumn() => null]);

                $newBefore               = $log['params']['after'];
                $newAfter                = $log['params']['before'];
                $log['params']['before'] = $newBefore;
                $log['params']['after']  = $newAfter;

                event(new ActionLogEvent($log['model'], $log['table_name'], 'create', $log['record_key_name'], $log['record_id'], $log['params']));

                break;
            default:
                
        }

        DB::table('action_log')
            ->where('id', $logId)
            ->update(['is_restored' => 1]);

        return redirect('action_log');
    }
}
