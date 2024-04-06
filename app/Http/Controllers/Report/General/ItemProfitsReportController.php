<?php

namespace App\Http\Controllers\Report\General;

use App\DataTables\Report\General\ItemProfitsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ItemProfitsReportController extends Controller
{
    public function __invoke(ItemProfitsDataTable $dataTable)
    {
        return $dataTable->render('reports.itemProfits');
    }
}
