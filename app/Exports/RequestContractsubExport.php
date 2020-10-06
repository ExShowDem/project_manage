<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class RequestContractsubExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
{
    protected $model;
    protected $contractSub;

    /**
     * RequestSupply constructor.
     * @param array $requestSupply
     */
    public function __construct(array $model)
    {
        $this->contractSub = $model['contractSub'];
        unset($model['contractSub']);
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
                $event->sheet->getCell('B2')->setValue($this->contractSub['subcontractor']['name']);

                $event->sheet->getCell('A3')->setValue('Hạng mục thi công');
                $event->sheet->getDelegate()->getStyle('A3')->getFont()->setBold(true);
                $event->sheet->getCell('B3')->setValue($this->contractSub['construction_items']);

                $event->sheet->getCell('A4')->setValue('Dự án');
                $event->sheet->getDelegate()->getStyle('A4')->getFont()->setBold(true);
                $event->sheet->getCell('B4')->setValue($this->contractSub['project']['name']);

                $event->sheet->getCell('A5')->setValue('Ngày ký hợp đồng');
                $event->sheet->getDelegate()->getStyle('A5')->getFont()->setBold(true);
                $event->sheet->getCell('B5')->setValue($this->contractSub['contract_sign_date']);

                $event->sheet->getCell('A6')->setValue('Tiến độ');
                $event->sheet->getDelegate()->getStyle('A6')->getFont()->setBold(true);
                $event->sheet->getCell('B6')->setValue($this->contractSub['process']);

                $event->sheet->getCell('A7')->setValue('Hình thức hợp đồng');
                $event->sheet->getDelegate()->getStyle('A7')->getFont()->setBold(true);
                $event->sheet->getCell('B7')->setValue($this->contractSub['contract_form']);

                $event->sheet->getCell('A8')->setValue('Số hợp đồng');
                $event->sheet->getDelegate()->getStyle('A8')->getFont()->setBold(true);
                $event->sheet->getCell('B8')->setValue($this->contractSub['contract_number']);

                $event->sheet->getCell('A9')->setValue('Giá trị phụ lục hợp đồng');
                $event->sheet->getDelegate()->getStyle('A9')->getFont()->setBold(true);
                $event->sheet->getCell('B9')->setValue($this->contractSub['contract_annex_value']);

                $event->sheet->getCell('A10')->setValue('Giá trị hợp đồng (chưa VAT) (VND)');
                $event->sheet->getDelegate()->getStyle('A10')->getFont()->setBold(true);
                $event->sheet->getCell('B10')->setValue($this->contractSub['contract_value']);

                $event->sheet->getCell('A11')->setValue('Giá trị hợp đồng (có VAT) (VND)');
                $event->sheet->getDelegate()->getStyle('A11')->getFont()->setBold(true);
                $event->sheet->getCell('B11')->setValue($this->contractSub['contract_value_vat']);

                $event->sheet->getCell('A12')->setValue('Giá Trị tạm giữ bảo hành');
                $event->sheet->getDelegate()->getStyle('A12')->getFont()->setBold(true);
                $event->sheet->getCell('B12')->setValue($this->contractSub['value_custody_warranty']);

                $event->sheet->getCell('A13')->setValue('Nội dung hợp đồng');
                $event->sheet->getDelegate()->getStyle('A13')->getFont()->setBold(true);
                $event->sheet->getCell('B13')->setValue($this->contractSub['content']);

                $event->sheet->getCell('A14')->setValue('Thời Gian Bảo Hành');
                $event->sheet->getDelegate()->getStyle('A14')->getFont()->setBold(true);
                $event->sheet->getCell('B14')->setValue($this->contractSub['date_warranty']);
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
