<?php

namespace App\Exports\Reports\Customer;

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

class CustomerInvoiceWithdrawalsExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings  ,WithColumnWidths ,ShouldAutoSize,WithEvents ,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            __('app.INVOICEID'),__('app.INVOICEDATE'),__('app.TAXREGCODE'),__('app.VENDOR'),__('app.TOTALSALES'),
            __('app.INVOICEDISCOUNT'),__('app.ITEMDISCOUNT'),__('app.TAXTABLE'),__('app.NET'),
            __('app.Value added tax'),__('app.Other fees'),__('app.Items Discount After Tax'),__('app.TOTAL')
        ];
    }

    public function map($row): array
    {
        return [
            $row['invoice_number'],
            \date('d/m/Y' , strtotime($row['invoice_date'])),
            $row['customer_id'],
            $row['customer_name'],
            !empty($row['TotalSales']) ? $row['TotalSales'] : '0' ,
            !empty($row['InvoiceDiscount']) ? $row['InvoiceDiscount'] : '0',
            !empty($row['ItemsDiscount'])  ? $row['ItemsDiscount'] : '0',
            !empty($row['TaxTable']) ? $row['TaxTable'] : '0',
            !empty($row['Net']) ? $row['Net'] : '0',
            !empty($row['ValueAddedTax']) ? $row['ValueAddedTax'] : '0',
            !empty($row['OtherFees']) ? $row['OtherFees'] : '0',
            !empty($row['ItemsDiscountAfterTax']) ? $row['ItemsDiscountAfterTax'] : '0',
            !empty($row['Total']) ? $row['Total'] : '0',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_TEXT,
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
                $event->sheet->getStyle('A1:M1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:M1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:M1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 40,
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
