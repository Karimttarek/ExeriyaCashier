<?php

namespace App\Http\Controllers\Report;

use App\DataTables\ItemAnalysis\PurchaseDataTable;
use App\Http\Controllers\Controller;

class PurAnalysisReportController extends Controller
{

    public function __invoke( PurchaseDataTable $dataTable){
        return $dataTable->render('reports.salesAnalysis');
    }
}
