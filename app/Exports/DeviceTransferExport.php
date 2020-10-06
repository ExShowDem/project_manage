<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DeviceTransferExport implements FromArray, WithDrawings, WithEvents, WithHeadings, WithCustomStartCell
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
				
				$event->sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 10
                    ]
                ]);
				
                $event->sheet->getColumnDimension('A')->setWidth(4);
                $event->sheet->getColumnDimension('B')->setWidth(10);
				$event->sheet->getColumnDimension('C')->setWidth(5);
                $event->sheet->getColumnDimension('D')->setWidth(6);
				$event->sheet->getColumnDimension('E')->setWidth(6);
                $event->sheet->getColumnDimension('F')->setWidth(6);
				$event->sheet->getColumnDimension('G')->setWidth(7);
                $event->sheet->getColumnDimension('H')->setWidth(7);
				$event->sheet->getColumnDimension('I')->setWidth(5);
                $event->sheet->getColumnDimension('J')->setWidth(5);
				$event->sheet->getColumnDimension('K')->setWidth(8);
                $event->sheet->getColumnDimension('L')->setWidth(6);
				$event->sheet->getColumnDimension('M')->setWidth(6);
				$event->sheet->getColumnDimension('N')->setWidth(6);
                $event->sheet->getColumnDimension('O')->setWidth(6);
                $event->sheet->getDelegate()->mergeCells('A1:B4');
                $event->sheet->getRowDimension(4)->setRowHeight(20);

                $event->sheet->getDelegate()->mergeCells('C1:O1');
                $event->sheet->getDelegate()->mergeCells('C2:O2');
                $event->sheet->getDelegate()->mergeCells('C3:O3');
                $event->sheet->getDelegate()->mergeCells('C4:O4');

                $event->sheet->getCell('C1')->setValue('P/B/BP/DA:');
                $event->sheet->getCell('C2')->setValue('Ngày:');
                $event->sheet->getCell('C3')->setValue('Số:');
                $event->sheet->getCell('C4')->setValue('KẾ HOẠCH ĐIỀU CHUYỂN MÁY MÓC THIẾT BỊ');

                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('C4')->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle('A1:O4')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->getStyle('C1:O1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('C4:O4')->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('A5:A6');
                $event->sheet->getDelegate()->mergeCells('B5:B6');
                $event->sheet->getDelegate()->mergeCells('C5:C6');
                $event->sheet->getDelegate()->mergeCells('D5:D6');
                $event->sheet->getDelegate()->mergeCells('E5:E6');
                $event->sheet->getDelegate()->mergeCells('F5:F6');
                $event->sheet->getDelegate()->mergeCells('G5:G6');
                $event->sheet->getDelegate()->mergeCells('H5:H6');

                $event->sheet->getDelegate()->mergeCells('I5:K5');

                $event->sheet->getDelegate()->mergeCells('L5:L6');
                $event->sheet->getDelegate()->mergeCells('M5:N5');

                $event->sheet->getDelegate()->mergeCells('O5:O6');

                $event->sheet->getDelegate()->getStyle('A5:O6')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:O6')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5:O6')->getBorders()->applyFromArray( [ 'allBorders' => ['borderStyle' => 'thin'] ] );

                $loca = (int) $event->sheet->getHighestRow();
				$event->sheet->getStyle('A1:O'.$loca)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7:O'.$loca)->getBorders()->applyFromArray( [ 'outline' => ['borderStyle' => 'thin'] ] );

                $event->sheet->getDelegate()->mergeCells('A'.(string)($loca+2).':B'.(string)($loca+2));
                $event->sheet->getCell('A'.(string)($loca+2))->setValue('Chú ý:');
                $event->sheet->getDelegate()->getStyle('A'.(string)($loca+2))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+3).':N'.(string)($loca+3));
                $event->sheet->getCell('B'.(string)($loca+3))->setValue('1/ Thời gian xe vận chuyển vào khu vực nội thành như sau:');
                $event->sheet->getDelegate()->getStyle('B'.(string)($loca+3))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+4).':G'.(string)($loca+4));
                $event->sheet->getCell('B'.(string)($loca+4))->setValue('  + Xe thùng 6m, 4.5m, 3m, ba gác:');

                $event->sheet->getDelegate()->mergeCells('H'.(string)($loca+4).':N'.(string)($loca+4));
                $event->sheet->getCell('H'.(string)($loca+4))->setValue('Sau 8h00 đến trước 16h00, sau 20h00 đến trước 6h00');
                $event->sheet->getDelegate()->getStyle('H'.(string)($loca+4))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+5).':G'.(string)($loca+5));
                $event->sheet->getCell('B'.(string)($loca+5))->setValue('  + Xe cẩu thùng, đầu kéo các loại xe tải nặng khác :');

                $event->sheet->getDelegate()->mergeCells('H'.(string)($loca+5).':N'.(string)($loca+5));
                $event->sheet->getCell('H'.(string)($loca+5))->setValue('Sau 0h00 đến trước 6h00');
                $event->sheet->getDelegate()->getStyle('H'.(string)($loca+5))->getFont()->setBold(true);

                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+6).':K'.(string)($loca+6));
                $event->sheet->getCell('B'.(string)($loca+6))->setValue('  + BCH công trường hoàn tất việc xuất nhập để xe di chuyển ra khỏi khu vực nội thành trước thời gian cấm ');
				$event->sheet->getStyle('B'.(string)($loca+6))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('L'.(string)($loca+6).':N'.(string)($loca+6));
                $event->sheet->getCell('L'.(string)($loca+6))->setValue('(trước 6h00 và 16h00)');
                $event->sheet->getDelegate()->getStyle('L'.(string)($loca+6))->getFont()->setBold(true);
				$event->sheet->getStyle('L'.(string)($loca+6))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+7).':O'.(string)($loca+7));
                $event->sheet->getCell('B'.(string)($loca+7))->setValue('2/ BCH công trình thực hiện sắp xếp mặt bằng, phân loại vật tư thiết bị trước khi gửi yêu cầu cấp/trả.');
                $event->sheet->getDelegate()->getStyle('B'.(string)($loca+7))->getFont()->setBold(true);
				$event->sheet->getStyle('B'.(string)($loca+7))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+8).':O'.(string)($loca+8));
                $event->sheet->getCell('B'.(string)($loca+8))->setValue('3/ Tính thêm chi phí nếu BCH công trình không thực hiện công tác xuất/ nhập thiết bị sau khi xe vận chuyển đến công trình được 01 tiếng đồng hồ.');
                $event->sheet->getDelegate()->getStyle('B'.(string)($loca+8))->getFont()->setBold(true);
				$event->sheet->getStyle('B'.(string)($loca+8))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->mergeCells('B'.(string)($loca+9).':C'.(string)($loca+9));
                $event->sheet->getCell('B'.(string)($loca+9))->setValue('  + Thời gian chờ: ');

                $event->sheet->getDelegate()->mergeCells('D'.(string)($loca+9).':O'.(string)($loca+9));
                $event->sheet->getCell('D'.(string)($loca+9))->setValue('1h00 (phạt 1/3 chi phí cước xe, 2h00 (phạt 1/2 chi phí cước xe), sau 2h00 rút xe khỏi công trình (phạt 1/2 chi phí cước xe)');
                $event->sheet->getDelegate()->getStyle('D'.(string)($loca+9))->getFont()->setBold(true);
				$event->sheet->getStyle('D'.(string)($loca+9))->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 8
                    ]
                ]);
				
                $event->sheet->getDelegate()->mergeCells('K'.(string)($loca+11).':M'.(string)($loca+11));
                $event->sheet->getCell('K'.(string)($loca+11))->setValue('Ngày tháng năm __');

                $event->sheet->getDelegate()->mergeCells('K'.(string)($loca+12).':M'.(string)($loca+12));
                $event->sheet->getCell('K'.(string)($loca+12))->setValue('Trưởng Phòng Thiết Bị');
                $event->sheet->getDelegate()->getStyle('K'.(string)($loca+12))->getFont()->setBold(true);
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
                'ĐVT',
                'SL đề nghị',
                'Số lượng',
                'Loại xe',
                'Số xe',
                'Đơn vị vận chuyển',
                'Kho BCONS',
                '',
                '',
                'Công trình',
                'Thời gian dự kiến',
                '',
                'Công trình',
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
                'Xuất',
                'Nhập',
                'Luân chuyển',
                '',
                'Nơi đi',
                'Nơi đến',
                '',
            ],
        ];
    }

    public function array(): array
    {
        return $this->model;
    }
}
