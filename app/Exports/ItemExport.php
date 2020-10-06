<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ItemExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;

    /**
     * ItemExport constructor.
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

                foreach(range('C','I') as $columnID) 
                {
                    $event->sheet->getColumnDimension($columnID)->setWidth(15);
                }

                $loca = (int) $event->sheet->getHighestRow();

                $event->sheet->getDelegate()->getStyle('A5:I5')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:I'.$loca)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:I'.$loca)->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );
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
                'Số lượng',
                'Đơn giá chưa Vat',
                'Thành tiền',
                'Tiến độ',
                'Bộ phận cấp',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
