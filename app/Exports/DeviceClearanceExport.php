<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DeviceClearanceExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;

    public function __construct(array $model)
    {
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
                $event->sheet->getColumnDimension('A')->setWidth(5);
                $event->sheet->getColumnDimension('B')->setWidth(25);
                $event->sheet->getDelegate()->mergeCells('A1:B4');
                $event->sheet->getRowDimension(4)->setRowHeight(20);

                foreach(range('C','H') as $columnID) 
                {
                    $event->sheet->getColumnDimension($columnID)->setWidth(15);
                }

                $loca = (int) $event->sheet->getHighestRow();

                $event->sheet->getDelegate()->getStyle('A5:H5')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:H'.$loca)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:H'.$loca)->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );
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
            'ID',
            'Mã thiết bị',
            'Tên thiết bị',
            'ĐVT',
            'Số lượng',
            'Số lượng đang có',
            'Đơn Giá',
            'Thành tiền',
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
