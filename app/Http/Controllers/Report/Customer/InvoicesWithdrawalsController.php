<?php

namespace App\Http\Controllers\Report\Customer;

use App\DataTables\Report\Customer\InvoiceWithdrawalDataTable;
use App\Http\Controllers\Controller;

class InvoicesWithdrawalsController extends Controller
{

    public function __invoke(InvoiceWithdrawalDataTable $dataTable){

        return $dataTable->render('reports.customer.invoicesWithdrawals');
    }
}
