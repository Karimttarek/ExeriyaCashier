<?php

namespace App\Exports\Reports\General;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class DailyCashBoxExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings  ,WithColumnWidths ,ShouldAutoSize,WithEvents ,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            __('app.RECEIPTID'),
            __('app.RECEIPTDATE'),
            __('app.STATEMENT'),
            __('app.CUSTOMER'),
            __('app.IN'),
            __('app.OUT'),
            __('app.BALANCE'),
        ];
    }

    public function map($row): array
    {
        return [
            $row['no'],
            \date('Y/m/d' , strtotime($row['receipt_date'])),
            $row['statement'],
            $row['receiver_name'],
            !empty($row['INCOME']) ? $row['INCOME'] : '0' ,
            !empty($row['OUTCOME']) ? $row['OUTCOME'] : '0',
            $row['balance']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    $event->sheet->getDelegate()->setRightToLeft(true);
                }
                // Header Colors
                $event->sheet->getStyle('A1:G1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:G1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 50,
            'D' => 50,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 25,
            'N' => 20,

        ];
    }
}
