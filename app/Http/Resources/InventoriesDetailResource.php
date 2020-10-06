<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ReceiptInputStatus;
use App\Enums\ReceiptOutputStatus;
use App\Enums\ReceiptTransferStatus;
use App\Enums\CommonStatus;
use Carbon\Carbon;

class InventoriesDetailResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $searchOption = $request->get('search_option');
        $mergeSupply  = [];

        if (!empty($this->supply)) 
        {
            $mergeSupply = [
                'supply_id' => $this->supply->id,
                'supply_name' => $this->supply->name,
                'supply_code' => $this->supply->code,
                'unit_name' => $this->supply->unit->name ?? null,
            ];
        }

        $quantityStartPeriod = $this->getQuantityStartPeriod($this->supply_id, $this->project_id, $searchOption);
        $periodInput = $this->getQuantityInputInPeriod($this->supply_id, $this->project_id, $searchOption);
        $periodOutput = $this->getQuantityOutputInPeriod($this->supply_id, $this->project_id, $searchOption);
        list($stock, $diff) = $this->getStock($this->supply_id, $this->project_id, $searchOption, true);
        list($periodInput, $periodOutput) = $this->adjustQuantities($periodInput, $periodOutput, $diff);
        $quantityEndPeriod = $quantityStartPeriod + $periodInput - $periodOutput;

        $searchOption1 = ['end_date' => Carbon::now()->format('d/m/Y')];
        $periodInput1 = $this->getQuantityInputInPeriod($this->supply_id, $this->project_id, $searchOption1);
        $periodOutput1 = $this->getQuantityOutputInPeriod($this->supply_id, $this->project_id, $searchOption1);
        list($stock1, $diff1) = $this->getStock($this->supply_id, $this->project_id, $searchOption1, true);
        list($periodInput1, $periodOutput1) = $this->adjustQuantities($periodInput1, $periodOutput1, $diff1);
        $quantityEndPeriod1 = $periodInput1 - $periodOutput1;

        return [
            $this->mergeWhen($this->whenLoaded('supply'), $mergeSupply),

            'quantity_start_period' => (float) (string) $quantityStartPeriod,
            'quantity_end_period' => (float) (string) $quantityEndPeriod,
            'quantity_input_in_period' => (float) (string) $periodInput,
            'quantity_output_in_period' => (float) (string) $periodOutput,
            'inputted' => (float) (string) $this->getInputted($searchOption),
            'outputted' => (float) (string) $this->getOutputted($searchOption),
            'begin_to_now' => (float) (string) $quantityEndPeriod1,
        ];
    }

    private function getInputted($searchOption)
    {
        $input = (float) DB::table('receipt_input_supplies')
            ->join('receipt_inputs', 'receipt_inputs.id', '=', 'receipt_input_supplies.input_id')
            ->where('receipt_input_supplies.supplies_id', '=', $this->supply_id)
            ->where('receipt_inputs.input_id', '=', $this->project_id)
            ->where('receipt_inputs.status', '=', ReceiptInputStatus::FORWARD)
            ->where('receipt_inputs.date_input', '>=', carbon_date($searchOption['start_date'])->startOfDay())
            ->where('receipt_inputs.date_input', '<=', carbon_date($searchOption['end_date'])->endOfDay())
            ->sum('receipt_input_supplies.quantity');

        return $input;
    }

    private function getOutputted($searchOption)
    {
        $output = (float) DB::table('receipt_output_supplies')
            ->join('receipt_outputs', 'receipt_outputs.id', '=', 'receipt_output_supplies.output_id')
            ->where('receipt_output_supplies.supplies_id', '=', $this->supply_id)
            ->where('receipt_outputs.output_id', '=', $this->project_id)
            ->where('receipt_outputs.status', '=', ReceiptOutputStatus::FORWARD)
            ->where('receipt_outputs.date_output', '>=', carbon_date($searchOption['start_date'])->startOfDay())
            ->where('receipt_outputs.date_output', '<=', carbon_date($searchOption['end_date'])->endOfDay())
            ->sum('receipt_output_supplies.quantity');

        return $output;
    }
}
