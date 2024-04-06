<?php

namespace App\DataTables\Report\General;

use App\Exports\Reports\General\ItemProfitsExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ItemProfitsDataTable extends DataTable
{

    protected string $exportClass = ItemProfitsExport::class;
    protected string $printPreview = 'print.reps.general.itemprofits';

    private function Q(){

        $PurSumQty =
        ' SUM( CASE WHEN invoicehead.invoice_type = 1 THEN
            CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
            WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
            ELSE invoicedetails.qty END
         ELSE 0 END ) ';
        $PurPriceAvg = 'TRUNCATE ( COALESCE ( SUM( CASE WHEN invoicehead.invoice_type = 1 THEN invoicedetails.price * invoicedetails.qty ELSE 0 END ) / ' . $PurSumQty . ' ,0 ) ,5)' ;

//        $PurPriceAvg = '( SELECT  SUM(  invoicedetails.price * invoicedetails.qty  ) / ' . $PurSumQty. '  WHERE invoicehead.invoice_type = 1 AND invoicehead.invoice_date BETWEEN "'. date('Y-m-d', strtotime('1st January This Year')) .'" AND NOW() )';

        $SalesSumQty =
        'SUM( CASE WHEN invoicehead.invoice_type IN (2,5) THEN
            CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
            WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
            ELSE invoicedetails.qty END
         ELSE 0 END )';
        $SalesPriceAvg = 'TRUNCATE (COALESCE ( SUM( CASE WHEN invoicehead.invoice_type IN (2,5) THEN invoicedetails.price * invoicedetails.qty ELSE 0 END ) / ' . $SalesSumQty . ' ,0 ),5)' ;


        return DB::table('invoicedetails')
            ->groupBy('invoicedetails.item')
            ->select(LaravelLocalization::getCurrentLocale() == 'en' ? 'products.name as name' : 'products.name_ar as name','invoicehead.invoice_date',


                DB::raw($SalesSumQty .'AS SalesSumQty'),
                DB::raw($SalesPriceAvg . 'AS SellPriceAvg'),

                DB::raw('TRUNCATE ('.$SalesSumQty . '*' . $SalesPriceAvg . ',5 )AS TotalSales'),

                DB::raw($PurSumQty .'AS PurSumQty'),
                DB::raw($PurPriceAvg . '  AS PurPriceAvg'),

                DB::raw('TRUNCATE ('.$PurPriceAvg . '*' . $SalesSumQty.',5 )AS TotalSalesCost'),

                DB::raw('TRUNCATE ('.$SalesSumQty . '*' . $SalesPriceAvg  . '-'  .$PurPriceAvg . '*' . $SalesSumQty . '  ,5)AS TotalProfit'))

            ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
            ->leftJoin('products' ,'products.uuid' ,'=' ,'invoicedetails.item_uuid')
            ->whereIn('invoicedetails.invoice_type' ,['1','2','5'])
            ->whereIn('invoicehead.status' ,['Pending','Valid','Stocktaked'])
            ->whereNotIn('invoicehead.status', ['Invalid','Canceled'])
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
            ->filterColumn('invoicehead.invoice_date', function ($query , $date) {
//                $date = explode( '-' , $date);
                $sql = "DATE(invoice_date) <= ?";
                $query->whereRaw($sql, [date('Y-m-d' , strtotime($date))]);
            })
            ->filterColumn('name', function ($query , $keyword) {
                $sql = "products.name like ? OR products.name_ar like ?";
                return $query->whereRaw($sql, ["%{$keyword}%" ,"%{$keyword}%"]);
            })
            ->addColumn('PurPriceAvg' , function ($query) {
                return round($query->PurPriceAvg ,5);
            })
            ->addColumn('TotalSalesCost' , function ($query) {
                return round($query->TotalSalesCost ,5);
            })
            ->addColumn('TotalProfit' , function ($query) {
                return round($query->TotalProfit ,5);
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
                    ->setTableId('itemprofits-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->lengthMenu([10, 25, 50, 100,500])
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make(['extend' =>'excel', 'text' => __('app.EXCEL')]),
                        Button::make(['extend' =>'csv','text' => __('app.CSV')]),
                        Button::make(['extend' =>'print', 'text' => __('app.PRINT')]),
                        Button::make(['extend' =>'reset','text' => __('app.RESET')]),
                        Button::make(['extend' =>'reload','text' => __('app.RELOAD')])
                    ])
                    ->parameters([
                        'initComplete' => " function () {
                            this.api().columns([1]).every(function () {
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

                            // 5thColTotal
                            SalesSumQty = this.api()
                                .column(2, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // fourthColTotal
                            SellPriceAvg = this.api()
                                .column(3, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            TotalSales = this.api()
                                .column(4, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            PurSumQty = this.api()
                                .column(5, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            PurPriceAvg = this.api()
                                .column(6, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            TotalSalesCost = this.api()
                                .column(7, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);

                            // 5thColTotal
                            TotalProfit = this.api()
                                .column(8, { page: 'current' }).data()
                                .reduce((a, b) => intVal(a) + intVal(b), 0);


                                this.api().column(2).footer().innerHTML =
                                SalesSumQty.toLocaleString('en-US');

                                this.api().column(3).footer().innerHTML =
                                SellPriceAvg.toLocaleString('en-US');

                                this.api().column(4).footer().innerHTML =
                                TotalSales.toLocaleString('en-US');

                                this.api().column(5).footer().innerHTML =
                                PurSumQty.toLocaleString('en-US');

                                this.api().column(6).footer().innerHTML =
                                PurPriceAvg.toLocaleString('en-US');

                                this.api().column(7).footer().innerHTML =
                                TotalSalesCost.toLocaleString('en-US');

                                this.api().column(8).footer().innerHTML =
                                TotalProfit.toLocaleString('en-US');

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
                'title' => __('app.DATE'),
                'orderable' => true
            ],
            [
                'name' => 'name',
                'data' => 'name',
                'title' => __('app.ITEM'),
                'searchable' => true,
                'orderable' => true
            ],
            [
                'name' => 'SalesSumQty',
                'data' => 'SalesSumQty',
                'title' => __('app.Total Sales Quantity'),
                'searchable' => false,
                'orderable' => true
            ],
            [
                'name' => 'SellPriceAvg',
                'data' => 'SellPriceAvg',
                'title' => __('app.Average Sales Price'),
                'searchable' => false,
            ],
            [
                'name' => 'TotalSales',
                'data' => 'TotalSales',
                'title' => __('app.TOTALSALES'),
                'searchable' => false,
            ],
            [
                'name' => 'PurSumQty',
                'data' => 'PurSumQty',
                'title' => __('app.Total Purchases Quantity'),
                'searchable' => false,
            ],
            [
                'name' => 'PurPriceAvg',
                'data' => 'PurPriceAvg',
                'title' => __('app.Average Purchase Price'),
                'searchable' => false,
            ],
            [
                'name' => 'TotalSalesCost',
                'data' => 'TotalSalesCost',
                'title' => __('app.Total Sales Cost'),
                'searchable' => false,
            ],
            [
                'name' => 'TotalProfit',
                'data' => 'TotalProfit',
                'title' => __('app.Total Profit'),
                'searchable' => false,
            ],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ItemProfits_' . date('YmdHis');
    }
}
