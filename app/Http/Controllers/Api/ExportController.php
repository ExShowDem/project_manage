<?php

namespace App\Http\Controllers\Api;

use App\Exports\OfferBuyExport;
use App\Exports\PlanExport;
use App\Exports\ReceiptInputExport;
use App\Exports\ReceiptOutputExport;
use App\Exports\ReceiptTransferExport;
use App\Exports\ItemExport;
use App\Exports\RequestSupplyExport;
use App\Exports\RequestSubcontractExport;
use App\Exports\RequestCensorSubExport;
use App\Exports\RequestContractsubExport;
use App\Exports\RequestPaymentorderExport;
use App\Exports\InvoiceExport;
use App\Exports\DeviceEstimateExport;
use App\Exports\DeviceMonthlyEstimateExport;
use App\Exports\DeviceIssuanceExport;
use App\Exports\DeviceTransferExport;
use App\Exports\StocktakingExport;
use App\Exports\DeviceInputExport;
use App\Exports\DevicePurchaseRequestExport;
use App\Exports\DevicePurchaseExport;
use App\Exports\DeviceContractExport;
use App\Exports\DeviceClearanceExport;
use App\Exports\DeviceRentalExport;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Project;
use App\Models\PaymentOrder;
use App\Models\Subcontractor;
use Carbon\Carbon;

class ExportController extends BaseController
{
    private $ndp = 3;

    private function filterData($request, $defaultData = [])
    {
        return collect($request->all())->map(function ($item) use ($defaultData) {
            return array_merge($defaultData, (array)$item);
        })->toArray();
    }

    private function truncateNdp($number)
    {
        $dps = pow(10, $this->ndp);

        return floor($number*$dps)/$dps;
    }

    public function planPdf(Request $request)
    {
        $dataExport = $this->filterData($request, $this->getFormatData('plan'));

        $pdf = PDF::loadView('admin.export.plan_table', ['dataExport' => $dataExport]);
        return $pdf->download('example.pdf');
    }
    public function planXls(Request $request)
    {
        $dataExport = $this->filterData($request, $this->getFormatData('plan'));

        foreach ($dataExport as $index => $data) {
            $dataExport[$index]['total'] = $data['quantity'] * $data['unit_price_budget'];
            $dataExport[$index]['unit'] = $data['unit']['name'];
        }

        return Excel::download(new PlanExport($dataExport), 'example.xlsx');
    }

    public function itemsPdf(Request $request)
    {
        list($formatExport) = $this->getItems($request, true);

        $pdf = PDF::loadView('admin.export.items_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function itemsXls(Request $request)
    {
        list($formatExport) = $this->getItems($request);

        return Excel::download(new ItemExport($formatExport), 'example.xlsx');
    }
    private function getItems($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('items'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('items.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price_budget']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'                => $data['id'], 
                'name'              => $data['name'], 
                'code'              => $data['code'], 
                'unit'              => $data['unit']['name'],
                'quantity'          => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'unit_price_budget' => $unitPrice, 
                'total'             => $total, 
                'progress'          => number_format($data['progress'], 2, '.', $isPdf ? ',' : '') . ' %', 
                'type_text'         => $data['type_text']
            ]);
        }

        return [$formatExport];
    }

    public function offerBuyPdf(Request $request)
    {
        $dataExport = $this->filterData($request, $this->getFormatData('offer-buy'));

        $pdf = PDF::loadView('admin.export.offer_buys_table', ['dataExport' => $dataExport]);

        return $pdf->download('example.pdf');
    }
    public function offerBuyXls(Request $request)
    {
        $dataExport = $this->filterData($request, $this->getFormatData('offer-buy'));

        foreach ($dataExport as $index => $data) {
            $dataExport[$index]['total'] = $data['quantity'] * $data['unit_price'];
            $dataExport[$index]['unit'] = $data['unit']['name'];
        }

        return Excel::download(new OfferBuyExport($dataExport), 'example.xlsx');
    }

    public function requestSuppliesPdf(Request $request)
    {
        list($formatExport, $requestSupply, $sums) = $this->getRequestSupplies($request, true);

        $pdf = PDF::loadView('admin.export.request_supplies_table', ['dataExport' => $formatExport, 'requestSupply' => $requestSupply, 'sums' => $sums])
            ->setPaper('a4', 'landscape');
       // dd($pdf->stream());

        return $pdf->download('example.pdf');
        //return $pdf->stream();
    }
    public function requestSuppliesXls(Request $request)
    {
        list($formatExport, $requestSupply, $sums) = $this->getRequestSupplies($request);

        $formatExport['requestSupply'] = $requestSupply;
        $formatExport['sums']          = $sums;

        return Excel::download(new RequestSupplyExport($formatExport), 'example.xlsx');
    }
    private function getRequestSupplies($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $item          = $request->get('item');
        $itemName      = $item['item']['name'];
        $project       = $item['to_user_name'];
        $code          = $item['code'];
        $contentOffer  = $item['content_offer'];
        $requestSupply = [
            'itemName'     => $itemName,
            'project'      => $project,
            'code'         => $code,
            'contentOffer' => $contentOffer,
        ];

        $supplies   = new Request($request->only('supplies')['supplies']);
        $dataExport = $this->filterData($supplies, $this->getFormatData('request-supplies'));

        $formatExport = array();

        $sumQuantitySum      = 0;
        $sumEstimateQuantity = 0;
        $sumInputCumulative  = 0;
        $sumQuantity         = 0;

        foreach ($dataExport as $index => $data) 
        {
            $sumEstimateQuantity += $data['estimate_quantity'];
            $sumInputCumulative  += $data['input_cumulative'];
            $sumQuantity         += $data['quantity'];

            $estimateQuantity = $this->truncateNdp($data['estimate_quantity']);
            $quantity         = $this->truncateNdp($data['quantity']);
            $cumulative       = $this->truncateNdp($data['cumulative']);
            $inputCumulative  = $this->truncateNdp($data['input_cumulative']);
            $remainder        = floatval($data['estimate_quantity']) - floatval($data['input_cumulative']);
            $remainder        = $this->truncateNdp($remainder);

            $quantitySum     = floatval($data['quantity']) + floatval($data['input_cumulative']);
            $sumQuantitySum += $quantitySum;
            $quantitySum     = $this->truncateNdp($quantitySum);

            array_push($formatExport, [
                'stt'                  => $index + 1, 
                'name'                 => $data['name'], 
                'unit'                 => $data['unit']['name'], 
                'estimate_quantity'    => number_format($estimateQuantity, $this->ndp, '.', $isPdf ? ',' : ''),
                'quantity_sum'         => number_format($quantitySum, $this->ndp, '.', $isPdf ? ',' : ''),
                //'cumulative'           => number_format($cumulative, $this->ndp, '.', $isPdf ? ',' : ''),
                'input_cumulative'     => number_format($inputCumulative, $this->ndp, '.', $isPdf ? ',' : ''),
                //'remainder'            => number_format($remainder, $this->ndp, '.', $isPdf ? ',' : ''),
                'quantity'             => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''),
                'date_arrival_request' => $data['date_arrival'],
                //'type'                 => $data['type']['name'],
                'note_request'         => $data['note'],                     
            ]);
        }

