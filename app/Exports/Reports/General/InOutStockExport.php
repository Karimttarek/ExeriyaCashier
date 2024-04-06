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

class InOutStockExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings  ,WithColumnWidths ,ShouldAutoSize,WithEvents ,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            __('app.INVOICEDATE'),
            __('app.INVOICEID'),
            __('app.ITEM'),
            __('app.OUT'),
            __('app.IN'),
            __('app.BALANCE'),
        ];
    }

    public function map($row): array
    {
        return [
            \date('Y/m/d' , strtotime($row['invoice_date'])),
            $row['invoice_number'],
            $row['name'],
            !empty($row['OUTCOME']) ? $row['OUTCOME'] : '0' ,
            !empty($row['INCOME']) ? $row['INCOME'] : '0',
            $row['balance']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
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
                $event->sheet->getStyle('A1:F1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 50,
            'D' => 20,
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
