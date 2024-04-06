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

class ItemProfitsExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings  ,WithColumnWidths ,ShouldAutoSize,WithEvents ,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            __('app.DATE'),
            __('app.ITEM'),
            __('app.Total Sales Quantity'),
            __('app.Average Sales Price'),
            __('app.TOTALSALES'),
            __('app.Total Purchases Quantity'),
            __('app.Average Purchase Price'),
            __('app.Total Sales Cost'),
            __('app.Total Profit'),
        ];
    }

    public function map($row): array
    {
        return [
            \date('Y/m/d' , strtotime($row['invoice_date'])),
            $row['name'],
            !empty($row['SalesSumQty']) ? $row['SalesSumQty'] : '0',
            !empty($row['SellPriceAvg']) ?  $row['SellPriceAvg'] : '0' ,
            !empty($row['TotalSales']) ? $row['TotalSales'] : '0',
            !empty($row['PurSumQty']) ? $row['PurSumQty'] : '0',
            !empty($row['PurPriceAvg']) ? $row['PurPriceAvg'] : '0' ,
            !empty($row['TotalSalesCost']) ? $row['TotalSalesCost'] : '0',
            !empty($row['TotalProfit']) ? $row['TotalProfit'] : '0',
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    $event->sheet->getDelegate()->setRightToLeft(true);
                }
                // Header Colors
                $event->sheet->getStyle('A1:I1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:I1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 60,
            'C' => 20,
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
