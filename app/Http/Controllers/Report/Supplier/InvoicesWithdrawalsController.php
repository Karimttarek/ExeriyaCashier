<?php

namespace App\Http\Controllers\Report\Supplier;

use App\DataTables\Report\Supplier\InvoiceWithdrawalDataTable;
use App\Http\Controllers\Controller;

class InvoicesWithdrawalsController extends Controller
{
    public function __invoke(InvoiceWithdrawalDataTable $dataTable){
        return $dataTable->render('reports.supplier.invoicesWithdrawals');
    }

}
