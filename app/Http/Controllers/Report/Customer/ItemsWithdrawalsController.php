<?php

namespace App\Http\Controllers\Report\Customer;

use App\DataTables\Report\Customer\ItemWithdrawalDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ItemsWithdrawalsController extends Controller
{
    public function __invoke(ItemWithdrawalDataTable $dataTable){
        return $dataTable->render('reports.customer.itemsWithdrawals');
    }
}
