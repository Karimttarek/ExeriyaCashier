<?php

namespace App\DataTables\Report\General;

use App\Exports\Reports\General\InOutStockExport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Editor\Fields\Checkbox;
use Yajra\DataTables\Services\DataTable;

class InOutStockDataTable extends DataTable
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
                    DB::raw('SUM(CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                                    CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                    WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                    ELSE invoicedetails.qty END
                                END) as INCOME'),

                    DB::raw('SUM(CASE WHEN invoicedetails.invoice_type IN(2,3,5) THEN
                                    CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                    WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                    ELSE invoicedetails.qty * manufacturs.qty END
                                END) as OUTCOME'),

                    DB::raw('
                    IFNULL(
                    SUM(CASE WHEN invoicedetails.invoice_type IN(1,4,6) THEN
                                    CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                    WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                    ELSE invoicedetails.qty END
                                END) , 0)
                            -
                            IFNULL(
                            SUM(CASE WHEN invoicedetails.invoice_type IN(2,3,5) THEN
                                CASE WHEN invoicedetails.unit_type = products.second_unit_type THEN invoicedetails.qty * products.second_unit_qty
                                WHEN invoicedetails.unit_type = products.third_unit_type THEN (invoicedetails.qty * products.third_unit_qty) * products.second_unit_qty
                                ELSE invoicedetails.qty * manufacturs.qty END
                            END) , 0) as balance')
                )
                ->leftJoin('invoicehead' , 'invoicehead.uuid' ,'=','invoicedetails.uuid')
                ->leftJoin('products' , 'invoicedetails.item_uuid' ,'=','products.uuid')
                ->rightJoin('manufacturs' , 'manufacturs.parent_uuid' , '=' , 'invoicedetails.item_uuid')
                ->groupBy('invoicedetails.item_uuid')
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
            })
            ->addColumn('Action' , function ($query){
                if($query->invoice_type == 1){
                    $this->route =  'Pur.edit';
                }elseif ($query->invoice_type == 2){
                    $this->route =  'Sales.edit';
                }
                elseif ($query->invoice_type == 3){
                    $this->route =  'PurReturn.edit';
                }
                elseif ($query->invoice_type == 4){
                    $this->route =  'SalesReturn.edit';
                }
                                return '<a href="http://exeriyainvoicing.test/en/report/in/out/stock/detailed?draw=3&columns%5B0%5D%5Bdata%5D=invoice_date&columns%5B0%5D%5Bname%5D=invoicehead.invoice_date&columns%5B1%5D%5Bdata%5D=invoice_number&columns%5B2%5D%5Bdata%5D=name&columns%5B2%5D%5Bname%5D=products.name%20as%20name&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=%09%D8%A8%D8%A7%D9%87%D9%8A%20210%20%D8%AC%D9%85%20*%208%20-%20H.S%20Lavend&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=INCOME&columns%5B4%5D%5Bdata%5D=OUTCOME&columns%5B5%5D%5Bdata%5D=balance&columns%5B6%5D%5Bdata%5D=Action&columns%5B6%5D%5Bsearchable%5D=false&columns%5B6%5D%5Borderable%5D=false&order%5B0%5D%5Bcolumn%5D=1&order%5B0%5D%5Bdir%5D=desc&start=0&length=10&search%5Bvalue%5D=&_=1710534070774" title="'.__('app.Redirect To Invoice').'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>'.__('app.View').'</a> '   ;
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


                        }"
                    ],);
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
            ],
//            [
//                'name' => 'Action',
//                'data' => 'Action',
//                'title' => '*',
//                'printable' => false,
//                'searchable' => false,
//                'orderable' => false,
//                'exportable' => false,
//            ],
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
