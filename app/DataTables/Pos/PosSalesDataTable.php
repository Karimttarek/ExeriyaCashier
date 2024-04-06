<?php

namespace App\DataTables\Pos;

use App\Exports\Reports\Customer\CustomerItemsWithdrawalsExport;
use App\Exports\Reports\Pos\PosSales;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PosSalesDataTable extends DataTable
{
    protected string $exportClass = PosSales::class;
    protected string $printPreview = 'print.reps.pos.posSales';

    private string $qty = '
     CASE WHEN
            invoicedetails.invoice_type IN(5) THEN
            CASE WHEN
                    invoicedetails.unit_type = products.first_unit_type THEN invoicedetails.qty
                    WHEN
                    invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * IFNULL(products.second_unit_qty ,1)
                    WHEN
                    invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * IFNULL(products.second_unit_qty ,1)
            END ELSE 0 END
        -
    CASE WHEN
        invoicedetails.invoice_type IN(6) THEN
            CASE WHEN
                invoicedetails.unit_type = products.first_unit_type THEN invoicedetails.qty
                WHEN
                invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * IFNULL(products.second_unit_qty ,1)
                WHEN
                invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * IFNULL(products.third_unit_qty ,1) ) * IFNULL(products.second_unit_qty ,1)
            END ELSE 0 END
    ';

    private function Q(){
        return DB::table('invoicehead')
            ->whereIn('invoicehead.invoice_type' , [5,6])
            ->select('invoicehead.invoice_date','invoicedetails.unit_type',
                LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name',
                DB::raw($this->qty .' as qty'),

                DB::raw('invoicedetails.price as price'),
                DB::raw('invoicedetails.discount as discount'),
                DB::raw('CASE WHEN
                            invoicedetails.invoice_type IN(5) THEN
                                invoicedetails.total ELSE -invoicedetails.total END
                         as total'),)

            ->leftJoin('invoicedetails' , 'invoicedetails.uuid' , '=' , 'invoicehead.uuid')
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            // ->groupBy('invoicedetails.item')
            ->orderBy('invoicehead.invoice_date');
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables($query)
                // ->addColumn('invoicehead.invoice_number', function ($query) {
                //     return $query->invoice_number;
                // })
                ->filterColumn('invoice_date', function ($query , $date) {
                    $date = explode( '-' , $date);
                    $sql = "DATE(invoice_date) >= ? and DATE(invoice_date) <= ?";
                    $query->whereRaw($sql, [date('Y-m-d' , strtotime($date[0])) , date('Y-m-d' , strtotime($date[1]))]);
                })
                ->filterColumn('name', function ($query , $keyword) {
                    $sql = "products.name like ? OR products.name_ar like ?";
                    return $query->whereRaw($sql, ["%{$keyword}%" ,"%{$keyword}%"]);
                })
                ->addColumn('qty', function ($query) {
                    return $query->qty ?? 0;
                    // return number_format( , 2);
                })
                ->filterColumn('unit_type', function ($query , $keyword) {
                    $sql = "invoicedetails.unit_type like ?";
                    return $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('price', function ($query) {
                    return $query->price ?? 0;
                })
                ->addColumn('discount', function ($query) {
                    return $query->discount ?? 0;
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
                    ->setTableId('PosSales-table')
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
                            this.api().columns([1,3]).every(function () {
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
                            this.api().columns([0]).every(function () {
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
                                .column(2, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            price = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            discount = this.api()
                                .column(5, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            net = this.api()
                                .column(6, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                this.api().column(2).footer().innerHTML =
                                qty.toLocaleString('en-US');

                                this.api().column(4).footer().innerHTML =
                                price.toLocaleString('en-US');

                                this.api().column(5).footer().innerHTML =
                                discount.toLocaleString('en-US');

                                this.api().column(6).footer().innerHTML =
                                net.toLocaleString('en-US');
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
                'name' => 'invoice_date',
                'data' => 'invoice_date',
                'title' => __('app.DATE'),
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
                'name' => 'qty',
                'data' => 'qty',
                'title' => __('app.QTY'),
                'searchable' => false,
            ],
            [
                'name' => 'unit_type',
                'data' => 'unit_type',
                'title' => __('app.UNITTYPE'),
                'searchable' => true,
                'orderable' => true
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
                'name' => 'total',
                'data' => 'total',
                'title' => __('app.VALUE'),
                'searchable' => false,
            ],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PosSales_' . date('YmdHis');
    }
}
