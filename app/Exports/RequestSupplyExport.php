<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RequestSupplyExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $requestSupply;
    protected $sums;

    /**
     * RequestSupply constructor.
     * @param array $model
     */
    public function __construct(array $model)
    {
        $this->requestSupply = $model['requestSupply'];
        unset($model['requestSupply']);
        $this->sums = $model['sums'];
        unset($model['sums']);
        $this->model = $model;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Bcons Logo');
        $drawing->setDescription('Bcons Logo');
        $drawing->setPath(public_path('/assets/admin/images/logo-excel.jpg'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:I9')->getFont()->setName('Times New Roman')->setSize(11);
                $event->sheet->getStyle('A9:I9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

                $event->sheet->getColumnDimension('A')->setWidth(5);
                $event->sheet->getColumnDimension('B')->setWidth(19);
                $event->sheet->getColumnDimension('C')->setWidth(5);
				
                foreach(range('D','G') as $columnID) 
                {
                    $event->sheet->getColumnDimension($columnID)->setWidth(8);
                }
				$event->sheet->getColumnDimension('H')->setWidth(11);
				$event->sheet->getColumnDimension('I')->setWidth(14);
                $event->sheet->getRowDimension(1)->setRowHeight(74);
                $event->sheet->getDelegate()->mergeCells('A1:I1');

                $event->sheet->getRowDimension(2)->setRowHeight(18.75);
                $event->sheet->getRowDimension(3)->setRowHeight(20);
                $event->sheet->getRowDimension(4)->setRowHeight(29.25);

                foreach(range(5,8) as $columnID) 
                {
                    $event->sheet->getRowDimension($columnID)->setRowHeight(20);
                }

                $event->sheet->getDelegate()->mergeCells('F2:I2');
                $event->sheet->getCell('F2')->setValue('Biểu mẫu số: QT.VT.03/BM-03');
                $event->sheet->getDelegate()->getStyle('F2')->getFont()->setBold(true)->setItalic(true);
				$event->sheet->getDelegate()->mergeCells('A3:B3');
                $event->sheet->getCell('A3')->setValue('Số: ' . $this->requestSupply['code']);

                $event->sheet->getDelegate()->mergeCells('A4:I4');
                $event->sheet->setCellValue('A4', 'PHIẾU YÊU CẦU VẬT TƯ');
                $event->sheet->getDelegate()->getStyle('A4')->getFont()->setSize(22)->setBold(true);
                $event->sheet->getDelegate()->getStyle('A4')->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->mergeCells('A5:I5');
                $event->sheet->setCellValue('A5', 'Bộ phận: ' . $this->requestSupply['project']);
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A6:I6');
                $event->sheet->setCellValue('A6', 'Nội dung đề xuất: ' . $this->requestSupply['contentOffer']);
                $event->sheet->getDelegate()->getStyle('A6')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A7:I7');
                $event->sheet->setCellValue('A7', 'Khu vực thi công: '. $this->requestSupply['itemName']);
                $event->sheet->getDelegate()->getStyle('A7')->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('A4:I7')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getRowDimension(9)->setRowHeight(50);
                
                $event->sheet->getDelegate()->getStyle('A9:I9')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('B9')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('D9:I9')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A9:I9')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('A9:I9')->getAlignment()->setVertical('center');

                $loca = (int) $event->sheet->getHighestRow();

                $event->sheet->getStyle('A1:I'.$loca)->getAlignment()->setWrapText(true);

                $event->sheet->getDelegate()->mergeCells('A'.(string)($loca+1).':B'.(string)($loca+1));
                $event->sheet->setCellValue('A'. (string)($loca+1), 'TỔNG CỘNG');

                $event->sheet->setCellValue('D'. (string)($loca+1), $this->sums['sumEstimateQuantity']);
                $event->sheet->setCellValue('E'. (string)($loca+1), $this->sums['sumQuantitySum']);
                $event->sheet->setCellValue('F'. (string)($loca+1), $this->sums['sumInputCumulative']);
                $event->sheet->setCellValue('G'. (string)($loca+1), $this->sums['sumQuantity']);

                $event->sheet->getDelegate()->getStyle('A'. (string)($loca+1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('D'. (string)($loca+1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('E'. (string)($loca+1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('F'. (string)($loca+1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('G'. (string)($loca+1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A9:I'. (string)($loca+1))->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->setCellValue('B'. (string)($loca+2), 'Kính trình BGĐ xét duyệt');

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+2).':H'.(string)($loca+2));
                $event->sheet->setCellValue('F'. (string)($loca+2), $this->model[0]['date_arrival_request']);

                $event->sheet->setCellValue('B'. (string)($loca+3), 'PHÊ DUYỆT CỦA BGĐ');
                $event->sheet->getDelegate()->getStyle('B'. (string)($loca+3))->getFont()->setBold(true);

                $event->sheet->setCellValue('B'. (string)($loca+4), '(Ký ghi rõ họ tên)');
                $event->sheet->getDelegate()->getStyle('B'. (string)($loca+3))->getFont()->setItalic(true);

                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+3).':E'.(string)($loca+3));
                $event->sheet->setCellValue('C'. (string)($loca+3), 'KHỐI TÀI CHÍNH');
                $event->sheet->getDelegate()->getStyle('C'. (string)($loca+3))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+3).':G'.(string)($loca+3));
                $event->sheet->setCellValue('F'. (string)($loca+3), 'KHỐI THI CÔNG');
                $event->sheet->getDelegate()->getStyle('F'. (string)($loca+3))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('H'.(string)($loca+3).':I'.(string)($loca+3));
                $event->sheet->setCellValue('H'. (string)($loca+3), 'NGƯỜI ĐỀ XUẤT');
                $event->sheet->getDelegate()->getStyle('H'. (string)($loca+3))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+4).':E'.(string)($loca+4));
                $event->sheet->setCellValue('C'. (string)($loca+4), '(Ký ghi rõ họ tên)');
                $event->sheet->getDelegate()->getStyle('C'. (string)($loca+4))->getFont()->setItalic(true);

                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+4).':G'.(string)($loca+4));
                $event->sheet->setCellValue('F'. (string)($loca+4), '(Ký ghi rõ họ tên)');
                $event->sheet->getDelegate()->getStyle('F'. (string)($loca+4))->getFont()->setItalic(true);

                $event->sheet->getDelegate()->mergeCells('H'.(string)($loca+4).':I'.(string)($loca+4));
                $event->sheet->setCellValue('H'. (string)($loca+4), '(Ký ghi rõ họ tên)');
                $event->sheet->getDelegate()->getStyle('H'. (string)($loca+4))->getFont()->setItalic(true);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên vật tư',
            'ĐVT',
            'KL dự toán',
            'KL lũy kế gồm đơn hàng này ',
            'KL đã nhập',
            'KL yêu cầu đợt này',
            'Thời gian cần cấp đến công trình',
            'Ghi chú',
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
