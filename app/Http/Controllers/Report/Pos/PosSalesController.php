<?php

namespace App\Http\Controllers\Report\Pos;

use App\DataTables\Pos\PosSalesDataTable;
use App\Http\Controllers\Controller;

class PosSalesController extends Controller
{
    public function __invoke(PosSalesDataTable $dataTable){
        return $dataTable->render('reports.pos.PosSales');
    }
}
