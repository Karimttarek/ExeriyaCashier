<?php

namespace App\Http\Controllers\Report\General;

use App\DataTables\Report\Finances\RevenuesDataTable;
use App\Http\Controllers\Controller;

class RevenuesController extends Controller
{
    public function __invoke(RevenuesDataTable $dataTable){

        return $dataTable->render('reports.RevenuesStatement');
    }
}
