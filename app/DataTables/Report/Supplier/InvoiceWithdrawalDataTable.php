<?php

namespace App\DataTables\Report\Supplier;

use App\Exports\Reports\Supplier\SupplierInvoiceWithdrawalsExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class InvoiceWithdrawalDataTable extends DataTable
{
    protected string $exportClass = SupplierInvoiceWithdrawalsExport::class;
    protected string $printPreview = 'print.reps.supplier.invoicesWithdrawals';

    private function Q(){
        return DB::table('invoicehead')
        ->where('invoicehead.invoice_type' , 1)
        ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
        ->select(
            'invoicehead.invoice_number' ,'invoicehead.invoice_date',
            'invoicehead.issuer_id','invoicehead.issuer_name',
            'invoicehead.total_sales as TotalSales' ,'invoicehead.invoice_discount as InvoiceDiscount' ,'invoicehead.invoice_tax as InvoiceTax' ,
            'invoicehead.total_net as Net' ,
            'invoicehead.total_items_discount as ItemsDiscount', 'invoicehead.discount_after_tax as ItemsDiscountAfterTax',

            DB::raw('SUM(SUBSTRING_INDEX( invoicedetails.taxvalue, ",", 1))   as ValueAddedTax'),

            DB::raw(" IF (SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.tax_type, ',', -1),',', -1) = 'T20' ,
                    SUM( IF ( SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.taxvalue, ',', -1),',', -1) =  invoicedetails.taxvalue ,
                    0 , SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.taxvalue, ',', -1),',', -1)) ) , 0 )
                    as OtherFees"),

            'invoicehead.total_tax','invoicehead.total_tax_table as TaxTable','invoicehead.total as Total'
        )
        ->leftJoin('invoicedetails' , 'invoicedetails.uuid' , '=' , 'invoicehead.uuid')
        ->orderBy('invoicehead.invoice_date' )
        ->groupBy('invoicehead.invoice_number');
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable()
    {
        $query = $this->Q();
        return datatables($query)
                ->addColumn('invoicehead.invoice_number', function ($query) {
                    return $query->invoice_number;
                })
                ->filterColumn('invoice_date', function ($query , $date) {
                    $date = explode( '-' , $date);
                    $sql = "DATE(invoice_date) >= ? and DATE(invoice_date) <= ?";
                    $query->whereRaw($sql, [date('Y-m-d' , strtotime($date[0])) , date('Y-m-d' , strtotime($date[1]))]);
                })
                ->addColumn('invoicehead.issuer_id', function ($query) {
                    return $query->issuer_id;
                })
                ->addColumn('invoicehead.issuer_name', function ($query) {
                    return $query->issuer_name;
                })
                ->addColumn('TotalSales', function ($query) {
                    return $query->TotalSales ?? 0;
                })
                ->addColumn('InvoiceDiscount', function ($query) {
                    return $query->InvoiceDiscount ?? 0;
                })
                ->addColumn('ItemsDiscount', function ($query) {
                    return $query->ItemsDiscount ?? 0;
                })
                ->addColumn('TaxTable', function ($query) {
                    return $query->TaxTable ?? 0;
                })
                ->addColumn('Net', function ($query) {
                    return $query->Net ?? 0;
                })
                ->addColumn('ValueAddedTax', function ($query) {
                    return $query->ValueAddedTax ?? 0 ;
                })
                ->addColumn('OtherFees', function ($query) {
                    return $query->OtherFees ?? 0;
                })
                ->addColumn('ItemsDiscountAfterTax', function ($query) {
                    return $query->ItemsDiscountAfterTax ?? 0;
                })
                ->addColumn('Total', function ($query) {
                    return $query->Total ?? 0;
                });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query()
    {
        return $this->applyScopes($this->Q());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('invoicewithdrawal-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->lengthMenu([10, 25, 50, 100,500])
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->responsive(true)
                    ->autoWidth(false)
                    ->buttons([
                        Button::make(['extend' =>'excel', 'text' => __('app.EXCEL')]),
                        Button::make(['extend' =>'csv','text' => __('app.CSV')]),
                        // Button::make(['extend' =>'pdf','text' => __('app.PDF')]),
                        Button::make(['extend' =>'print', 'text' => __('app.PRINT')]),
                        Button::make(['extend' =>'reset','text' => __('app.RESET')]),
                        Button::make(['extend' =>'reload','text' => __('app.RELOAD')])
                    ])->parameters([
                        'initComplete' => " function () {
                            this.api().columns([0,2,3]).every(function () {
                                var column = this;
                                var input = document.createElement(\"input\");
                                input.type = 'search';
                                input.setAttribute('class', 'form-control form-control-sm bg-white');
                                input.setAttribute('placeholder', 'Search');
                                $(input).appendTo($(column.footer()).empty())
                                .on('input', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns([1]).every(function () {
                                var column = this;
                                var input = document.createElement(\"input\");

                                input.setAttribute('class', 'form-control form-control-sm bg-white daterange');
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }",
                        'drawCallback' => " function () {
                            // Remove the formatting to get integer data for summation
                            let intVal = function (i) {
                                return typeof i === 'string'
                                    ? i.replace(/[\$,]/g, '') * 1
                                    : typeof i === 'number'
                                    ? i
                                    : 0;
                            };

                            // fourthColTotal
                            totalSales = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            invoiceDiscount = this.api()
                                .column(5, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            itemdiscount = this.api()
                                .column(6, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            taxTable = this.api()
                                .column(7, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            net = this.api()
                                .column(8, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            valueAddedTax = this.api()
                                .column(9, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            otherFees = this.api()
                                .column(10, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            itemDiscountAfterTax = this.api()
                                .column(11, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            total = this.api()
                                .column(12, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                this.api().column(4).footer().innerHTML =
                                totalSales.toLocaleString('en-US');

                                this.api().column(5).footer().innerHTML =
                                invoiceDiscount.toLocaleString('en-US');

                                this.api().column(6).footer().innerHTML =
                                itemdiscount.toLocaleString('en-US');

                                this.api().column(7).footer().innerHTML =
                                taxTable.toLocaleString('en-US');

                                this.api().column(8).footer().innerHTML =
                                net.toLocaleString('en-US');

                                this.api().column(9).footer().innerHTML =
                                valueAddedTax.toLocaleString('en-US');

                                this.api().column(10).footer().innerHTML =
                                otherFees.toLocaleString('en-US');

                                this.api().column(11).footer().innerHTML =
                                itemDiscountAfterTax.toLocaleString('en-US');

                                this.api().column(12).footer().innerHTML =
                                total.toLocaleString('en-US');
                        }"

                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            [
                'name' => 'invoice_number',
                'data' => 'invoice_number',
                'title' => __('app.INVOICEID'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'invoice_date',
                'data' => 'invoice_date',
                'title' => __('app.INVOICEDATE'),
                'orderable' => true
            ],
            [
                'name' => 'issuer_id',
                'data' => 'issuer_id',
                'title' => __('app.TAXREGCODE'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'issuer_name',
                'data' => 'issuer_name',
                'title' => __('app.SUPPLIERNAME'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'TotalSales',
                'data' => 'TotalSales',
                'title' => __('app.TOTALPURCHASE'),
                'orderable' => true
            ],
            [
                'name' => 'InvoiceDiscount',
                'data' => 'InvoiceDiscount',
                'title' => __('app.INVOICEDISCOUNT'),
                'orderable' => true
            ],
            [
                'name' => 'ItemsDiscount',
                'data' => 'ItemsDiscount',
                'title' => __('app.ITEMDISCOUNT'),
                'orderable' => true
            ],
            [
                'name' => 'TaxTable',
                'data' => 'TaxTable',
                'title' => __('app.TAXTABLE'),
                'orderable' => true
            ],
            [
                'name' => 'Net',
                'data' => 'Net',
                'title' => __('app.NET'),
                'orderable' => true
            ],
            [
                'name' => 'ValueAddedTax',
                'data' => 'ValueAddedTax',
                'title' => __('app.Value added tax'),
                'orderable' => true
            ],
            [
                'name' => 'OtherFees',
                'data' => 'OtherFees',
                'title' => __('app.Other fees'),
                'orderable' => true
            ],
            [
                'name' => 'ItemsDiscountAfterTax',
                'data' => 'ItemsDiscountAfterTax',
                'title' => __('app.Items Discount After Tax'),
                'orderable' => true
            ],
            [
                'name' => 'Total',
                'data' => 'Total',
                'title' => __('app.TOTAL'),
                'orderable' => true
            ]
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SupplierInvoiceWithdrawal_' . date('YmdHis');
    }
}
