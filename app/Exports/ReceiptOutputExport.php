<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ReceiptOutputExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;

    /**
     * OutputExport constructor.
     * @param array $model
     */
    public function __construct(array $model)
    {
        $this->model = $model;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Bcons Logo');
        $drawing->setDescription('Bcons logo');
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

                foreach(range('C','L') as $columnID) 
                {
                    $event->sheet->getColumnDimension($columnID)->setWidth(12);
                }

                $loca = (int) $event->sheet->getHighestRow();

                $event->sheet->getDelegate()->getStyle('A5:L5')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:L'.$loca)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:L'.$loca)->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );
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
                'ID',
                'Mã vật tư',
                'Tên vật tư',
                'ĐVT',
                'SL tồn kho',
                'SL lũy kế',
                'SL cần xuất',
                'SL thực xuất',
                'Hạng mục',
                'Đơn Giá',
                'Thành Tiền',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
