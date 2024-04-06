<?php

namespace App\DataTables\Report\Customer;

use App\Exports\Reports\Customer\CustomerItemsWithdrawalsExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ItemWithdrawalDataTable extends DataTable
{
    protected string $exportClass = CustomerItemsWithdrawalsExport::class;
    protected string $printPreview = 'print.reps.customer.itemsWithdrawals';

    private function Q(){
        return DB::table('invoicehead')
            ->where('invoicehead.invoice_type' , 2)
            ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
            ->select('invoicehead.invoice_date','invoicehead.invoice_number','invoicehead.customer_id' ,'invoicehead.customer_name','invoicehead.customer_street',
                LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name',
                'invoicedetails.item_code','invoicedetails.unit_type','invoicedetails.qty','invoicedetails.price','invoicedetails.discount',
                DB::raw('( ( invoicedetails.qty * invoicedetails.price) - invoicedetails.discount  ) as net'),
                DB::raw('( ( ( ( invoicedetails.qty * invoicedetails.price) - invoicedetails.discount)  + invoicedetails.tax_table) / invoicedetails.qty ) as unitNetPriceAfterTableTax'),
                DB::raw('( ( ( invoicedetails.qty * invoicedetails.price) - invoicedetails.discount)  + invoicedetails.tax_table)  as netPriceIncludeTaxTable'),
                DB::raw('IF ( SUBSTRING_INDEX( invoicedetails.tax_type, ",", 1) = "T1" ,
                         SUBSTRING_INDEX( invoicedetails.taxvalue, ",", 1) , 0 )
                         as ValueAddedTax'),

                DB::raw("IF (SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.tax_type, ',', -1),',', -1) = 'T20' ,
                        IF ( SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.taxvalue, ',', -1),',', -1) = invoicedetails.taxvalue ,
                        0 , SUBSTRING_INDEX( SUBSTRING_INDEX( invoicedetails.taxvalue, ',', -1),',', -1)) , 0)
                            as OtherFees"),

                'invoicedetails.taxvalue','invoicedetails.tax_table',
                'invoicedetails.discount_after_tax','invoicedetails.total')
            ->leftJoin('invoicedetails' , 'invoicedetails.uuid' , '=' , 'invoicehead.uuid')
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            ->orderBy('invoicehead.invoice_date');
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
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
                // ->addColumn('invoicehead.customer_id', function ($query) {
                //     return $query->customer_id;
                // })
                ->filterColumn('customer_name', function ($query , $keyword) {
                    $sql = "invoicehead.customer_name like ?";
                    return $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('item_code', function ($query, $keyword) {
                    $sql = "invoicedetails.item_code like ?";
                    return $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('name', function ($query , $keyword) {
                    $sql = "products.name like ? OR products.name_ar like ?";
                    return $query->whereRaw($sql, ["%{$keyword}%" ,"%{$keyword}%"]);
                })
                ->filterColumn('unit_type', function ($query , $keyword) {
                    $sql = "invoicedetails.unit_type like ? ";
                    return $query->whereRaw($sql, ["%{$keyword}%" ]);
                })
                ->addColumn('invoicedetails.qty', function ($query) {
                    return $query->qty ?? 0;
                })
                ->addColumn('invoicedetails.price', function ($query) {
                    return $query->price ?? 0;
                })
                ->addColumn('invoicedetails.discount', function ($query) {
                    return $query->discount ?? 0;
                })
                ->addColumn('net', function ($query) {
                    return $query->net ?? 0 ;
                })
                ->addColumn('invoicedetails.tax_table', function ($query) {
                    return $query->tax_table ?? 0 ;
                })
                ->addColumn('unitNetPriceAfterTableTax', function ($query) {
                    return $query->unitNetPriceAfterTableTax ?? 0 ;
                })
                ->addColumn('netPriceIncludeTaxTable', function ($query) {
                    return $query->netPriceIncludeTaxTable  ?? 0 ;
                })
                ->addColumn('ValueAddedTax', function ($query) {
                    return $query->ValueAddedTax ?? 0 ;
                })
                ->addColumn('OtherFees', function ($query) {
                    return $query->OtherFees ?? 0 ;
                })
                ->addColumn('invoicedetails.discount_after_tax', function ($query) {
                    return $query->discount_after_tax ?? 0 ;
                })
                ->addColumn('invoicedetails.total', function ($query) {
                    return $query->total ?? 0 ;
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
                    ->setTableId('itemwithdrawal-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->lengthMenu([10, 25, 50, 100,500])
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make(['extend' =>'excel', 'text' => __('app.EXCEL')]),
                        Button::make(['extend' =>'csv','text' => __('app.CSV')]),
                        // Button::make(['extend' =>'pdf','text' => __('app.PDF')]),
                        Button::make(['extend' =>'print', 'text' => __('app.PRINT')]),
                        Button::make(['extend' =>'reset','text' => __('app.RESET')]),
                        Button::make(['extend' =>'reload','text' => __('app.RELOAD')])
                    ])
                    ->parameters([
                        // 'responsive' => true,
                        // 'autoWidth' => false,
                        'initComplete' => " function () {
                            this.api().columns([0,2,3,4,5]).every(function () {
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

                            // 5thColTotal
                            qty = this.api()
                                .column(6, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            price = this.api()
                                .column(7, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            discount = this.api()
                                .column(8, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            net = this.api()
                                .column(9, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            tax_table = this.api()
                                .column(10, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            unitNetPriceAfterTableTax = this.api()
                                .column(11, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            netPriceIncludeTaxTable = this.api()
                                .column(12, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            ValueAddedTax = this.api()
                                .column(13, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            OtherFees = this.api()
                                .column(14, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            discount_after_tax = this.api()
                                .column(15, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            total = this.api()
                                .column(16, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                this.api().column(6).footer().innerHTML =
                                qty.toLocaleString('en-US');

                                this.api().column(7).footer().innerHTML =
                                price.toLocaleString('en-US');

                                this.api().column(8).footer().innerHTML =
                                discount.toLocaleString('en-US');

                                this.api().column(9).footer().innerHTML =
                                net.toLocaleString('en-US');

                                this.api().column(10).footer().innerHTML =
                                tax_table.toLocaleString('en-US');

                                this.api().column(11).footer().innerHTML =
                                unitNetPriceAfterTableTax.toLocaleString('en-US');

                                this.api().column(12).footer().innerHTML =
                                netPriceIncludeTaxTable.toLocaleString('en-US');

                                this.api().column(13).footer().innerHTML =
                                ValueAddedTax.toLocaleString('en-US');

                                this.api().column(14).footer().innerHTML =
                                OtherFees.toLocaleString('en-US');

                                this.api().column(15).footer().innerHTML =
                                discount_after_tax.toLocaleString('en-US');

                                this.api().column(16).footer().innerHTML =
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
                'name' => 'customer_name',
                'data' => 'customer_name',
                'title' => __('app.CUSTOMERNAME'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'item_code',
                'data' => 'item_code',
                'title' => __('app.ITEMCODE'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'name',
                'data' => 'name',
                'title' => __('app.NAME'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'unit_type',
                'data' => 'unit_type',
                'title' => __('app.UNITTYPE'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'qty',
                'data' => 'qty',
                'title' => __('app.QTY'),
                'searchable' => false,
            ],
            [
                'name' => 'price',
                'data' => 'price',
                'title' => __('app.UNITPRICE'),
                'searchable' => false,
            ],
            [
                'name' => 'discount',
                'data' => 'discount',
                'title' => __('app.DISCOUNT'),
                'searchable' => false,
            ],
            [
                'name' => 'net',
                'data' => 'net',
                'title' => __('app.NETPRICE'),
                'searchable' => false,
            ],
            [
                'name' => 'tax_table',
                'data' => 'tax_table',
                'title' => __('app.TAXTABLE'),
                'searchable' => false,
            ],
            [
                'name' => 'unitNetPriceAfterTableTax',
                'data' => 'unitNetPriceAfterTableTax',
                'title' => __('app.Unit net price after table tax'),
                'searchable' => false,
            ],
            [
                'name' => 'netPriceIncludeTaxTable',
                'data' => 'netPriceIncludeTaxTable',
                'title' => __('app.Net price includes tax table'),
                'searchable' => false,
            ],
            [
                'name' => 'ValueAddedTax',
                'data' => 'ValueAddedTax',
                'title' => __('app.Value added tax'),
                'searchable' => false,
            ],
            [
                'name' => 'OtherFees',
                'data' => 'OtherFees',
                'title' => __('app.Other fees'),
                'searchable' => false,
            ],
            [
                'name' => 'discount_after_tax',
                'data' => 'discount_after_tax',
                'title' => __('app.DISCOUNTAFTERTAX'),
                'searchable' => false,
            ],
            [
                'name' => 'total',
                'data' => 'total',
                'title' => __('app.TOTAL'),
                'searchable' => false,
            ],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CustomersItemWithdrawal_' . date('YmdHis');
    }
}