        $sumQuantitySum      = $this->truncateNdp($sumQuantitySum);
        $sumEstimateQuantity = $this->truncateNdp($sumEstimateQuantity);
        $sumInputCumulative  = $this->truncateNdp($sumInputCumulative);
        $sumQuantity         = $this->truncateNdp($sumQuantity);

        $sumQuantitySum      = number_format($sumQuantitySum, $this->ndp, '.', $isPdf ? ',' : '');
        $sumEstimateQuantity = number_format($sumEstimateQuantity, $this->ndp, '.', $isPdf ? ',' : '');
        $sumInputCumulative  = number_format($sumInputCumulative, $this->ndp, '.', $isPdf ? ',' : '');
        $sumQuantity         = number_format($sumQuantity, $this->ndp, '.', $isPdf ? ',' : '');

        $sums = [
            'sumQuantitySum'      => $sumQuantitySum,
            'sumEstimateQuantity' => $sumEstimateQuantity,
            'sumInputCumulative'  => $sumInputCumulative,
            'sumQuantity'         => $sumQuantity,
        ];

        return [$formatExport, $requestSupply, $sums];
    }

    public function requestSubcontractXls(Request $request)
    {
        $dataExport = $request->all();
        $formatExport = array();
        $formatExport['subcontract'] = $dataExport;

        return Excel::download(new RequestSubcontractExport($formatExport), 'example.xlsx');
    }
    public function requestSubcontractPdf(Request $request)
    {
        $pdf = PDF::loadView('admin.export.ntp_subcontract', ['dataExport' => $request->all()])
            ->setPaper('a4', 'portrait');

        return $pdf->download('example.pdf');
    }

    public function requestCensorXls(Request $request)
    {
        $dataExport = $request->all();

        $formatExport = array();
        $formatExport['censorSub'] = $dataExport;
        $formatExport['censorSub']['subcontractors'] = implode(', ', Subcontractor::find($dataExport['subcontractors'])->pluck('name')->toArray());

        return Excel::download(new RequestCensorSubExport($formatExport), 'example.xlsx');
    }
    public function requestCensorPdf(Request $request)
    {
        $dataExport = $request->all();
        $subcontractors = Subcontractor::find($dataExport['subcontractors'])->pluck('name')->toArray();
        $dataExport['subcontractors'] = implode(', ', $subcontractors);

        $pdf = PDF::loadView('admin.export.ntp_censor_sub', ['dataExport' => $dataExport])
            ->setPaper('a4', 'portrait');

        return $pdf->download('example.pdf');
    }

    public function requestContractsubPdf(Request $request)
    {
        list($dataExport) = $this->getContractsub($request, true);

        $pdf = PDF::loadView('admin.export.ntp_contract_sub', ['dataExport' => $dataExport])
            ->setPaper('a4', 'portrait');

        return $pdf->download('example.pdf');
    }
    public function requestContractsubXls(Request $request)
    {
        list($dataExport) = $this->getContractsub($request);
        $formatExport = array();
        $formatExport['contractSub'] = $dataExport;
        return Excel::download(new RequestContractsubExport($formatExport), 'example.xlsx');
    }
    private function getContractsub($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $canSeePrice   = auth()->user()->can('contract_sub.see_price');
        $formattedData = $request->all();

        if (!$canSeePrice)
        {
            $formattedData['contract_annex_value']   = '*****';
            $formattedData['contract_value']         = '*****';
            $formattedData['contract_value_vat']     = '*****';
            $formattedData['value_custody_warranty'] = '*****';
        }

        return [$formattedData];
    }

    public function requestPaymentorderPdf(Request $request)
    {
        list($formatExport, $paymentOrder) = $this->getPaymentOrderInfo($request, true);

        $contractSub   = $paymentOrder->contractSub;
        $subcontractor = $paymentOrder->subContractor;

        $pdf = PDF::loadView('admin.export.payment_order', ['dataExport' => $formatExport, 'paymentOrder' => $paymentOrder, 'contractSub' => $contractSub, 'subcontractor' => $subcontractor])
            ->setPaper('a4', 'portrait');

        return $pdf->download('example.pdf');
    }
    public function requestPaymentorderXls(Request $request)
    {
        list($formatExport, $paymentOrder) = $this->getPaymentOrderInfo($request);
        $formatExport['paymentOrder'] = $paymentOrder;

        return Excel::download(new RequestPaymentorderExport($formatExport), 'example.xlsx');
    }
    private function getPaymentOrderInfo($request, $isPdf = false)
    {
        $this->ndp = 0;
        
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $canSeePrice  = auth()->user()->can('payment_order.see_price');

        $dataExportContract = $request->all();

        $paymentOrder = PaymentOrder::find($dataExportContract['id']);
        $dataExport   = PaymentOrder::where('contract_subcontractor_id', $dataExportContract['contract_sub']['id'])->get();

        if ($canSeePrice)
        {
            $settleVal = $paymentOrder->settlement_value;
            $settleVal = $this->truncateNdp($settleVal);
            $settleVal = number_format($settleVal, $this->ndp, '.', $isPdf ? ',' : '');
            $paymentOrder->settlement_value = $settleVal;
        }
        else
        {
            $paymentOrder->settlement_value = '*****';
        }

        $formatExport = array();

        if($paymentOrder->type_payment == 1)
        {
            if ($canSeePrice)
            {
                $contractVal = str_replace(',', '', $dataExportContract['contract_value']);
                $contractVal = floatval($contractVal);
                $contractVal = $this->truncateNdp($contractVal);
                $contractVal = number_format($contractVal, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $contractVal = '*****';
            }

            array_push($formatExport, [
                'stt' => 1,
                'content' => 'Giá trị hợp đồng',
                'value' => $contractVal,
                'code' => $dataExportContract['contract_sub']['contract_number']
            ]);
            array_push($formatExport, [
                'stt' => 2,
                'content' => 'Giá trị quyết toán',
                'value' => '',
                'code' => ''
            ]);

            $value_paid = 0;

            foreach ($dataExport as $index => $data) 
            {
                $value_paid += $data->settlement_value;
            }

            if ($canSeePrice)
            {
                $paidVal = floatval(str_replace(',', '', $value_paid));
                $paidVal = $this->truncateNdp($paidVal);
                $paidVal = number_format($paidVal, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $paidVal = '*****';
            }

            array_push($formatExport, [
                'stt' => 3,
                'content' => 'Giá trị đã thanh toán',
                'value' => $paidVal,
                'code' => ''
            ]);
            array_push($formatExport, [
                'stt' => 3.1,
                'content' => 'Tạm ứng hợp đồng',
                'value' => '',
                'code' => ''
            ]);

            $arrayKeys = array_keys((array) $dataExport);
            $lastArrayKey = array_pop($arrayKeys);
            $stt_prefix = "3.";
            $dataExportArray = (array) $dataExport;
            usort($dataExportArray, function($a, $b) {return strcmp($a->payment_date, $b->payment_date);});

            foreach ($dataExport as $index => $data) 
            {
                $stt = $index + 1;
                $stt_next = $index+2;

                if ($canSeePrice)
                {
                    $settleVal1 = floatval($data->settlement_value);
                    $settleVal1 = $this->truncateNdp($settleVal1);
                    $settleVal1 = number_format($settleVal1, $this->ndp, '.', $isPdf ? ',' : '');
                }
                else
                {
                    $settleVal1 = '*****';
                }

                array_push($formatExport, [
                    'stt' => $stt_prefix . $stt_next,
                    'content' => 'Giá trị thanh toán đợt ' . $stt,
                    'value' => $settleVal1,
                    'code' => $data->code
                ]);
            }

            if ($canSeePrice)
            {
                $settleVal2 = floatval(str_replace(',', '', $dataExportContract['settlement_value']));
                $settleVal2 = $this->truncateNdp($settleVal2);
                $settleVal2 = number_format($settleVal2, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $settleVal2 = '*****';
            }

            array_push($formatExport, [
                'stt' => 4,
                'content' => 'Giá trị thanh toán đợt này',
                'value' => $settleVal2,
                'code' => ''
            ]);

            if ($canSeePrice)
            {
                $contractVal1 = floatval(str_replace(',', '', $dataExportContract['contract_annex_value']));
                $contractVal1 = $this->truncateNdp($contractVal1);
                $contractVal1 = number_format($contractVal1, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $contractVal1 = '*****';
            }

            array_push($formatExport, [
                'stt' => 5,
                'content' => 'Giá trị còn lại phải thanh toán',
                'value' => $contractVal1,
                'code' => ''
            ]);
        }
        else if($paymentOrder->type_payment == 2) 
        {
            if ($canSeePrice)
            {
                $contractVal2 = floatval(str_replace(',', '', $dataExportContract['contract_value']));
                $contractVal2 = $this->truncateNdp($contractVal2);
                $contractVal2 = number_format($contractVal2, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $contractVal2 = '*****';
            }

            array_push($formatExport, [
                'stt' => 1,
                'content' => 'Giá trị hợp đồng',
                'value' => $contractVal2,
                'code' => $dataExportContract['contract_sub']['contract_number']
            ]);

            $value_paid = 0;

            foreach ($dataExport as $index => $data) 
            {
                $value_paid += $data->settlement_value;
            }

            if ($canSeePrice)
            {
                $paidVal1 = floatval($value_paid);
                $paidVal1 = $this->truncateNdp($paidVal1);
                $paidVal1 = number_format($paidVal1, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $paidVal1 = '*****';
            }

            array_push($formatExport, [
                'stt' => 2,
                'content' => 'Giá trị đã thanh toán',
                'value' => $paidVal1,
                'code' => ''
            ]);

            $arrayKeys = array_keys((array) $dataExport);
            $lastArrayKey = array_pop($arrayKeys);
            $stt_prefix = "2.";

            foreach ($dataExport as $index => $data) 
            {
                $stt = $index + 1;

                if ($canSeePrice)
                {
                    $settleVal3 = floatval($data->settlement_value);
                    $settleVal3 = $this->truncateNdp($settleVal3);
                    $settleVal3 = number_format($settleVal3, $this->ndp, '.', $isPdf ? ',' : '');
                }
                else
                {
                    $settleVal3 = '*****';
                }

                array_push($formatExport, [
                    'stt' => $stt_prefix . $stt,
                    'content' => 'Đợt ' . $stt,
                    'value' => $settleVal3,
                    'code' => $data->code
                ]);
            }

            if ($canSeePrice)
            {
                $settleVal4 = floatval(str_replace(',', '', $dataExportContract['settlement_value']));
                $settleVal4 = $this->truncateNdp($settleVal4);
                $settleVal4 = number_format($settleVal4, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $settleVal4 = '*****';
            }

            array_push($formatExport, [
                'stt' => 3,
                'content' => 'Giá trị thanh toán đợt này',
                'value' => $settleVal4,
                'code' => ''
            ]);

            if ($canSeePrice)
            {
                $contractVal3 = floatval(str_replace(',', '', $dataExportContract['contract_annex_value']));

                $contractVal3 = $this->truncateNdp($contractVal3);
                $contractVal3 = number_format($contractVal3, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $contractVal3 = '*****';
            }

            array_push($formatExport, [
                'stt' => 4,
                'content' => 'Giá trị còn lại phải thanh toán',
                'value' => $contractVal3,
                'code' => ''
            ]);
        }
        else if($paymentOrder->type_payment == 3)
        {
            if ($canSeePrice)
            {
                $contractVal4 = floatval(str_replace(',', '', $dataExportContract['contract_sub']['contract_value_vat']));
                $contractVal4 = $this->truncateNdp($contractVal4);
                $contractVal4 = number_format($contractVal4, $this->ndp, '.', $isPdf ? ',' : '');
            }
            else
            {
                $contractVal4 = '*****';
            }

            array_push($formatExport, [
                'stt' => 1,
                'content' => 'Giá trị hợp đồng',
                'value' => $contractVal4,
                'code' => $dataExportContract['contract_sub']['contract_number']
            ]);
            array_push($formatExport, [
                'stt' => 2,
                'content' => 'Giá trị tạm ứng hợp đồng',
                'value' => '',
                'code' => ''
            ]);

            array_push($formatExport, [
                'stt' => 3,
                'content' => 'Giá trị còn lại phải thanh toán',
                'value' => '',
                'code' => ''
            ]);
        }
        
        $paymentOrder->payment_date = Carbon::parse($paymentOrder->payment_date)->isoFormat('[ngày] D MMMM [năm] YYYY');

        return [$formatExport, $paymentOrder];
    }

    public function invoicePdf(Request $request)
    {
        list($formatExport) = $this->getInvoice($request, true);

        $pdf = PDF::loadView('admin.export.invoices_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function invoiceXls(Request $request)
    {
        list($formatExport) = $this->getInvoice($request);

        return Excel::download(new InvoiceExport($formatExport), 'example.xlsx');
    }
    private function getInvoice($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('invoice'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('invoices.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');

                $discount = number_format($data['discount'], 2, '.', $isPdf ? ',' : '');
                $tax      = number_format($data['tax'], 2, '.', $isPdf ? ',' : '');

                $unitPrice = $this->truncateNdp($data['unit_price']);
                $otherCost = $this->truncateNdp($data['other_cost']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                    $otherCost = number_format($otherCost, $this->ndp, '.', ',');
                }
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
                $discount  = '*****';
                $otherCost = '*****';
                $tax       = '*****';
            }

            array_push($formatExport, [
                'id'         => $data['id'], 
                'name'       => $data['name'], 
                'code'       => $data['code'], 
                'unit'       => $data['unit']['name'],
                'quantity'   => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'unit_price' => $unitPrice,
                'total'      => $total, 
                'discount'   => $discount . ' %', 
                'other_cost' => $otherCost,
                'tax'        => $tax . ' %',
            ]);
        }

        return [$formatExport];
    }

    public function receiptInputPdf(Request $request)
    {
        list($formatExport) = $this->getReceiptInput($request, true);

        $pdf = PDF::loadView('admin.export.receipt_input_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function receiptInputXls(Request $request)
    {
        list($formatExport) = $this->getReceiptInput($request);

        return Excel::download(new ReceiptInputExport($formatExport), 'example.xlsx');
    }
    private function getReceiptInput($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('receipt-input'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('receipt_inputs.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity         = $this->truncateNdp($data['quantity']);
            $originalQuantity = $this->truncateNdp($data['original_quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'                => $data['id'], 
                'name'              => $data['name'], 
                'code'              => $data['code'], 
                'unit'              => $data['unit']['name'],
                'original_quantity' => number_format($originalQuantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'quantity'          => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'difference_reason' => $data['difference_reason'], 
                'unit_price'        => floor($data['unit_price']*1000)/1000,
                'total'             => $total, 
            ]);
        }

        return [$formatExport];
    }

    public function receiptOutputPdf(Request $request)
    {
        list($formatExport) = $this->getReceiptOutput($request, true);

        $pdf = PDF::loadView('admin.export.receipt_output_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function receiptOutputXls(Request $request)
    {
        list($formatExport) = $this->getReceiptOutput($request);

        return Excel::download(new ReceiptOutputExport($formatExport), 'example.xlsx');
    }
    private function getReceiptOutput($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('receipt-output'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('receipt_outputs.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantityStock       = $this->truncateNdp($data['quantity_in_stock']);
            $accumulatedQuantity = $this->truncateNdp($data['accumulated_quantity']);
            $needed              = floatval($data['quantity_needed']) - floatval($data['quantity']);
            $needed              = $this->truncateNdp($needed);
            $quantity            = $this->truncateNdp($data['quantity']);

            if ($isPdf)
            {
                $quantityStock       = number_format($quantityStock, $this->ndp, '.', ',');
                $accumulatedQuantity = number_format($accumulatedQuantity, $this->ndp, '.', ',');
                $needed              = number_format($needed, $this->ndp, '.', ',');
                $quantity            = number_format($quantity, $this->ndp, '.', ',');
            }

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                "id"                   => $data['id'], 
                "code"                 => $data['code'], 
                "name"                 => $data['name'], 
                "unit"                 => $data['unit']['name'],
                "quantity_in_stock"    => $quantityStock, 
                "accumulated_quantity" => $accumulatedQuantity, 
                "needed"               => $needed, 
                "quantity"             => $quantity, 
                "item_name"            => $data['item_name'],
                "unit_price"           => $unitPrice, 
                "total"                => $total, 
            ]);
        }

        return [$formatExport];
    }

    public function receiptTransferPdf(Request $request)
    {
        list($formatExport) = $this->getReceiptTransfer($request, true);

        $pdf = PDF::loadView('admin.export.receipt_transfer_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function receiptTransferXls(Request $request)
    {
        list($formatExport) = $this->getReceiptTransfer($request);

        return Excel::download(new ReceiptTransferExport($formatExport), 'example.xlsx');
    }
    private function getReceiptTransfer($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('receipt-transfer'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('receipt_transfers.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $input    = $this->truncateNdp($data['input']);
            $output   = $this->truncateNdp($data['output']);
            $quantity = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                "id"         => $data['id'], 
                "code"       => $data['code'], 
                "name"       => $data['name'], 
                "unit"       => $data['unit']['name'],
                "input"      => number_format($input, $this->ndp, '.', $isPdf ? ',' : ''), 
                "output"     => number_format($output, $this->ndp, '.', $isPdf ? ',' : ''),  
                "quantity"   => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''),  
                "unit_price" => $unitPrice, 
                "total"      => $total, 
            ]);
        }

        return [$formatExport];
    }

    public function deviceEstimatePdf(Request $request)
    {
        list($formatedData, $projectName) = $this->getDeviceEstimate($request, true);

        $pdf = PDF::loadView('admin.export.device_estimate_table', ['dataExport' => $formatedData, 'project' => $projectName])
            ->setPaper('a4', 'landscape');
        //dd($pdf);

        return $pdf->download('example.pdf');
    }
    public function deviceEstimateXls(Request $request)
    {
        list($formatedData, $projectName) = $this->getDeviceEstimate($request);

        $formatedData['project'] = $projectName;

        return Excel::download(new DeviceEstimateExport($formatedData), 'example.xlsx');
    }
    private function getDeviceEstimate($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $requestId    = $request->get('project');
        $project      = Project::findOrFail($requestId);
        $deviceReq    = new Request($request->only('devices')['devices']);
        $dataExport   = $this->filterData($deviceReq, $this->getFormatData('device-estimate'));
        $formatedData = [];
        $canSeePrice  = auth()->user()->can('device_estimates.see_price');
        //echo "<pre>";print_r($request->all());exit;
        foreach ($dataExport as $index => $data) 
        {
            $mass    = $this->truncateNdp($data['mass']);
            $mass1   = $this->truncateNdp($data['mass1']);
            $rent    = $this->truncateNdp($data['rent']);
            $mass2   = $this->truncateNdp($data['mass2']);

            if ($canSeePrice)
            {
                $price     = $this->truncateNdp($data['price']);
                $rentPrice = $this->truncateNdp($data['rent_price']);
                $estimatedUnitPrice = $this->truncateNdp($data['estimated_unit_price']);

                if ($isPdf)
                {
                    $price     = number_format($price, $this->ndp, '.', ',');
                    $rentPrice = number_format($rentPrice, $this->ndp, '.', ',');
                    $estimatedUnitPrice = number_format($estimatedUnitPrice, $this->ndp, '.', ',');
                }

                $totalPrice = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $price      = '*****';
                $rentPrice  = '*****';
                $estimatedUnitPrice = '*****';
                $totalPrice = '*****';
            }

            $formatedData[$index]['stt']   = $index + 1;
            $formatedData[$index]['name']  = $data['name'];
            $formatedData[$index]['unit']  = $data['unit']['name'];
            $formatedData[$index]['size']  = '';
            $formatedData[$index]['mass']  = number_format($mass, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['mass1'] = number_format($mass1, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['price'] = $price;
            $formatedData[$index]['rent']  = number_format($rent, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['rent_price'] = $rentPrice;
            $formatedData[$index]['mass2']      = number_format($mass2, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['estimated_unit_price'] = $estimatedUnitPrice;
            $formatedData[$index]['input_time']  = $data['input_time'];
            $formatedData[$index]['return_time'] = $data['return_time'];
            $formatedData[$index]['days_used']   = $data['days_used'];
            $formatedData[$index]['total_price'] = $totalPrice;
            $formatedData[$index]['note']        = $data['note'];
        }

        $projectName = $project->name;

        return [$formatedData, $projectName];
    }

    public function deviceMonthlyEstimatePdf(Request $request)
    {
        list($formatedData, $projectName, $dates) = $this->getDeviceMonthlyEstimate($request, true);

        $pdf = PDF::loadView('admin.export.device_monthly_estimate_table', ['dataExport' => $formatedData, 'project' => $projectName, 'batches' => $dates])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceMonthlyEstimateXls(Request $request)
    {
        list($formatedData, $projectName, $dates) = $this->getDeviceMonthlyEstimate($request);
        
        $formatedData['project'] = $projectName;
        $formatedData['dates']   = $dates;

        return Excel::download(new DeviceMonthlyEstimateExport($formatedData), 'example.xlsx');
    }
    private function getDeviceMonthlyEstimate($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $requestId    = $request->get('project');
        $project      = Project::findOrFail($requestId);
        $deviceReq    = new Request($request->only('devices')['devices']);
        $dataExport   = $this->filterData($deviceReq, $this->getFormatData('device-monthly-estimate'));
        $formatedData = [];

        foreach ($dataExport as $index => $data) 
        {
            $totalQuantity       = $this->truncateNdp($data['total_quantity']);
            $accumulatedQuantity = $this->truncateNdp($data['accumulated_quantity']);
            $quantity  = $this->truncateNdp($data['quantity']);
            $quantity1 = $this->truncateNdp($data['quantity1']);
            $quantity2 = $this->truncateNdp($data['quantity2']);
            $quantity3 = $this->truncateNdp($data['quantity3']);
            $quantity4 = $this->truncateNdp($data['quantity4']);
            $quantity5 = $this->truncateNdp($data['quantity5']);
            $quantity6 = $this->truncateNdp($data['quantity6']);

            $formatedData[$index]['stt']  = $index + 1;
            $formatedData[$index]['name'] = $data['name'];
            $formatedData[$index]['unit'] = $data['unit']['name'];
            $formatedData[$index]['type'] = $data['type_text'];
            $formatedData[$index]['total_quantity'] = number_format($totalQuantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['accumulated_quantity'] = number_format($accumulatedQuantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity']  = number_format($quantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity1'] = number_format($quantity1, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity2'] = number_format($quantity2, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity3'] = number_format($quantity3, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity4'] = number_format($quantity4, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity5'] = number_format($quantity5, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity6'] = number_format($quantity6, $this->ndp, '.', $isPdf ? ',' : '');
        }

        $projectName = $project->name;
        $dates       = [];

        for ($i = 1; $i <= 6; $i++) 
        {
            $date      = $dataExport[0]['batch'.$i];
            $dates[$i] = $date ?? '_/_/_'; 
        }

        return [$formatedData, $projectName, $dates];
    }

    public function deviceIssuancePdf(Request $request)
    {
        list($formatedData, $projectName) = $this->getDeviceIssuance($request, true);

        $pdf = PDF::loadView('admin.export.device_issuance_table', ['dataExport' => $formatedData, 'project' => $projectName])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceIssuanceXls(Request $request)
    {
        list($formatedData, $projectName) = $this->getDeviceIssuance($request);

        $formatedData['project'] = $projectName;

        return Excel::download(new DeviceIssuanceExport($formatedData), 'example.xlsx');
    }
    private function getDeviceIssuance($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $requestId    = $request->get('project');
        $project      = Project::findOrFail($requestId);
        $deviceReq    = new Request($request->only('devices')['devices']);
        $dataExport   = $this->filterData($deviceReq, $this->getFormatData('device-issuance'));
        $formatedData = [];

        foreach ($dataExport as $index => $data) 
        {
            $accumulated = $this->truncateNdp($data['accumulated_quantity']);
            $total       = $this->truncateNdp($data['total_quantity']);
            $monthly     = $this->truncateNdp($data['monthly_estimated_quantity']);
            $quantity    = $this->truncateNdp($data['quantity']);
            $quantity1   = $this->truncateNdp($data['quantity1']);

            $formatedData[$index]['stt']  = $index + 1;
            $formatedData[$index]['name'] = $data['name'];
            $formatedData[$index]['unit'] = $data['unit']['name'];
            $formatedData[$index]['size'] = '';
            $formatedData[$index]['accumulated_quantity'] = number_format($accumulated, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['total_quantity'] = number_format($total, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['monthly_estimated_quantity'] = number_format($monthly, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity'] = number_format($quantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['supply_date']  = $data['supply_date'];
            $formatedData[$index]['return_date']  = $data['return_date'];
            $formatedData[$index]['supply_date1'] = $data['supply_date1'];
            $formatedData[$index]['quantity1'] = number_format($quantity1, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['surpassed'] = $data['has_surpassed_estimates_label'];
        }

        $projectName = $project->name;

        return [$formatedData, $projectName];
    }

    public function deviceTransferPdf(Request $request)
    {
        list($formatedData) = $this->getDeviceTransfer($request, true);

        $pdf = PDF::loadView('admin.export.device_transfer_table', ['dataExport' => $formatedData])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceTransferXls(Request $request)
    {
        list($formatedData) = $this->getDeviceTransfer($request);

        return Excel::download(new DeviceTransferExport($formatedData), 'example.xlsx');
    }
    private function getDeviceTransfer($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-transfer'));
        $formatedData = [];

        foreach ($dataExport as $index => $data) 
        {
            $quantity       = $this->truncateNdp($data['quantity']);
            $issuedQuantity = $this->truncateNdp($data['issued_quantity']);

            $formatedData[$index]['stt']  = $index + 1;
            $formatedData[$index]['name'] = $data['name'];
            $formatedData[$index]['unit'] = $data['unit']['name'];
            $formatedData[$index]['issued_quantity'] = number_format($issuedQuantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['quantity'] = number_format($quantity, $this->ndp, '.', $isPdf ? ',' : '');
            $formatedData[$index]['carrier_type'] = $data['carrier_type'];
            $formatedData[$index]['carrier_number'] = $data['carrier_number'];
            $formatedData[$index]['transfer_unit'] = $data['transfer_unit'];
            $formatedData[$index]['in'] =  '';
            $formatedData[$index]['ex'] =  '';
            $formatedData[$index]['trans'] =  '';
            $formatedData[$index]['from_project'] = Project::find($data['from_project'])->name;
            $formatedData[$index]['sent'] = $data['sent'];
            $formatedData[$index]['arrived'] = $data['arrived'];
            $formatedData[$index]['to_project'] = Project::find($data['to_project'])->name;
        }

        return [$formatedData];
    }

    public function receiptStocktakingPdf(Request $request)
    {
        list($formatExport) = $this->getReceiptStocktaking($request, true);

        $pdf = PDF::loadView('admin.export.stocktaking_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function receiptStocktakingXls(Request $request)
    {
        list($formatExport) = $this->getReceiptStocktaking($request);

        return Excel::download(new StocktakingExport($formatExport), 'example.xlsx');
    }
    private function getReceiptStocktaking($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('stocktaking'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('stocktaking.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantitySystem = $this->truncateNdp($data['quantity_system']);
            $quantityActual = $this->truncateNdp($data['quantity_actual']);
            $difference     = floatval($data['quantity_actual']) - floatval($data['quantity_system']);
            $difference     = $this->truncateNdp($difference);

            if ($canSeePrice)
            {
                $price = $this->truncateNdp($data['price']);

                if ($isPdf)
                {
                    $price = number_format($price, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $price = '*****';
                $total = '*****';
            }

            array_push($formatExport, [
                "id"              => $data['id'], 
                "name"            => $data['name'], 
                "unit"            => $data['unit']['name'],
                "price"           => $price, 
                "quantity_system" => number_format($quantitySystem, $this->ndp, '.', $isPdf ? ',' : ''),
                "quantity_actual" => number_format($quantityActual, $this->ndp, '.', $isPdf ? ',' : ''),
                "difference"      => number_format($difference, $this->ndp, '.', $isPdf ? ',' : ''),
                "reason"          => $data['reason'],
                "total"           => $total, 
            ]);
        }

        return [$formatExport];
    }

    public function deviceInputPdf(Request $request)
    {
        list($formatExport) = $this->getDeviceInput($request, true);

        $pdf = PDF::loadView('admin.export.device_input_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceInputXls(Request $request)
    {
        list($formatExport) = $this->getDeviceInput($request);

        return Excel::download(new DeviceInputExport($formatExport), 'example.xlsx');
    }
    private function getDeviceInput($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-input'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('device_input.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $existingQuantity = $this->truncateNdp($data['existing_quantity']);
            $quantity         = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                    "id"                => $data['id'],
                    "code"              => $data['code'],
                    "name"              => $data['name'],
                    "unit"              => $data['unit']['name'],
                    "existing_quantity" => number_format($existingQuantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                    "quantity"          => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                    "unit_price"        => $unitPrice, 
                    "total"             => $total, 
                    "note"              => $data['note'],
            ]);
        }

        return [$formatExport];
    }

    public function devicePurchaseRequestPdf(Request $request)
    {
        list($formatExport) = $this->getDevicePurchaseRequest($request, true);

        $pdf = PDF::loadView('admin.export.device_purchase_request_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function devicePurchaseRequestXls(Request $request)
    {
        list($formatExport) = $this->getDevicePurchaseRequest($request);

        return Excel::download(new DevicePurchaseRequestExport($formatExport), 'example.xlsx');
    }
    private function getDevicePurchaseRequest($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-purchase-request'));
        $formatExport = array();

        foreach ($dataExport as $index => $data) 
        {
            $inputCum  = $this->truncateNdp($data['input_cumulative']);
            $estimated = $this->truncateNdp($data['estimated_quantity']);
            $quantity  = $this->truncateNdp($data['quantity']);

            array_push($formatExport, [
                'id'                   => $data['id'],
                'code'                 => $data['code'],
                'name'                 => $data['name'],
                'unit'                 => $data['unit']['name'],
                'input_cumulative'     => number_format($inputCum, $this->ndp, '.', $isPdf ? ',' : ''),
                'estimated_quantity'   => number_format($estimated, $this->ndp, '.', $isPdf ? ',' : ''),
                'quantity'             => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''),
                'required_return_date' => $data['required_return_date'],
                'note'                 => $data['note'],
            ]);
        }

        return [$formatExport];
    }

    public function devicePurchasePdf(Request $request)
    {
        list($formatExport) = $this->getDevicePurchase($request, true);

        $pdf = PDF::loadView('admin.export.device_purchase_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function devicePurchaseXls(Request $request)
    {
        list($formatExport) = $this->getDevicePurchase($request);

        return Excel::download(new DevicePurchaseExport($formatExport), 'example.xlsx');
    }
    private function getDevicePurchase($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-purchase'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('device_purchase.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $requested = $this->truncateNdp($data['requested_quantity']);
            $quantity  = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'                 => $data['id'],
                'code'               => $data['code'],
                'name'               => $data['name'],
                'unit'               => $data['unit']['name'],
                'requested_quantity' => number_format($requested, $this->ndp, '.', $isPdf ? ',' : ''),  
                'quantity'           => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'unit_price'         => $unitPrice, 
                'total'              => $total, 
                'note'               => $data['note'],
            ]);
        }

        return [$formatExport];
    }

    public function deviceContractPdf(Request $request)
    {
        list($formatExport) = $this->getDeviceContract($request, true);

        $pdf = PDF::loadView('admin.export.device_contract_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceContractXls(Request $request)
    {
        list($formatExport) = $this->getDeviceContract($request);

        return Excel::download(new DeviceContractExport($formatExport), 'example.xlsx');
    }
    private function getDeviceContract($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-contract'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('device_contract.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity = $this->truncateNdp($data['quantity']);
            $needed   = $this->truncateNdp($data['needed_quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'              => $data['id'], 
                'name'            => $data['name'], 
                'code'            => $data['code'], 
                'unit'            => $data['unit']['name'],
                'needed_quantity' => number_format($needed, $this->ndp, '.', $isPdf ? ',' : ''), 
                'quantity'        => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''), 
                'unit_price'      => $unitPrice,
                'total'           => $total, 
                'note'            => $data['note'],
            ]);
        }

        return [$formatExport];
    }

    public function deviceClearancePdf(Request $request)
    {
        list($formatExport) = $this->getDeviceClearance($request, true);

        $pdf = PDF::loadView('admin.export.device_clearance_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceClearanceXls(Request $request)
    {
        list($formatExport) = $this->getDeviceClearance($request);

        return Excel::download(new DeviceClearanceExport($formatExport), 'example.xlsx');
    }
    private function getDeviceClearance($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-clearance'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('device_clearance.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity = $this->truncateNdp($data['quantity']);
            $existing = $this->truncateNdp($data['existing_quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'                => $data['id'], 
                'name'              => $data['name'], 
                'code'              => $data['code'], 
                'unit'              => $data['unit']['name'],
                'quantity'          => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''),
                'existing_quantity' => number_format($existing, $this->ndp, '.', $isPdf ? ',' : ''),
                'unit_price'        => $unitPrice,
                'total'             => $total, 
            ]);
        }

        return [$formatExport];
    }

    public function deviceRentalPdf(Request $request)
    {
        list($formatExport) = $this->getDeviceRental($request, true);

        $pdf = PDF::loadView('admin.export.device_rental_table', ['dataExport' => $formatExport])
            ->setPaper('a4', 'landscape');

        return $pdf->download('example.pdf');
    }
    public function deviceRentalXls(Request $request)
    {
        list($formatExport) = $this->getDeviceRental($request);

        return Excel::download(new DeviceRentalExport($formatExport), 'example.xlsx');
    }
    private function getDeviceRental($request, $isPdf = false)
    {
        $isPdf = true; // Force , as 1000 seperator on excel exports
        
        $dataExport   = $this->filterData($request, $this->getFormatData('device-rental'));
        $formatExport = array();
        $canSeePrice  = auth()->user()->can('device_rental.see_price');

        foreach ($dataExport as $index => $data) 
        {
            $quantity = $this->truncateNdp($data['quantity']);

            if ($canSeePrice)
            {
                $unitPrice = $this->truncateNdp($data['unit_price']);

                if ($isPdf)
                {
                    $unitPrice = number_format($unitPrice, $this->ndp, '.', ',');
                }

                $total = number_format($data['total'], 0, '.', $isPdf ? ',' : '');
            }
            else
            {
                $unitPrice = '*****';
                $total     = '*****';
            }

            array_push($formatExport, [
                'id'         => $data['id'], 
                'name'       => $data['name'], 
                'code'       => $data['code'], 
                'unit'       => $data['unit']['name'],
                'quantity'   => number_format($quantity, $this->ndp, '.', $isPdf ? ',' : ''),
                'unit_price' => $unitPrice,
                'total'      => $total, 
            ]);
        }

        return [$formatExport];
    }

    /**
     * @param $case
     * @return array
     */
    private function getFormatData($case)
    {
        switch (strtoupper($case)) 
        {
            case 'DEVICE-INPUT':
                return [
                    "id"                => '',
                    "code"              => '',
                    "name"              => '',
                    "unit"              => '',
                    "existing_quantity" => 0,
                    "quantity"          => 0,
                    "unit_price"        => 0,
                    "total"             => 0,
                    "note"              => '',
                ];
                break;
            case 'DEVICE-PURCHASE-REQUEST':
                return [
                    'id'                   => '',
                    'code'                 => '',
                    'name'                 => '',
                    'unit'                 => '',
                    'input_cumulative'     => 0,
                    'estimated_quantity'   => 0,
                    'quantity'             => 0,
                    'required_return_date' => '',
                    'note'                 => '',
                ];
                break;
            case 'DEVICE-PURCHASE':
                return [
                    'id'                 => '',
                    'code'               => '',
                    'name'               => '',
                    'unit'               => '',
                    'requested_quantity' => 0,
                    'quantity'           => 0,
                    'unit_price'         => 0,
                    'total'              => 0,
                    'note'               => '',
                ];
                break;
            case 'DEVICE-CONTRACT':
                return [
                    'id'              => '',
                    'name'            => '',
                    'code'            => '',
                    'unit'            => '',
                    'needed_quantity' => 0,
                    'quantity'        => 0,
                    'unit_price'      => 0,
                    'total'           => 0,
                    'note'            => '',
                ];
                break;
            case 'DEVICE-CLEARANCE':
                return [
                    'id'                => '',
                    'name'              => '',
                    'code'              => '',
                    'unit'              => '',
                    'quantity'          => 0,
                    'existing_quantity' => 0,
                    'unit_price'        => 0,
                    'total'             => 0,
                ];
                break;
            case 'DEVICE-RENTAL':
                return [
                    'id'              => '',
                    'name'            => '',
                    'code'            => '',
                    'unit'            => '',
                    'quantity'        => 0,
                    'unit_price'      => 0,
                    'total'           => 0,
                ];
                break;
            case 'DEVICE-ESTIMATE':
                return [
                    "id" => '',
                    "name" => '',
                    "unit" => '',
                    "mass" => 0,
                    "mass1" => 0,
                    "price" => 0,
                    "rent" => 0,
                    "rent_price" => 0,
                    "mass2" => 0,
                    "estimated_unit_price" => 0,
                    "input_time" => '',
                    "return_time" => '',
                    "days_used" => 0,
                    "total_price" => 0,
                    "note" => '',
                ];
                break;
            case 'DEVICE-MONTHLY-ESTIMATE':
                return [
                    "id" => '',
                    "name" => '',
                    "unit" => '',
                    "type" => '',
                    "total_quantity" => 0,
                    "accumulated_quantity" => 0,
                    "quantity" => 0,
                    "quantity1" => 0,
                    "quantity2" => 0,
                    "quantity3" => 0,
                    "quantity4" => 0,
                    "quantity5" => 0,
                    "quantity6" => 0,
                ];
                break;
            case 'DEVICE-ISSUANCE':
                return [
                    "id" => '',
                    "name" => '',
                    "unit" => '',
                    "accumulated_quantity" => 0,
                    "total_quantity" => 0,
                    "monthly_estimated_quantity" => 0,
                    "quantity" => 0,
                    "supply_date" => '',
                    "return_date" => '',
                    "supply_date1" => '',
                    "quantity1" => 0,
                    "has_surpassed_estimates_label" => '',
                ];
                break;
            case 'DEVICE-TRANSFER':
                return [
                    "id" => '',
                    "name" => '',
                    "unit" => '',
                    "issued_quantity" => 0,
                    "quantity" => 0,
                    "carrier_type" => '',
                    "carrier_number" => '',
                    "transfer_unit" => '',
                    "from_project" => 0,
                    "to_project" => 0,
                    "sent" => '',
                    "arrived" => '',
                ];
                break;
            case 'PLAN':
                return [
                    "id" => '',
                    "code" => '',
                    "name" => '',
                    "unit" => '',
                    "quantity" => 0,
                    "unit_price_budget" => 0,
                    "date_arrival" => '',
                    "total" => 0,
                ];
                break;
            case 'OFFER-BUY':
                return [
                    "id" => '',
                    "code" => '',
                    "name" => '',
                    "unit" => '',
                    "quantity" => 0,
                    "unit_price" => 0,
                    "date_arrival" => '',
                    "total" => 0,
                ];
                break;
            case 'RECEIPT-INPUT':
                return [
                    'id'                => '',
                    'name'              => '',
                    'code'              => '',
                    'unit'              => '',
                    'original_quantity' => 0,
                    'quantity'          => 0,
                    'difference_reason' => '',
                    'unit_price'        => 0,
                    'total'             => 0,
                ];
                break;
            case 'RECEIPT-OUTPUT':
                return [
                    "id"                   => '',
                    "code"                 => '',
                    "name"                 => '',
                    "unit"                 => '',
                    "quantity_in_stock"    => 0,
                    "accumulated_quantity" => 0,
                    "needed"               => 0,
                    "quantity"             => 0,
                    "item_name"            => '',
                    "unit_price"           => 0,
                    "total"                => 0,
                ];
                break;
            case 'RECEIPT-TRANSFER':
                return [
                    "id"         => '',
                    "code"       => '',
                    "name"       => '',
                    "unit"       => '',
                    "input"      => 0,
                    "output"     => 0,
                    "quantity"   => 0,
                    "unit_price" => 0,
                    "total"      => 0,
                ];
                break;
            case 'INVOICE':
                return [
                    'id'         => '',
                    'name'       => '',
                    'code'       => '',
                    'unit'       => '',
                    'quantity'   => 0,
                    'unit_price' => 0,
                    'total'      => 0,
                    'discount'   => 0,
                    'other_cost' => 0,
                    'tax'        => 0,
                ];
                break;
            case 'ITEMS':
                return [
                    'id'                => '',
                    'name'              => '',
                    'code'              => '',
                    'unit'              => '',
                    'quantity'          => 0,
                    'unit_price_budget' => 0,
                    'total'             => 0,
                    'progress'          => 0,
                    'type_text'         => '',
                ];
                break;
            case 'REQUEST-SUPPLIES':
                return [
                    'stt'                  => '',
                    'name'                 => '',
                    'unit'                 => '',
                    'estimate_quantity'    => 0,
                    'quantity'             => 0,
                    'cumulative'           => 0,
                    'input_cumulative'     => 0,
                    'remainder'            => 0,
                    'date_arrival_request' => '',
                    'type'                 => '',
                    'note_request'         => '',
                ];
                break;
            case 'REQUEST-PAYMENTORDER':
                return [
                    "id" => '',
                    "code" => '',
                    "name" => '',
                    "unit" => '',
                    "quantity" => 0,
                    "unit_price" => 0,
                    "total" => 0,
                ];
                break;
            case 'STOCKTAKING':
                return [
                    "id"              => '',
                    "name"            => '',
                    "unit"            => '',
                    "price"           => 0,
                    "quantity_system" => 0,
                    "quantity_actual" => 0,
                    "difference"      => 0,
                    "reason"          => '',
                    "total"           => 0,
                ];
                break;
            default:
                return [];
                break;
        }
    }
}
