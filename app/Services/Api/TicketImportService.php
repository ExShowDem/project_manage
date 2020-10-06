<?php

namespace App\Services\Api;

use App\Enums\TicketImportStatus;
use App\Models\TicketImport;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TicketImportService extends BaseService
{
    public function __construct(TicketImport $invoice)
    {
        $this->model = $invoice;
    }

    private function smoothDataBeforeSave($inputs, $isCreate = true)
    {
        $inputsTicket = Arr::only($inputs, [
            'supplier_id',
            'invoice_id',
            'date_import',
            'number_ticket',
            'reason',
            'contract_number',
        ]);

        $inputsTicket['date_import'] = carbon_date($inputsTicket['date_import']);

        if ($isCreate) {
            $inputsTicket['project_id'] = $inputs['project_id'];
            $inputsTicket['created_by'] = auth()->id();
        }

        if (isset($inputs['action']) && $inputs['action'] === 'complete') {
            $inputsTicket['status'] = TicketImportStatus::APPROVED;
        } else {
            $inputsTicket['status'] = TicketImportStatus::CREATED;
        }

        $attachData = collect($inputs['supplies'])->keyBy('id')->map(function ($supply) {
            return [
                'quantity' => $supply['quantity'] ?? 0,
                'unit_price' => $supply['unit_price'] ?? 0,
                'reason' => $supply['reason'] ?? '',

            ];
        })->toArray();

        return [$inputsTicket, $attachData];
    }

    /**
     * @param $invoiceId
     * @param $data
     * @return array|bool
     */
    public function store($invoiceId, $data)
    {
        try {
            list($inputTicket, $attachData) = $this->smoothDataBeforeSave($data);
            $inputTicket['invoice_id'] = $invoiceId;

            DB::beginTransaction();

            $ticketImport = parent::create($inputTicket);
            $ticketImport->supplies()->attach($attachData);
            DB::commit();

            $importSup = [];
            foreach ($attachData as $supId => $data) {
                $importSup[$supId] = $data['quantity'];
            }

            return [$importSup, $ticketImport];
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }
    }
}
