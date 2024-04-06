<?php

namespace App\Http\Controllers\Report\Stock;

use App\DataTables\Report\General\InOutStockDataTable;
use App\Http\Controllers\Controller;

class InOutStockReportController extends Controller
{
    public function __invoke(InOutStockDataTable $dataTable){
        return $dataTable->render('reports.stock.inOutStockRep');
    }
}
