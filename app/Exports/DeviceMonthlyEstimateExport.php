<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DeviceMonthlyEstimateExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $project;

    protected $batchDates = [];

    public function __construct(array $model)
    {
        $this->project = $model['project'];
        unset($model['project']);

        foreach ($model['dates'] as $key => $date) 
        {
            $this->batchDates[$key] = $date;
        }
        unset($model['dates']);

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
				
                $event->sheet->getColumnDimension('A')->setWidth(4);
                $event->sheet->getColumnDimension('B')->setWidth(12);
				
				$event->sheet->getColumnDimension('C')->setWidth(4);
                $event->sheet->getColumnDimension('D')->setWidth(4);
				$event->sheet->getColumnDimension('E')->setWidth(4);
                $event->sheet->getColumnDimension('F')->setWidth(4);
				$event->sheet->getColumnDimension('G')->setWidth(6);
                

				
                $event->sheet->getDelegate()->mergeCells('A1:B4');
                $event->sheet->getRowDimension(4)->setRowHeight(20);

                $event->sheet->getDelegate()->mergeCells('E1:M1');
                $event->sheet->getDelegate()->mergeCells('E2:M2');
                $event->sheet->getDelegate()->mergeCells('E3:M3');
                $event->sheet->getDelegate()->mergeCells('E4:M4');
				$event->sheet->getStyle('A5:M5')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				$event->sheet->getStyle('A6:M6')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getCell('E1')->setValue('Công trường: ' . $this->project);
                $event->sheet->getCell('E2')->setValue('Ngày:');
                $event->sheet->getCell('E3')->setValue('Số:');
                $event->sheet->getCell('E4')->setValue('DỰ TRÙ MÁY MÓC THIẾT BỊ THÁNG:_');

                foreach(range('H','M') as $columnID) 
                {
                    $event->sheet->getColumnDimension($columnID)->setWidth(10);
                }

                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:M4')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('C1:M1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('C4:M4')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A5:A6');
                $event->sheet->getDelegate()->mergeCells('B5:B6');
                $event->sheet->getDelegate()->mergeCells('C5:C6');
                $event->sheet->getDelegate()->mergeCells('D5:D6');
                $event->sheet->getDelegate()->mergeCells('E5:E6');
                $event->sheet->getDelegate()->mergeCells('F5:F6');
                $event->sheet->getDelegate()->mergeCells('G5:G6');

                $event->sheet->getDelegate()->mergeCells('H5:M5');

                $event->sheet->getDelegate()->getStyle('A5:M6')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:M6')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:M6')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );
                $event->sheet->getStyle('A5:M6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00FFFF');

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
                $event->sheet->getDelegate()->mergeCells('C'.(string)($loca+7).':F'.(string)($loca+7));
                $event->sheet->getCell('C'.(string)($loca+7))->setValue('TRƯỞNG P.THIẾT BỊ');
				$event->sheet->getStyle('C'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('G'.(string)($loca+7).':I'.(string)($loca+7));
                $event->sheet->getCell('G'.(string)($loca+7))->setValue('GIÁM SÁT THIẾT BỊ CÔNG TRƯỜNG');
				$event->sheet->getStyle('G'.(string)($loca+7))->applyFromArray([
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
				
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+7).':L'.(string)($loca+7))->getFont()->setBold(true);
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
                'STT',
                'Tên thiết bị',
                'ĐVT',
                'CẤP/TRẢ',
                'SL dự trù tổng',
                'SL lũy kế',
                'Số lượng',
                'HẠNG MỤC',
                '',
                '',
                '',
                '',
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
                'ĐỢT 1 Ngày: ' . $this->batchDates[1] . ' ',
                'ĐỢT 2 Ngày: ' . $this->batchDates[2] . ' ',
                'ĐỢT 3 Ngày: ' . $this->batchDates[3] . ' ',
                'ĐỢT 4 Ngày: ' . $this->batchDates[4] . ' ',
                'ĐỢT 5 Ngày: ' . $this->batchDates[5] . ' ',
                'ĐỢT 6 Ngày: ' . $this->batchDates[6] . ' ',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
