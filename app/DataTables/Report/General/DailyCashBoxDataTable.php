<?php

namespace App\DataTables\Report\General;

use App\Exports\Reports\General\DailyCashBoxExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DailyCashBoxDataTable extends DataTable
{

    protected string $exportClass = DailyCashBoxExport::class;
    protected string $printPreview = 'print.reps.general.dailyCashBox';
    private string $route = '';
    private function Q(){
        return DB::table('receipts')
            ->whereIn('receipt_type' ,[1,2,5,8,9,10,11,12,13])
            ->select('uuid','no','receipt_date','statement','customer_name','supplier_name','exp_code',
                'exp_name','receiver_name','receipt_type',
                DB::raw('CONCAT_WS(" " ,receiver_name,customer_name ,supplier_name ,exp_name ) as receiver'),
                DB::raw('CASE WHEN receipt_type IN(1,9,11,12,13) THEN value END as INCOME,
                CASE WHEN receipt_type IN(2,5,8,10) THEN value END as OUTCOME'),
                DB::raw('SUM(CASE WHEN receipt_type IN (1,9,11,12,13) THEN value ELSE 0 END - CASE WHEN receipt_type IN (2,5,8,10) THEN  value ELSE 0 END ) OVER (ORDER BY receipt_date ,id) balance'))
            ->orderBy('receipt_date');
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
            ->filterColumn('no', function($query, $keyword) {
                $sql = "no = ?";
                $query->whereRaw($sql, ["{$keyword}"]);
            })
            ->filterColumn('receipt_date', function ($query , $date) {
//                $date = explode( '-' , $date);
                $sql = "DATE(receipt_date) <= ? ";
                $query->whereRaw($sql, [date('Y-m-d' , strtotime($date))]);
            })
            ->addColumn('INCOME', function ($query) {
                return $query->INCOME ?? 0;
            })
            ->addColumn('OUTCOME', function ($query) {
                return $query->OUTCOME ?? 0;
            })
            ->addColumn('balance', function ($query) {
                return number_format($query->balance ,5) ?? 0;
            })
            ->addColumn('Action' , function ($query){
                in_array($query->receipt_type,[1,9,11]) ? $this->route = 'Voucher.edit' : $this->route = 'Cash.edit';
                return '<a href="' .route($this->route,$query->uuid).'" title="'.__('app.Redirect To Receipt').'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>'.__('app.View').'</a> '   ;
            })
            ->rawColumns(['Action', 'action']);
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
                    ->setTableId('dailycashbox-table')
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
                        var api = this.api();
                            // Remove the formatting to get integer data for summation
                            let intVal = function (i) {
                                return typeof i === 'string'
                                    ? i.replace(/[\$,]/g, '') * 1
                                    : typeof i === 'number'
                                    ? i
                                    : 0;
                            };

                            // thirdColTotal
                            thirdColTotal = api
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            fourthColTotal = api
                                .column(5, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                api.column(4).footer().innerHTML =
                                thirdColTotal.toLocaleString('en-US');

                                api.column(5).footer().innerHTML =
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
                'name' => 'no',
                'data' => 'no',
                'title' => __('app.RECEIPTID'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'receipt_date',
                'data' => 'receipt_date',
                'title' => __('app.RECEIPTDATE'),
                'orderable' => true
            ],
            [
                'name' => 'statement',
                'data' => 'statement',
                'title' => __('app.STATEMENT'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'receiver_name',
                'data' => 'receiver_name',
                'title' => __('app.RECEIVER'),
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
            ],
            [
                'name' => 'Action',
                'data' => 'Action',
                'title' => '*',
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
                'exportable' => false,
            ],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DailyCashBox_' . date('YmdHis');
    }
}
