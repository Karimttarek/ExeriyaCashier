<?php

namespace App\Http\Controllers\Report\General;

use App\DataTables\Report\Finances\ExpenseDataTable;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function __invoke(ExpenseDataTable $dataTable){

        return $dataTable->render('reports.ExpenseStatement');
    }
}
