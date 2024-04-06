<?php

namespace App\Exports\Reports\Supplier;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class SupplierItemsWithdrawalsExport extends DataTablesCollectionExport implements FromCollection , WithMapping ,WithHeadings  ,WithColumnWidths ,ShouldAutoSize,WithEvents ,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            __('app.INVOICEDATE'), __('app.INVOICEID'), __('app.TAXREGORNID') ,__('app.SUPPLIER') ,__('app.ADDRESS'),__('app.ITEMCODE'),
            __('app.NAME')  ,__('app.QTY'),__('app.UNITTYPE') ,__('app.UNITPRICE'),__('app.DISCOUNT'), __('app.NETPRICE'),
            __('app.TAXTABLE'),__('app.Unit net price after table tax'),__('app.Net price includes tax table'),__('app.Value added tax'),
            __('app.Other fees'),__('app.DISCOUNTAFTERTAX'),__('app.TOTAL')
        ];
    }

    public function map($row): array
    {
        return [
            Date::dateTimeToExcel(new \DateTime($row['invoice_date'])),
//            \date('d/m/Y' , strtotime($row['invoice_date'])),
            $row['invoice_number'],
            $row['issuer_id'],
            $row['issuer_name'],
            $row['issuer_street'],
            str_contains($row['item_code'], 'EG-') ? substr($row['item_code'], strrpos($row['item_code'], '-') + 1) : $row['item_code'],
            $row['name'],
            $row['qty'],
            $row['unit_type'],
            $row['price'],
            !empty($row['discount']) ? $row['discount'] : '0',
            !empty($row['net']) ? $row['net'] : '0',
            !empty($row['tax_table']) ? $row['tax_table'] : '0',
            !empty($row['unitNetPriceAfterTableTax']) ? $row['unitNetPriceAfterTableTax'] : '0',
            !empty($row['netPriceIncludeTaxTable']) ? $row['netPriceIncludeTaxTable'] : '0',
            !empty($row['ValueAddedTax']) ? $row['ValueAddedTax'] : '0',
            !empty($row['OtherFees']) ? $row['OtherFees'] : '0',
            !empty($row['discount_after_tax']) ? $row['discount_after_tax'] : '0',
            !empty($row['total']) ? $row['total'] : '0',

        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
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
                $event->sheet->getStyle('A1:T1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '95B3D7'],]);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1:T1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:T1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 15,
            'D' => 35,
            'E' => 35,
            'F' => 15,
            'G' => 40,
            'H' => 10,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'M' => 30,
            'N' => 30,
            'O' => 25,
            'p' => 20,
            'Q' => 15,
            'R' => 15,
            'T' => 15,
        ];
    }
}
