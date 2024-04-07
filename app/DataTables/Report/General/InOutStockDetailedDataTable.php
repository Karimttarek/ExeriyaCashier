<?php

namespace App\DataTables\Report\General;

use App\Exports\Reports\General\InOutStockExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class InOutStockDetailedDataTable extends DataTable
{

    protected string $exportClass = InOutStockExport::class;
    protected string $printPreview = 'print.reps.general.inOutStock';

    private string $route = '';
    private function Q(){

        return DB::table('invoicedetails')
//                ->whereBetween('invoicehead.invoice_date',[now()->startOfYear() ,now()->endOfYear()])
                ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
                ->select('invoicedetails.uuid','invoicedetails.invoice_number','invoicehead.invoice_date','invoicedetails.invoice_type',
                    'manufacturs.child_name','invoicedetails.created_at',
                    'products.second_unit_type','products.second_unit_qty','products.third_unit_type','products.third_unit_qty',
                    LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name' ,
                    DB::raw('CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                                    CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                    WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                    ELSE invoicedetails.qty END
                                END as INCOME'),

                    DB::raw('CASE WHEN invoicedetails.invoice_type IN(2,3,5) THEN
                                    CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                    WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                    ELSE invoicedetails.qty * manufacturs.qty END
                                END as OUTCOME'),

                    DB::raw("
                    SUM( CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                            CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                            WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty ELSE invoicedetails.qty END
                        ELSE -
                         CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                        WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                        ELSE invoicedetails.qty * manufacturs.qty END END)
                        OVER(PARTITION BY manufacturs.child_name ORDER BY invoicehead.invoice_date ,invoicehead.invoice_number ) balance")
                )
                ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
                ->leftJoin('products' , 'invoicedetails.item_uuid' ,'=','products.uuid')
                ->rightJoin('manufacturs' , 'manufacturs.parent_uuid' , '=' , 'invoicedetails.item_uuid')
                ->orderBy('invoicehead.invoice_date');

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
            ->filterColumn('invoicehead.invoice_date', function ($query , $date) {
                //                $date = explode( '-' , $date);
                $sql = "DATE(invoice_date) <= ?";
                $query->whereRaw($sql, [date('Y-m-d' , strtotime($date))]);
            })
            ->addColumn('invoice_number', function ($query) {
                return $query->invoice_number  ;
            })
            ->addColumn('name', function ($query) {
                return $query->name;
            })
            ->addColumn('INCOME', function ($query) {
                return $query->INCOME ?? 0;
                // return number_format( , 2);
            })
            ->addColumn('OUTCOME', function ($query) {
                return $query->OUTCOME ?? 0 ;
                // return number_format($query->OUTCOME , 2);
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
                    ->setTableId('inoutstock-table')
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
                        'initComplete' => " function () {
                            this.api().columns([1,2]).every(function () {
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
                                input.setAttribute('type' ,'date');
                                input.setAttribute('value' ,new Date().toISOString().split('T')[0]);
                                input.setAttribute('class', 'form-control form-control-sm bg-white');
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

                            // thirdColTotal
                            thirdColTotal = this.api()
                                .column(3, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            fourthColTotal = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);


                                this.api().column(3).footer().innerHTML =
                                thirdColTotal.toLocaleString('en-US');

                                this.api().column(4).footer().innerHTML =
                                fourthColTotal.toLocaleString('en-US');

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
                'name' => 'invoicehead.invoice_date',
                'data' => 'invoice_date',
                'title' => __('app.INVOICEDATE'),
                'orderable' => true
            ],
            [
                'name' => 'invoice_number',
                'data' => 'invoice_number',
                'title' => __('app.INVOICEID'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name',
                'data' => 'name',
                'title' => __('app.ITEM'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'INCOME',
                'data' => 'INCOME',
                'title' => __('app.IN'),
            ],
            [
                'name' => 'OUTCOME',
                'data' => 'OUTCOME',
                'title' => __('app.OUT'),
            ],
            [
                'name' => 'balance',
                'data' => 'balance',
                'title' => __('app.BALANCE')
            ]

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'InOutStock_' . date('YmdHis');
    }
}
