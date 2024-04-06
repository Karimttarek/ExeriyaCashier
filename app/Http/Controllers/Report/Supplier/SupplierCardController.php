<?php

namespace App\Http\Controllers\Report\Supplier;

use App\DataTables\Report\Supplier\SupplierCardDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierCardController extends Controller
{
    public function __invoke(SupplierCardDataTable $dataTable){
        return $dataTable->render('reports.supplierCard');
    }
}
