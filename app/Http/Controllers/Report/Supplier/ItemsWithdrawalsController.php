<?php

namespace App\Http\Controllers\Report\Supplier;

use App\DataTables\Report\Supplier\ItemWithdrawalDataTable;
use App\Http\Controllers\Controller;

class ItemsWithdrawalsController extends Controller
{
    public function __invoke(ItemWithdrawalDataTable $dataTable){
        return $dataTable->render('reports.supplier.itemsWithdrawals');
    }
}
