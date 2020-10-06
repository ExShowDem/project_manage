<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DeviceIssuanceExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
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
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
				
				$event->sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 10
                    ]
                ]);
				
                $event->sheet->getColumnDimension('A')->setWidth(5);
                $event->sheet->getColumnDimension('B')->setWidth(16);
				$event->sheet->getColumnDimension('C')->setWidth(5);
                $event->sheet->getColumnDimension('D')->setWidth(7);
				$event->sheet->getColumnDimension('E')->setWidth(5);
                $event->sheet->getColumnDimension('F')->setWidth(7);
				$event->sheet->getColumnDimension('G')->setWidth(6);
                $event->sheet->getColumnDimension('H')->setWidth(7);
				$event->sheet->getColumnDimension('I')->setWidth(10);
                $event->sheet->getColumnDimension('J')->setWidth(10);
				$event->sheet->getColumnDimension('K')->setWidth(10);
                $event->sheet->getColumnDimension('L')->setWidth(6);
				$event->sheet->getColumnDimension('M')->setWidth(6);

				
                $event->sheet->getDelegate()->mergeCells('A1:B4');
                $event->sheet->getRowDimension(4)->setRowHeight(20);

                $event->sheet->getDelegate()->mergeCells('D1:M1');
                $event->sheet->getDelegate()->mergeCells('D2:M2');
                $event->sheet->getDelegate()->mergeCells('D3:M3');
                $event->sheet->getDelegate()->mergeCells('D4:M4');

                $event->sheet->getCell('D1')->setValue('P/B/BP/DA:');
                $event->sheet->getCell('D2')->setValue('Ngày:');
                $event->sheet->getCell('D3')->setValue('Số:');
                $event->sheet->getCell('D4')->setValue('ĐỀ NGHỊ CUNG CẤP / TRẢ MÁY MÓC THIẾT BỊ');

                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:M4')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('C1:M1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('C4:M4')->getFont()->setBold(true);

                foreach(range('C','M') as $columnID) 
                {
                    //$event->sheet->getColumnDimension($columnID)->setWidth(15);
                }

                $event->sheet->getDelegate()->mergeCells('A5:A6');
                $event->sheet->getDelegate()->mergeCells('B5:B6');

                $event->sheet->getDelegate()->mergeCells('C5:J5');
                $event->sheet->getDelegate()->mergeCells('K5:L5');

                $event->sheet->getDelegate()->mergeCells('M5:M6');

                $event->sheet->getDelegate()->getStyle('A5:M6')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:M6')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:M6')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $loca = (int) $event->sheet->getHighestRow();
				$event->sheet->getStyle('A1:M'.$loca)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7:M'.$loca)->getBorders()->applyFromArray( [ 'outline' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->mergeCells('A'.(string)($loca+2).':B'.(string)($loca+2));
                $event->sheet->getCell('A'.(string)($loca+2))->setValue('MỤC ĐÍCH SỬ DỤNG:');
				$event->sheet->getStyle('A'.(string)($loca+2))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getCell('H'.(string)($loca+2))->setValue('Ý KIẾN PHẢN HỒI:');
				$event->sheet->getStyle('H'.(string)($loca+2))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+2).':K'.(string)($loca+2))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A'.(string)($loca+5).':B'.(string)($loca+5));
                $event->sheet->getCell('A'.(string)($loca+5))->setValue('Ý KIẾN KHÁC:');
				$event->sheet->getStyle('A'.(string)($loca+5))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getCell('H'.(string)($loca+5))->setValue('Ý KIẾN KHÁC:');
				$event->sheet->getStyle('H'.(string)($loca+5))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+5).':K'.(string)($loca+5))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A'.(string)($loca+7).':B'.(string)($loca+7));
                $event->sheet->getCell('A'.(string)($loca+7))->setValue('CHỈ HUY TRƯỞNG');
				$event->sheet->getStyle('A'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+7).':E'.(string)($loca+7));
                $event->sheet->getCell('C'.(string)($loca+7))->setValue('TRƯỞNG P.THIẾT BỊ');
				$event->sheet->getStyle('C'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('F'.(string)($loca+7).':I'.(string)($loca+7));
                $event->sheet->getCell('F'.(string)($loca+7))->setValue('GIÁM SÁT THIẾT BỊ CÔNG TRƯỜNG');
				$event->sheet->getStyle('F'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('J'.(string)($loca+7).':K'.(string)($loca+7));
                $event->sheet->getCell('J'.(string)($loca+7))->setValue('NHÂN VIÊN PHÒNG THIẾT BỊ');
				$event->sheet->getStyle('J'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('L'.(string)($loca+7).':M'.(string)($loca+7));
                $event->sheet->getCell('L'.(string)($loca+7))->setValue('KHÁC.....');
				$event->sheet->getStyle('L'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+7).':M'.(string)($loca+7))->getFont()->setBold(true);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return [
            [
                'Stt',
                'Nội dung',
                'Từ công trường: ' . $this->project,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Từ phòng thiết bị',
                '',
                'Vượt dự trù',
            ],
            [
                '',
                '',
                'ĐVT',
                'Quy cách',
                'SL lũy kế',
                'SL Dự trù tổng',
                'SL Dự trù tháng',
                'Số lượng',
                'Ngày cung cấp',
                'Ngày trả',
                'Ngày cung cấp (P.TB)',
                'Số lượng (P.TB)',
                '',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
