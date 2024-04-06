<?php

namespace App\DataTables\Report\Customer;

use App\Exports\Reports\Customer\CustomerCardExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class CustomerCardDataTable extends DataTable
{

    protected string $exportClass = CustomerCardExport::class;
    protected string $printPreview = 'print.reps.customer.card';

    private function Q(){
        return DB::table('receipts')
            ->select('receipts.receipt_date','receipts.statement','receipts.receiver_name',
                DB::raw('CASE WHEN receipts.receipt_type IN(1,7,11) THEN value END as credit, CASE WHEN receipts.receipt_type IN (3,8) THEN value END as debit'),
                DB::raw('CONCAT_WS(" ", receipts.supplier_name , receipts.receiver_name, receipts.customer_name)  as receiver'),
                DB::raw('SUM(CASE WHEN receipts.receipt_type IN (3,8) THEN value ELSE 0 END - CASE WHEN receipts.receipt_type IN (1,7,11) THEN value ELSE 0 END ) OVER (ORDER BY receipts.receipt_date ,receipts.id) balance'))
            ->leftJoin('invoicehead' ,'invoicehead.uuid' , '=' ,'receipts.uuid')
            ->whereIn('receipt_type' ,[1,3,7,8,11])
            ->orderBy('receipts.receipt_date' )
            ->orderBy('receipts.id');
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
//                $date = explode( '-' , $date);
            $sql = "DATE(receipt_date) <= ? ";
            $query->whereRaw($sql, [date('Y-m-d' , strtotime($date))]);
        })
        ->addColumn('receipts.receiver_name', function ($query) {
            return $query->receiver_name;
        })
        ->addColumn('receipts.statement', function ($query) {
            return $query->statement;
        })
        ->addColumn('debit', function ($query) {
            return $query->debit ?? 0;
        })
        ->addColumn('credit', function ($query) {
            return $query->credit ?? 0;
        })
        ->addColumn('balance', function ($query) {
            return number_format($query->balance ,5) ?? 0;
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
                    ->setTableId('customerCard-table')
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
                'name' => 'receipt_date',
                'data' => 'receipt_date',
                'title' => __('app.DATE'),
            ],
            [
                'name' => 'statement',
                'data' => 'statement',
                'title' => __('app.STATEMENT'),
            ],
            [
                'name' => 'receiver_name',
                'data' => 'receiver_name',
                'title' => __('app.RECEIVER'),
            ],
            [
                'name' => 'debit',
                'data' => 'debit',
                'title' => __('app.DEBIT'),
            ],
            [
                'name' => 'credit',
                'data' => 'credit',
                'title' => __('app.CREDIT'),
            ],
            [
                'name' => 'balance',
                'data' => 'balance',
                'title' => __('app.BALANCE'),
            ],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CustomerCard_' . date('YmdHis');
    }
}
