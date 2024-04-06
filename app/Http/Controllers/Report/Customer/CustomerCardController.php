<?php

namespace App\Http\Controllers\Report\Customer;

use App\DataTables\Report\Customer\CustomerCardDataTable;
use App\Http\Controllers\Controller;

class CustomerCardController extends Controller
{
    public function __invoke(CustomerCardDataTable $dataTable){

         return $dataTable->render('reports.customerCard');
    }
}
