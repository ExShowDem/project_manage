<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DeviceEstimateExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $project;

    public function __construct(array $model)
    {
        $this->project = $model['project'];
        unset($model['project']);
        $this->model = $model;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/admin/images/bcons.jpg'));
        $drawing->setHeight(78);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    { // For reference: https://phpoffice.github.io/PhpSpreadsheet/1.7.0/PhpOffice/PhpSpreadsheet/Style.html
        return [
            AfterSheet::class => function(AfterSheet $event) {
				
				$event->sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 10
                    ]
                ]);
				
				
				
				
                $event->sheet->getColumnDimension('A')->setWidth(5);
                $event->sheet->getColumnDimension('B')->setWidth(13);
				$event->sheet->getColumnDimension('C')->setWidth(6);
                $event->sheet->getDelegate()->mergeCells('A1:B4');
                $event->sheet->getRowDimension(4)->setRowHeight(20);

                $event->sheet->getDelegate()->mergeCells('D1:P1');
                $event->sheet->getDelegate()->mergeCells('D2:P2');
                $event->sheet->getDelegate()->mergeCells('D3:P3');
                $event->sheet->getDelegate()->mergeCells('D4:P4');

                $event->sheet->getCell('D1')->setValue('P/B/BP/DA:');
                $event->sheet->getCell('D2')->setValue('Ngày:');
                $event->sheet->getCell('D3')->setValue('Số:');
                $event->sheet->getCell('D4')->setValue('DỰ TRÙ MÁY MÓC THIẾT BỊ ĐẦU CÔNG TRƯỜNG');

                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:P4')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('D1:P1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('D4:P4')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A5:P6');
                $event->sheet->getCell('A5')->setValue('Địa điểm xây dựng: ' . $this->project);
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setItalic(true);
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setUnderline(true);
                $event->sheet->getDelegate()->getStyle('A5')->getAlignment()->setVertical('top');

                $event->sheet->getDelegate()->mergeCells('A7:A9');
                $event->sheet->getDelegate()->mergeCells('B7:B9');
                $event->sheet->getDelegate()->mergeCells('C7:C9');
                $event->sheet->getDelegate()->mergeCells('D7:D9');
                $event->sheet->getDelegate()->mergeCells('E7:E9');
                $event->sheet->getDelegate()->mergeCells('F7:O7');
                $event->sheet->getDelegate()->mergeCells('P7:P9');

                $event->sheet->getDelegate()->mergeCells('F8:F9');
                $event->sheet->getDelegate()->mergeCells('G8:G9');
                $event->sheet->getDelegate()->mergeCells('H8:H9');
                $event->sheet->getDelegate()->mergeCells('I8:I9');
                $event->sheet->getDelegate()->mergeCells('J8:K8');
                $event->sheet->getDelegate()->mergeCells('L8:L9');
                $event->sheet->getDelegate()->mergeCells('M8:M9');
                $event->sheet->getDelegate()->mergeCells('N8:N9');
                $event->sheet->getDelegate()->mergeCells('O8:O9');


                $event->sheet->getColumnDimension('D')->setWidth(6);
                $event->sheet->getColumnDimension('E')->setWidth(7);
                $event->sheet->getColumnDimension('F')->setWidth(7);
                $event->sheet->getColumnDimension('G')->setWidth(4);
                $event->sheet->getColumnDimension('H')->setWidth(4);
                $event->sheet->getColumnDimension('I')->setWidth(4);
                $event->sheet->getColumnDimension('J')->setWidth(4);
                $event->sheet->getColumnDimension('K')->setWidth(5);
                $event->sheet->getColumnDimension('L')->setWidth(9);
                $event->sheet->getColumnDimension('M')->setWidth(9);
                $event->sheet->getColumnDimension('N')->setWidth(5);
                $event->sheet->getColumnDimension('O')->setWidth(6);
                $event->sheet->getColumnDimension('P')->setWidth(5);

                $event->sheet->getDelegate()->getStyle('A7:P9')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A7:P9')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7:P9')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $loca = (int) $event->sheet->getHighestRow();
				$event->sheet->getStyle('A1:P'.$loca)->getAlignment()->setWrapText(true);
				
				$event->sheet->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A2:P2')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A3:P3')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A4:P4')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A7:P7')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
				$event->sheet->getStyle('A8:P8')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A9:P9')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
				
                $event->sheet->getDelegate()->getStyle('A10:P'.$loca)->getBorders()->applyFromArray( [ 'outline' => ['borderStyle' => 'thin'] ] );
				
				
				
				
                $event->sheet->getCell('A'.(string)($loca+2))->setValue('Ngày...Tháng...Năm... ...');
                $event->sheet->getCell('A'.(string)($loca+3))->setValue('CHỈ HUY TRƯỞNG');
				
				$event->sheet->getStyle('A'.(string)($loca+3))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+2).':E'.(string)($loca+2));
                $event->sheet->getCell('C'.(string)($loca+2))->setValue('Ngày...Tháng...Năm... ...');
                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+3).':E'.(string)($loca+3));
                $event->sheet->getCell('C'.(string)($loca+3))->setValue('TRƯỞNG P.THIẾT BỊ');
				$event->sheet->getStyle('C'.(string)($loca+3))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+2).':I'.(string)($loca+2));
                $event->sheet->getCell('F'.(string)($loca+2))->setValue('Ngày...Tháng...Năm... ...');
                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+3).':I'.(string)($loca+3));
                $event->sheet->getCell('F'.(string)($loca+3))->setValue('GIÁM ĐỐC KHỐI ĐẦU THẦU');
				$event->sheet->getStyle('F'.(string)($loca+3))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('J'.(string)($loca+2).':M'.(string)($loca+2));
                $event->sheet->getCell('J'.(string)($loca+2))->setValue('Ngày...Tháng...Năm... ...');
                $event->sheet->getDelegate()->mergeCells('J'.(string)($loca+3).':M'.(string)($loca+3));
                $event->sheet->getCell('J'.(string)($loca+3))->setValue('KHỐI TÀI CHÍNH KẾ TOÁN');
				$event->sheet->getStyle('J'.(string)($loca+3))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->mergeCells('N'.(string)($loca+2).':P'.(string)($loca+2));
                $event->sheet->getCell('N'.(string)($loca+2))->setValue('Ngày...Tháng...Năm... ...');
                $event->sheet->getDelegate()->mergeCells('N'.(string)($loca+3).':P'.(string)($loca+3));
                $event->sheet->getCell('N'.(string)($loca+3))->setValue('BAN GIÁM ĐỐC');
				$event->sheet->getStyle('N'.(string)($loca+3))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+3).':P'.(string)($loca+3))->getFont()->setBold(true);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function headings(): array
    {
        return [
            [
                'STT',
                'Loại máy móc thiết bị',
                'ĐVT',
                'Kích thước/Quy cách',
                'Khối lượng dự trù',
                'Khối lượng dự trù thi công',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Ghi chú',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                'BP.TB cung cấp',
                'Đơn giá',
                'Thuê ngoài',
                'Đơn giá',
                'Dự trù đầu tư',
                '',
                'Ngày dự trù cấp',
                'Ngày dự trù trả',
                'Tổng ngày sử dụng',
                'Thành tiền',
                '',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Khối lượng',
                'Đơn giá',
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
