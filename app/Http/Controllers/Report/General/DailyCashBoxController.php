<?php

namespace App\Http\Controllers\Report\General;

use App\DataTables\Report\General\DailyCashBoxDataTable;
use App\Http\Controllers\Controller;

class DailyCashBoxController extends Controller
{
    public function __invoke(DailyCashBoxDataTable $dataTable){

        return $dataTable->render('reports.dailyCashBox');
    }

}
