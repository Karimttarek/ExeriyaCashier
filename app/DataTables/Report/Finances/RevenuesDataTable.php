<?php

namespace App\DataTables\Report\Finances;

use App\Exports\Reports\Finances\RevenuesExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class RevenuesDataTable extends DataTable
{
    protected string $exportClass = RevenuesExport::class;
    protected string $printPreview = 'print.reps.general.revenues';

    private function Q(){
        return DB::table('receipt_details')->where('receipt_type',12)
            ->select('no','uuid','receipt_date','statement','type_name','value')
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
            ->filterColumn('receipt_date', function ($query , $date) {
                $date = explode( '-' , $date);
                $sql = "DATE(receipt_date) >= ? and DATE(receipt_date) <= ?";
                $query->whereRaw($sql, [date('Y-m-d' , strtotime($date[0])) , date('Y-m-d' , strtotime($date[1]))]);
            })
            ->addColumn('value', function ($query) {
                return $query->value ?? 0;
                // return number_format( , 5);
            })
            ->addColumn('Action' , function ($query){
                return '<a href="' .route('Finances.Revenues.edit',$query->uuid).'" title="'.__('app.Redirect To Receipt').'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>'.__('app.View').'</a> '   ;
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
            ->setTableId('revenues-table')
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
                            this.api().columns([1,2,3]).every(function () {
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

                            // thirdColTotal
                            thirdColTotal = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                this.api().column(4).footer().innerHTML =
                                thirdColTotal.toLocaleString('en-US');
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
                'name' => 'receipt_date',
                'data' => 'receipt_date',
                'title' => __('app.RECEIPTDATE'),
                'orderable' => true
            ],
            [
                'name' => 'no',
                'data' => 'no',
                'title' => __('app.RECEIPTID'),
                'orderable' => true
            ],
            [
                'name' => 'type_name',
                'data' => 'type_name',
                'title' => __('app.REVENUENAME'),
                'searchable' => true,
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
                'name' => 'value',
                'data' => 'value',
                'title' => __('app.VALUE'),
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
        return 'Revenues_' . date('YmdHis');
    }
}
