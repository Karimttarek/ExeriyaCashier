<?php

namespace App\Http\Controllers\Report;

use App\DataTables\ItemAnalysis\SalesDataTable;
use App\Http\Controllers\Controller;

class SalesAnalysisReportController extends Controller
{
    public function __invoke( SalesDataTable $dataTable){
        return $dataTable->render('reports.salesAnalysis');
    }

}
