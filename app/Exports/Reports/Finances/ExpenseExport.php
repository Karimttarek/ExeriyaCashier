<?php

namespace App\Exports\Reports\Finances;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class ExpenseExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings ,WithColumnWidths ,ShouldAutoSize,WithEvents
{
    public function headings(): array
    {
        return [
            __('app.RECEIPTDATE'),
            __('app.RECEIPTID'),
            __('app.EXPENSENAME'),
            __('app.STATEMENT'),
            __('app.VALUE'),
        ];
    }

    public function map($row): array
    {
        return [
            \date('Y/m/d' , strtotime($row['receipt_date'])),
            $row['no'],
            $row['type_name'] ,
            $row['statement'],
            !empty($row['value']) ? $row['value'] : '0'
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
                $event->sheet->getStyle('A1:E1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:E1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 10,
            'C' => 50,
            'D' => 50,
            'E' => 30,
        ];
    }
}
