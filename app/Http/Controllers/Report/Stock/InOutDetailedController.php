<?php

namespace App\Http\Controllers\Report\Stock;

use App\DataTables\Report\General\InOutStockDetailedDataTable;
use App\Http\Controllers\Controller;

class InOutDetailedController extends Controller
{
    public function __invoke(InOutStockDetailedDataTable $dataTable){
        return $dataTable->render('reports.stock.inOutStockRep');
    }
}
