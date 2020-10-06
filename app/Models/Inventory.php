<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Enums\ReceiptInputStatus;
use App\Enums\ReceiptOutputStatus;
use App\Enums\ReceiptTransferStatus;
use App\Enums\CommonStatus;

class Inventory extends BaseModel
{
    public const INPUT = 1;
    public const OUTPUT = 2;

    protected $fillable = [
        'project_id',
        'supply_id',
        'quantity',
    ];

    protected $attributes = [
        'quantity' => 0
    ];

    public function supply()
    {
        return $this->belongsTo(Supplies::class)->withTrashed();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class, 'inventory_id');
    }

    public function getQuantityInputInPeriod($supplyId, $projectId, $searchOption)
    {
        $input = DB::table('receipt_input_supplies')
            ->join('receipt_inputs', 'receipt_inputs.id', '=', 'receipt_input_supplies.input_id')
            ->where('receipt_input_supplies.supplies_id', '=', $supplyId)
            ->where('receipt_inputs.input_id', '=', $projectId)
            ->where('receipt_inputs.status', '=', ReceiptInputStatus::APPROVED)
            ->whereNull('receipt_inputs.deleted_at');

        if (isset($searchOption['start_date']))
        {
            $input = $input->where('receipt_inputs.date_input', '>=', carbon_date($searchOption['start_date'])->startOfDay());
        }

        if (isset($searchOption['end_date']))
        {
            $input = $input->where('receipt_inputs.date_input', '<=', carbon_date($searchOption['end_date'])->endOfDay());
        }

        $input = (float) $input->sum('receipt_input_supplies.quantity');

        $transferIn = $this->getQuantityTransferInPeriod($supplyId, $projectId, $searchOption, 'in');

        return $input + $transferIn;
    }

    public function getQuantityOutputInPeriod($supplyId, $projectId, $searchOption)
    {
        $output = DB::table('receipt_output_supplies')
            ->join('receipt_outputs', 'receipt_outputs.id', '=', 'receipt_output_supplies.output_id')
            ->where('receipt_output_supplies.supplies_id', '=', $supplyId)
            ->where('receipt_outputs.output_id', '=', $projectId)
            ->where('receipt_outputs.status', '=', ReceiptOutputStatus::APPROVED)
            ->whereNull('receipt_outputs.deleted_at');

        if (isset($searchOption['start_date']))
        {
            $output = $output->where('receipt_outputs.date_output', '>=', carbon_date($searchOption['start_date'])->startOfDay());
        }

        if (isset($searchOption['end_date']))
        {
            $output = $output->where('receipt_outputs.date_output', '<=', carbon_date($searchOption['end_date'])->endOfDay());
        }

        $output = (float) $output->sum('receipt_output_supplies.quantity');

        $transferOut = $this->getQuantityTransferInPeriod($supplyId, $projectId, $searchOption, 'out');

        return $output + $transferOut;
    }

    private function getQuantityTransferInPeriod($supplyId, $projectId, $searchOption, $direction)
    {
        $transfer = DB::table('receipt_transfer_supplies')
            ->join('receipt_transfers', 'receipt_transfers.id', '=', 'receipt_transfer_supplies.input_id')
            ->where('receipt_transfer_supplies.supplies_id', '=', $supplyId)
            ->where('receipt_transfers.'.$direction.'put_id', '=', $projectId)
            ->where('receipt_transfers.status', '=', ReceiptTransferStatus::APPROVED)
            ->whereNull('receipt_transfers.deleted_at');

        if (isset($searchOption['start_date']))
        {
            $transfer = $transfer->where('receipt_transfers.date_transfer', '>=', carbon_date($searchOption['start_date'])->startOfDay());
        }

        if (isset($searchOption['end_date']))
        {
            $transfer = $transfer->where('receipt_transfers.date_transfer', '<=', carbon_date($searchOption['end_date'])->endOfDay());
        }

        $transfer = (float) $transfer->sum('receipt_transfer_supplies.quantity');

        return $transfer;
    }

    public function getStock($supplyId, $projectId, $searchOption, $getOnlyLatest = false)
    {
        $query = DB::table('stocktaking')
            ->join('stocktaking_supplies', 'stocktaking_supplies.stocktaking_id', '=', 'stocktaking.id')
            ->where('stocktaking.status', CommonStatus::APPROVED)
            ->whereNull('stocktaking.deleted_at')
            ->where('stocktaking.project_id', $projectId)
            ->where('stocktaking_supplies.supply_id', $supplyId);

        if (isset($searchOption['start_date']))
        {
            $query = $query->where('stocktaking.created_date', '>=', carbon_date($searchOption['start_date'])->startOfDay());
        }

        if (isset($searchOption['end_date']))
        {
            $query = $query->where('stocktaking.created_date', '<=', carbon_date($searchOption['end_date'])->endOfDay());
        }

        if ($getOnlyLatest)
        {
            $query = $query->latest('stocktaking.created_date');
        }

        $stock = $query->sum('stocktaking_supplies.quantity_actual');
        $diff  = $query->sum('stocktaking_supplies.diff');

        return [$stock, $diff];
    }

    public function adjustQuantities($periodInput, $periodOutput, $diff)
    {
        if ($diff > 0)
        {
            $periodInput += $diff;
        }
        else if ($diff < 0)
        {
            $periodOutput += $diff;
        }

        return [$periodInput, $periodOutput];
    }

    public function getQuantityStartPeriod($supplyId, $projectId, $searchOption)
    {
        if (isset($searchOption['start_date']))
        {
            $searchOption['end_date'] = $searchOption['start_date'];
            unset($searchOption['start_date']);
        }

        $periodInput = $this->getQuantityInputInPeriod($supplyId, $projectId, $searchOption);
        $periodOutput = $this->getQuantityOutputInPeriod($supplyId, $projectId, $searchOption);

        list($stock, $diff) = $this->getStock($supplyId, $projectId, $searchOption, true);

        list($periodInput, $periodOutput) = $this->adjustQuantities($periodInput, $periodOutput, $diff);
        
        return $periodInput - $periodOutput;
    }
}
