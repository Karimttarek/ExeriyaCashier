<?php

namespace App\DataTables\ItemAnalysis;

use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PurchaseDataTable extends DataTable
{
    private function Q(){

        return DB::table('invoicedetails')
                ->groupBy('invoicedetails.item','invoicedetails.invoice_type')
                ->select(LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name','invoicedetails.item','invoicedetails.created_at','invoicehead.invoice_date',
                DB::raw('CASE WHEN invoicedetails.invoice_type = 1 THEN SUM(invoicedetails.total_sales) / SUM(invoicedetails.qty) END as UNIT_PRICE'),
                DB::raw('CASE WHEN invoicedetails.invoice_type = 1 THEN SUM(invoicedetails.total_sales) END as TOTAL_SALES'),
                DB::raw('SUM(invoicedetails.qty) AS "COUNT" , AVG(invoicedetails.price) As "AVG" ,SUM(invoicedetails.total) AS "TOTAL" , (SUM(invoicedetails.tax) + SUM(invoicedetails.tax_table)) As "TAX" ,SUM(invoicedetails.discount) As "DISCOUNT" '),
                DB::raw('CASE WHEN invoicedetails.invoice_type = 1 THEN SUM(invoicedetails.total) END as VALUE')) //, CASE WHEN invoice_type = 2 THEN SUM(total) END as INCOME
                ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
                ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
                ->where('invoicedetails.invoice_type' ,'1')
                ->where('invoicehead.status' ,'!=','Invaild')
                ->orderBy('invoicehead.invoice_date','desc');
    }
     /**
     * Build the DataTable class.
     *
     *
     */
    public function dataTable()
    {
        $query = $this->Q();
        return datatables($query)
        ->filterColumn('name', function ($query , $keyword) {
            $sql = "products.name like ? OR products.name_ar like ?";
            return $query->whereRaw($sql, ["%{$keyword}%" ,"%{$keyword}%"]);
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
                    ->setTableId('sales-table')
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
                            this.api().columns([0]).every(function () {
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
                            COUNT = this.api()
                                .column(1, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            UNIT_PRICE = this.api()
                                .column(2, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            TOTAL_SALES = this.api()
                                .column(3, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            DISCOUNT = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            TAX = this.api()
                                .column(5, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            VALUE = this.api()
                                .column(6, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                                this.api().column(1).footer().innerHTML =
                                COUNT.toLocaleString('en-US');

                                this.api().column(2).footer().innerHTML =
                                UNIT_PRICE.toLocaleString('en-US');

                                this.api().column(3).footer().innerHTML =
                                TOTAL_SALES.toLocaleString('en-US');

                                this.api().column(4).footer().innerHTML =
                                DISCOUNT.toLocaleString('en-US');

                                this.api().column(5).footer().innerHTML =
                                TAX.toLocaleString('en-US');

                                this.api().column(6).footer().innerHTML =
                                VALUE.toLocaleString('en-US');
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
                'name' => 'name',
                'data' => 'name',
                'title' => __('app.ITEM'),
            ],
            [
                'name' => 'COUNT',
                'data' => 'COUNT',
                'title' => __('app.QTY'),
            ],
            [
                'name' => 'UNIT_PRICE',
                'data' => 'UNIT_PRICE',
                'title' => __('app.UNITPRICE'),
            ],
            [
                'name' => 'TOTAL_SALES',
                'data' => 'TOTAL_SALES',
                'title' => __('app.TOTALSALES'),
            ],
            [
                'name' => 'DISCOUNT',
                'data' => 'DISCOUNT',
                'title' => __('app.DISCOUNT'),
            ],
            [
                'name' => 'TAX',
                'data' => 'TAX',
                'title' => __('app.TOTALTAX'),
            ],
            [
                'name' => 'VALUE',
                'data' => 'VALUE',
                'title' => __('app.VALUE'),
            ],
        ];
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Purchase_' . date('YmdHis');
    }
}
