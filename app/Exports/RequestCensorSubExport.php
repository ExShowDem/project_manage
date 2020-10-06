<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class RequestCensorSubExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $censorSub;

    /**
     * RequestSupply constructor.
     * @param array $requestSupply
     */
    public function __construct(array $model)
    {
        $this->censorSub = $model['censorSub'];
        unset($model['censorSub']);
        $this->model = $model;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/admin/images/logo-excel.jpg'));
        $drawing->setHeight(78);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getColumnDimension('A')->setWidth(50);
                $event->sheet->getColumnDimension('B')->setWidth(80);

                $event->sheet->getRowDimension(1)->setRowHeight(74);
                $event->sheet->getDelegate()->mergeCells('A1:B1');

                $event->sheet->getCell('A2')->setValue('Tên nhà thầu phụ');
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setBold(true);
                $event->sheet->getCell('B2')->setValue($this->censorSub['subcontractors']);

                $event->sheet->getCell('A3')->setValue('Dự án');
                $event->sheet->getDelegate()->getStyle('A3')->getFont()->setBold(true);
                $event->sheet->getCell('B3')->setValue($this->censorSub['project']['name']);

                $event->sheet->getCell('A4')->setValue('Gói thầu');
                $event->sheet->getDelegate()->getStyle('A4')->getFont()->setBold(true);
                $event->sheet->getCell('B4')->setValue($this->censorSub['package']);

                $event->sheet->getCell('A5')->setValue('Ngày trình duyệt');
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setBold(true);
                $event->sheet->getCell('B5')->setValue($this->censorSub['date_browsing']);

                $event->sheet->getCell('A6')->setValue('Ngày phê duyệt');
                $event->sheet->getDelegate()->getStyle('A6')->getFont()->setBold(true);
                $event->sheet->getCell('B6')->setValue($this->censorSub['date_approve']);

                $event->sheet->getCell('A7')->setValue('Loại');
                $event->sheet->getDelegate()->getStyle('A7')->getFont()->setBold(true);
                $event->sheet->getCell('B7')->setValue($this->censorSub['type_label']);

                $event->sheet->getCell('A8')->setValue('Link');
                $event->sheet->getDelegate()->getStyle('A8')->getFont()->setBold(true);
                $event->sheet->getCell('B8')->setValue($this->censorSub['link']);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function headings(): array
    {
        return [];
    }

    public function array(): array
    {
        return $this->model;
    }
}
