<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use App\Enums\PaymentOrderStatus;
use App\Models\PaymentOrder;

class RequestPaymentorderExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $paymentOrder;

    /**
     * RequestSupply constructor.
     * @param array $requestSupply
     */
    public function __construct(array $model)
    {
        $this->paymentOrder = $model['paymentOrder'];
        unset($model['paymentOrder']);
        $this->model = $model;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/admin/images/logo-excel.jpg'));
        $drawing->setHeight(68);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $contractSub   = $this->paymentOrder->contractSub;
                $subcontractor = $this->paymentOrder->subContractor;

                $event->sheet->getColumnDimension('A')->setWidth(3.87);
                $event->sheet->getColumnDimension('B')->setWidth(5.29);
                $event->sheet->getColumnDimension('C')->setWidth(26.0);
                $event->sheet->getColumnDimension('D')->setWidth(13.29);
                $event->sheet->getColumnDimension('E')->setWidth(15.29);
                $event->sheet->getColumnDimension('F')->setWidth(12.29);
                $event->sheet->getColumnDimension('G')->setWidth(13.29);

                $event->sheet->getRowDimension(1)->setRowHeight(54);
                $event->sheet->getRowDimension(2)->setRowHeight(18.75);
                $event->sheet->getRowDimension(3)->setRowHeight(20);
                $event->sheet->getRowDimension(4)->setRowHeight(22.25);

                $event->sheet->getRowDimension(13)->setRowHeight(18);
                $event->sheet->getRowDimension(18)->setRowHeight(18);

                $event->sheet->getDelegate()->mergeCells('A4:G4');
                $event->sheet->setCellValue('A4', 'PHIẾU YÊU CẦU THANH TOÁN');
                $event->sheet->getDelegate()->getStyle('A4')->getFont()->setBold(true)->setItalic(true);
                $event->sheet->getDelegate()->getStyle('A4')->getFont()->setSize(16)->setBold(true);
                $event->sheet->getDelegate()->getStyle('A4')->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->mergeCells('D5:H5');
                $event->sheet->getCell('D5')->setValue('Số: ______ BCST    ' . $this->paymentOrder->payment_date);

                $event->sheet->setCellValue('C7', 'Họ tên:');
                $event->sheet->getDelegate()->mergeCells('D7:F7');
                $event->sheet->setCellValue('D7', 'Đặng Thị Hoàng Yến');

                $event->sheet->setCellValue('C8', 'Đơn vị:');
                $event->sheet->getDelegate()->mergeCells('D8:E8');
                $event->sheet->setCellValue('D8', 'Khối đấu thầu');

                $event->sheet->setCellValue('C9', 'Nội dung thanh toán:');
                $event->sheet->getDelegate()->mergeCells('D9:E9');
                $event->sheet->setCellValue('D9', $contractSub->content);

                $event->sheet->setCellValue('C10', 'Đơn vị thụ hưởng:');
                $event->sheet->getDelegate()->mergeCells('D10:G10');
                $event->sheet->setCellValue('D10', $subcontractor->name);

                $event->sheet->setCellValue('C11', 'Số tài khoản:');
                $event->sheet->getDelegate()->mergeCells('D11:L11');
                $event->sheet->setCellValue('D11', $subcontractor->account_name.'-'.$subcontractor->bank);

                $event->sheet->getDelegate()->getStyle('B12:D12')->getFont()->setBold(true);
                $event->sheet->getDelegate()->mergeCells('B12:D12');
                $event->sheet->setCellValue('B12', 'I.PHẦN ĐỀ NGHỊ THANH TOÁN:');

                $event->sheet->getDelegate()->getStyle('B13:M13')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B13:M13')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B13:M13')->getAlignment()->setVertical('center');

                $event->sheet->setCellValue('B13', 'STT');
                $event->sheet->setCellValue('C13', 'NỘI DUNG');
                $event->sheet->setCellValue('D13', 'Ngày HĐ');
                $event->sheet->setCellValue('E13', 'Số HĐ');
                $event->sheet->setCellValue('F13', 'Số Tiền');
                $event->sheet->setCellValue('G13', 'Hạch Toán');

                $event->sheet->getDelegate()->getStyle('B13:M13')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B13:M13')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B13:M13')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('C13:C13')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C13:C13')->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('G13:G13')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('G13:G13')->getAlignment()->setVertical('center');

                $event->sheet->setCellValue('B14', '1');
                $event->sheet->setCellValue('C14', $this->paymentOrder->content);

                $event->sheet->getDelegate()->getRowDimension(14)->setRowHeight(-1); 
                $event->sheet->getDelegate()->getStyle('C14')->getAlignment()->setWrapText(true);

                $event->sheet->setCellValue('E14', $contractSub->contract_number);
                $event->sheet->setCellValue('F14', $this->paymentOrder->settlement_value);
                $event->sheet->setCellValue('G14', $this->paymentOrder->project->name);

                $event->sheet->getDelegate()->getStyle('B15:F15')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B15:E15')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B15:E15')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->mergeCells('B15:E15');
                $event->sheet->setCellValue('B15', 'CỘNG');

                $event->sheet->setCellValue('F15', $this->paymentOrder->settlement_value);

                $event->sheet->getDelegate()->getStyle('B13:G15')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $loca = (int)$event->sheet->getHighestRow();

                $event->sheet->getDelegate()->getStyle('B17:E17')->getFont()->setBold(true);
                $event->sheet->getDelegate()->mergeCells('B17:E17');
                $event->sheet->setCellValue('B17', 'Hồ sơ gốc hợp lệ đính kèm:');

                $event->sheet->setCellValue('B18', 'STT');
                $event->sheet->setCellValue('C18', 'Tên');
                $event->sheet->setCellValue('D18', 'Ngày hiệu lực');
                $event->sheet->setCellValue('E18', 'SL');
                $event->sheet->setCellValue('F18', 'Ghi chú');

                $event->sheet->setCellValue('B19', '1');
                $event->sheet->setCellValue('C19', 'Hồ sơ thanh toán');
                $event->sheet->setCellValue('D19', '');
                $event->sheet->setCellValue('E19', '1');
                $event->sheet->setCellValue('F19', '');

                $event->sheet->getDelegate()->getStyle('B18:F19')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('B18:M18')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B18:M18')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B18:M18')->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('B18:M18')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('C18')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('B18:M18')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B18:M18')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('C18:C18')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C18:C18')->getAlignment()->setVertical('center');

                $loca = (int)$event->sheet->getHighestRow();

                $event->sheet->getDelegate()->getStyle('B21:F21')->getFont()->setBold(true);
                $event->sheet->getDelegate()->mergeCells('B21:F21');
                $event->sheet->setCellValue('B21', 'II.TÌNH HÌNH THANH TOÁN(đính kèm bản photo chứng từ thanh toán):');

                $event->sheet->getDelegate()->getStyle('B22:M22')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B22:M22')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B22:M22')->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->getStyle('B22:M22')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('C22')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('B22:M22')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B22:M22')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('C22:C22')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C22:C22')->getAlignment()->setVertical('center');

                $loca = (int)$event->sheet->getHighestRow();

                if($this->paymentOrder->type_payment == 1)
                {
                    $event->sheet->getDelegate()->getStyle('B25:E25')->getFont()->setBold(true);
                }

                $event->sheet->getDelegate()->getStyle('B25:D'.(string)($loca-2))->getFont()->setItalic(true);
                $event->sheet->getDelegate()->getStyle('E25:E'.(string)($loca-2))->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('B'.(string)($loca).':F'.(string)($loca))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B'.(string)($loca-1).':F'.(string)($loca-1))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('F23:F'.(string)($loca));

                $event->sheet->getDelegate()->getStyle('B'. (string)($loca))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B22:F'. (string)($loca))->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('B23:E23')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B24:E24')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('E23:E24');
                $event->sheet->getDelegate()->getStyle('E23:E24')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('E23:E24')->getAlignment()->setVertical('center');

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+2).':D'.(string)($loca+2));
                $event->sheet->setCellValue('B'.(string)($loca+2), 'Trưởng đơn vị/CHT Công trình');

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+2).':F'.(string)($loca+2));
                $event->sheet->setCellValue('F'.(string)($loca+2), 'Người đề nghị');

                $event->sheet->setCellValue('C'.(string)($loca+5), 'Cái Minh Giác');

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+5).':G'.(string)($loca+5));
                $event->sheet->setCellValue('F'.(string)($loca+5), 'Đặng Thị Hoàng Yến');

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+6).':E'.(string)($loca+6));
                $event->sheet->setCellValue('B'.(string)($loca+6), 'PHẦN KIỂM TRA, XEM XÉT CỦA CÔNG TY');

                $event->sheet->setCellValue('C'.(string)($loca+7), 'Tổng giám đốc');

                $event->sheet->setCellValue('D'.(string)($loca+7), 'Kế toán trưởng');

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+7).':G'.(string)($loca+7));
                $event->sheet->setCellValue('F'.(string)($loca+7), 'Kế toán viên');
            },
        ];
    }

    public function startCell(): string
    {
        return 'B22';
    }

    public function headings(): array
    {
        return [
            'STT',
            'Số HĐ / Đợt thanh toán',
            'Giá trị',
            'Chứng từ',
            'Ghi chú'
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
