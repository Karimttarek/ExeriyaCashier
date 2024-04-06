<?php

use App\Http\Controllers\eInvoice\SalesInvoicePortalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Invoices\PurInvoiceController;
use App\Http\Controllers\Invoices\PurReturnController;
use App\Http\Controllers\Invoices\SalesInvoiceController;
use App\Http\Controllers\Invoices\SalesReturnController;
use App\Http\Controllers\Main\ClientController;
use App\Http\Controllers\Main\ExpensesController;
use App\Http\Controllers\Main\ManufacturController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\UserController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\POSReturnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Receipts\CashController;
use App\Http\Controllers\Receipts\VoucherController;
use App\Http\Controllers\Report\Customer\CustomerCardController;
use App\Http\Controllers\Report\Customer\InvoicesWithdrawalsController as CustomerInvoicesWithdrawalsController;
use App\Http\Controllers\Report\Customer\ItemsWithdrawalsController as CustomerItemsWithdrawalsController;
use App\Http\Controllers\Report\General\DailyCashBoxController;
use App\Http\Controllers\Report\General\ExpenseController;
use App\Http\Controllers\Report\Stock\InOutStockReportController;
use App\Http\Controllers\Report\General\ItemProfitsReportController;
use App\Http\Controllers\Report\InventoryController;
use App\Http\Controllers\Report\PurAnalysisReportController;
use App\Http\Controllers\Report\SalesAnalysisReportController;
use App\Http\Controllers\Report\Supplier\InvoicesWithdrawalsController as SupplierInvoicesWithdrawalsController;
use App\Http\Controllers\Report\Supplier\ItemsWithdrawalsController as SupplierItemsWithdrawalsController;
use App\Http\Controllers\Report\Supplier\SupplierCardController;
use App\Http\Controllers\Stocktaking\StocktakingController;
use App\Http\Controllers\System\BranchController;
use App\Http\Controllers\System\SystemController;
use App\Http\Controllers\DB\DBController;
use App\Http\Controllers\Report\Pos\PosSalesController;
use App\Http\Controllers\Report\Stock\InOutDetailedController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PHPUnit\Framework\Attributes\Group;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function(){ //...
    require __DIR__.'/auth.php';

    Route::middleware(['auth','UserVerified'])->prefix('')->group( function() { //isAdmin

        Route::get('pos/create',[POSController::class ,'create'])->name('POS.create');
        Route::get('pos/product/type/filter' ,[POSController::class ,'changeType'])->name('pos.TypeFilter');

    Route::middleware(['CheckIfCashier'])->group( function (){

        // ./POS SCREEN
        Route::prefix('pos')->controller(POSController::class)->group( function () {

            Route::view('/' ,'pos.index')->name('POS.index');
            Route::post('/store','store')->name('POS.store');
            Route::get('/edit/{uuid}','edit')->name('POS.edit');
            Route::post('/update/{uuid}','update')->name('POS.update');

            Route::get('/print','print')->name('POS.print');
        });

        Route::prefix('pos/return')->controller(POSReturnController::class)->group( function () {

            Route::get('/create','create')->name('POS.return.create');
            Route::view('/' ,'posreturn.index')->name('POS.return.index');
            Route::post('/store','store')->name('POS.return.store');
            Route::get('/edit/{uuid}','edit')->name('POS.return.edit');
            Route::post('/update/{uuid}','update')->name('POS.return.update');

            Route::get('/print','print')->name('POS.return.print');
        });

        Route::prefix('stocktaking')->controller(StocktakingController::class)->group( function () {
            Route::get('/' ,'index')->name('stocktaking.index');
            Route::post('/gard' ,'gard')->name('stocktaking.gard');
            Route::get('/gard/filter' ,'filter')->name('stocktaking.filter.gard');
        });

        Route::get('/',[HomeController::class ,'index'])->name('home');
        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        /**
         * Users
         */
        Route::prefix('users')->controller(UserController::class)->group( function () {
            // ./Users
            Route::get('', 'index')->name('User.get');
            Route::get('/destroy',  'destroy')->name('User.destroy');
            Route::get('/accept/{uuid}' , 'accept')->name('User.accept');
            Route::get('/refuse/{uuid}' , 'refuse')->name('User.refuse');
            Route::get('/set/manager/{uuid}' ,'setManager')->name('User.setManager');
            Route::get('/set/admin/{uuid}' ,'setAdmin')->name('User.setAdmin');
            Route::get('/set/user/{uuid}' ,'setUser')->name('User.setUser');
            Route::get('/set/cashier/{uuid}' ,'setCashier')->name('User.setCashier');
        });
        /**
         * Clients
         */
        Route::prefix('clients')->controller(ClientController::class)->group( function () {
        // ./Clients
            Route::view('', 'client.get')->name('Client.get');
            Route::get('/create', 'create')->name('Client.create');
            Route::post('/store', 'store')->name('Client.store');
            Route::get('/edit/{uuid}' , 'edit')->name('Client.edit');
            Route::post('/update/{uuid}' , 'update')->name('Client.update');
            Route::get('/destroy' , 'destroy')->name('Client.destroy');
        });

        // /**
        //  * Expenses
        //  */
        Route::prefix('expenses')->controller(ExpensesController::class)->group( function () {
            // ./Expenses
            Route::get('', function(){ return view('expenses.get'); })->name('Expenses.get');
            Route::get('create', 'create')->name('Expenses.create');
            Route::post('/store','store')->name('Expenses.store');
            Route::get('/edit/{uuid}' , 'edit')->name('Expenses.edit');
            Route::post('/update/{uuid}' , 'update')->name('Expenses.update');
            Route::get('/destroy' , 'destroy')->name('Expenses.destroy');
            Route::get('/filter','expFilter')->name('Expenses.filter');
        });

        // /**
        //  * Revenues
        //  */
        // // ./Revenues
        // Route::prefix('revenues')->controller(\App\Http\Controllers\Main\RevenuesController::class)->group( function () {
        //     Route::get('', function(){ return view('revenues.get'); })->name('Revenues.get');
        //     Route::get('/create', 'create')->name('Revenues.create');
        //     Route::post('/store',  'store')->name('Revenues.store');
        //     Route::get('/edit/{uuid}' ,'edit')->name('Revenues.edit');
        //     Route::post('/update/{uuid}' ,'update')->name('Revenues.update');
        //     Route::get('/filter', 'revFilter')->name('Revenues.filter');
        // });

        // /**
        //  * Manufactur
        //  */
        // Route::prefix('manufactur')->controller(ManufacturController::class)->group( function () {
        // // ./Manufactur
        //     Route::view('', 'manufactur.index')->name('Manufactur.get');
        //     Route::get('/create', 'create')->name('Manufactur.create');
        //     Route::post('/store', 'store')->name('Manufactur.store');
        //     Route::get('/edit/{product_id}' , 'edit')->name('Manufactur.edit');
        //     Route::post('/update/{product_id}','update')->name('Manufactur.update');
        //     Route::get('/destroy' ,'destroy')->name('Manufactur.destroy');
        //     Route::get('/filter','expFilter')->name('Manufactur.filter');
        //     Route::get('product/getItems' , 'Getitems')->name('Manufactur.getItems');
        //     Route::get('product/filter' , 'itemsFilter')->name('Manufactur.itemsFilter');
        // });

        /**
         * Products
         */
        Route::prefix('product')->controller(ProductController::class)->group( function () {
            // ./Products
            Route::view('', 'product.product')->name('Product.get');
            Route::get('/create', 'create')->name('Product.create');
            Route::post('/store', 'store')->name('Product.store');
            Route::get('/destroy' ,  'destroy')->name('Product.destroy');
            Route::get('/edit/{uuid}' , 'edit')->name('Product.edit');
            Route::post('/update/{uuid}' , 'update')->name('Product.update');
            Route::post('/bulk/upload' ,'importProductToExcel')->name('Product.bulkUpload');
            Route::get('/update/resources' , 'updateResources')->name('Product.updateResources');
            Route::get('/upload/to/eta/{uuid}' ,  'uploadToInvoice')->name('Product.uploadToInvoice');
            Route::get('/request/reuse/code/{uuid}' ,'reuseCode')->name('Product.reuseCode');
            Route::get('/request/reuse/codes' , 'reuseCodes')->name('Product.reuseCodes');

            Route::get('/pur/type/filter' , 'changeTypePur')->name('product.pur.TypeFilter');
            Route::get('/sales/type/filter' , 'changeTypeSales')->name('product.sales.TypeFilter');
        });

        /**
         * Invoices
         */
        Route::prefix('invoices')->group( function () {

            Route::prefix('purchase')->group( function () {
                // ./PURCHASE INVOICE
                Route::view('/', 'transaction.pur.pur')->name('Pur.get');
                Route::get('/create' , [PurInvoiceController::class , 'create'])->name('Pur.create');
                Route::post('/store' , [PurInvoiceController::class , 'store'])->name('Pur.store');
                Route::get('/edit/{uuid}' , [PurInvoiceController::class , 'edit'])->name('Pur.edit');
                Route::get('/copy/{uuid}' , [PurInvoiceController::class , 'getCopy'])->name('Pur.getCopy');
                Route::post('/update/{uuid}' , [PurInvoiceController::class , 'update'])->name('Pur.update');
                Route::post('/bulk/upload' ,[PurInvoiceController::class , 'importInvoiceToExcel'])->name('Pur.bulkUpload');
                Route::post('/tax/return' ,[PurInvoiceController::class , 'taxReturn'])->name('Pur.texReturn');
                Route::get('/invoices/to/excel' ,[PurInvoiceController::class , 'exportToExcel'])->name('Pur.toExcel');
                Route::post('/bulk/upload' ,[PurInvoiceController::class , 'bulkUpload'])->name('Pur.bulkUpload');

                Route::get('/print/{uuid}',[PurInvoiceController::class ,'print'])->name('Pur.print');
                // ./PURCHASE RETURN INVOICE
                Route::view('/return' , 'transactionReturn.pur.pur')->name('PurReturn.get');
                Route::get('/return/create' , [PurReturnController::class , 'create'])->name('PurReturn.create');
                Route::post('/return/store' , [PurReturnController::class , 'store'])->name('PurReturn.store');
                Route::get('/return/edit/{uuid}' , [PurReturnController::class , 'edit'])->name('PurReturn.edit');
                Route::get('/return/copy/{uuid}' , [PurReturnController::class , 'getCopy'])->name('PurReturn.getCopy');
                Route::post('/return/update/{uuid}' , [PurReturnController::class , 'update'])->name('PurReturn.update');
            });

            Route::prefix('sales')->group( function () {
                // ./SALES INVOICE
                Route::view('' ,'transaction.sales.sales')->name('Sales.get');
                Route::get('/create' , [SalesInvoiceController::class , 'create'])->name('Sales.create');
                Route::post('/store' , [SalesInvoiceController::class , 'store'])->name('Sales.store');
                Route::get('/edit/{uuid}' , [SalesInvoiceController::class , 'edit'])->name('Sales.edit');
                Route::get('/copy/{uuid}' , [SalesInvoiceController::class , 'getCopy'])->name('Sales.getCopy');
                Route::post('/update/{uuid}' , [SalesInvoiceController::class , 'update'])->name('Sales.update');
                Route::post('/bulk/upload' ,[SalesInvoiceController::class , 'importInvoiceToExcel'])->name('Sales.bulkUpload');
                Route::post('/upload/to/invoice/portal' ,[SalesInvoicePortalController::class , 'uploadToInvoice'])->name('Sales.uploadToInvoice');
                Route::get('/serialize' ,[SalesInvoicePortalController::class , 'Serialize'])->name('Sales.Serialize');
                Route::post('/tax/return' ,[SalesInvoiceController::class , 'taxReturn'])->name('Sales.texReturn');
                Route::get('/invoices/to/excel' ,[SalesInvoiceController::class , 'exportToExcel'])->name('Sales.toExcel');


                Route::post('/save/draft' ,[SalesInvoiceController::class , 'saveToDraft'])->name('Sales.saveToDraft');
                Route::get('/print/{uuid}',[SalesInvoiceController::class ,'print'])->name('Sales.print');

                Route::get('/send/document/{uuid}' ,[SalesInvoicePortalController::class , 'submitDoc'])->name('submitDoc');
                // ./SALES RETURN INVOICE
                Route::view('/return' ,'transactionReturn.sales.sales')->name('SalesReturn.get');
                Route::get('/return/create' , [SalesReturnController::class , 'create'])->name('SalesReturn.create');
                Route::post('/return/store' , [SalesReturnController::class , 'store'])->name('SalesReturn.store');
                Route::get('/return/edit/{uuid}' , [SalesReturnController::class , 'edit'])->name('SalesReturn.edit');
                Route::get('/return/copy/{uuid}' , [SalesReturnController::class , 'getCopy'])->name('SalesReturn.getCopy');
                Route::post('/return/update/{uuid}' , [SalesReturnController::class , 'update'])->name('SalesReturn.update');
            });
        });
        Route::get('/get/cities',[SalesInvoiceController::class , 'getCities'])->name('getCities');
        Route::get('/customer/filter',[SalesInvoiceController::class , 'customerFilter'])->name('customerFilter');


        // Branches
        Route::get('/branches',function(){ return view('branch.index') ;})->name('Branch.get');
        Route::get('/branches/create',[BranchController::class , 'create'])->name('Branch.create');
        Route::post('/branches/store',[BranchController::class , 'store'])->name('Branch.store');
        Route::get('/branches/edit/{id}',[BranchController::class , 'edit'])->name('Branch.edit');
        Route::post('/branches/update/{id}',[BranchController::class , 'update'])->name('Branch.update');

        Route::get('/system' ,[SystemController::class ,'index'])->name('System.index');
        Route::post('/system/store',[SystemController::class ,'store'])->name('System.store');

        /**
         * Receipts
         */
        Route::prefix('receipts')->group( function () {

            Route::controller(VoucherController::class)->group( function () {

                Route::view('/voucher' , 'receipts.voucher.get')->name('Voucher.get');
                Route::get('/voucher/create' , 'create')->name('Voucher.create');
                Route::post('/voucher/store' , 'store')->name('Voucher.store');
                Route::get('/voucher/edit/{uuid}' , 'edit')->name('Voucher.edit');
                Route::post('/voucher/update/{uuid}' , 'update')->name('Voucher.update');
                Route::post('/voucher/print' , 'print')->name('Voucher.print');
                Route::get('/voucher/print/{uuid}' , 'printReceipt')->name('Voucher.printReceipt');
            });

            Route::controller(CashController::class)->group( function () {
            // cash
            Route::view('/cash' , 'receipts.cash.get')->name('Cash.get');
            Route::get('/cash/create' ,'create')->name('Cash.create');
            Route::post('/cash/store' , 'store')->name('Cash.store');
            Route::get('/cash/edit/{uuid}' , 'edit')->name('Cash.edit');
            Route::post('/cash/update/{uuid}' ,'update')->name('Cash.update');
            Route::post('/cash/print' ,  'print')->name('Cash.print');
            Route::get('/cash/print/{uuid}' , 'printReceipt')->name('Cash.printReceipt');

            });
        });
        Route::get('/numberFormat', [VoucherController::class , 'numberFormat'])->name('numberFormat');

       Route::get('/app/db/backup', function(){
        Artisan::call('backup:run',['--only-db'=>true]);
       })->name('DB.backup');

        /**
         * Finances
         */
        Route::prefix('finances')->group( function () {
            Route::controller(\App\Http\Controllers\Finances\ExpenseController::class)->group( function (){
                Route::view('/expenses' , 'finances.expenses.get')->name('Finances.Expenses.get');
                Route::get('/expenses/create' ,'create')->name('Finances.Expenses.create');
                Route::post('/expenses/store' ,'store')->name('Finances.Expenses.store');
                Route::get('/expenses/edit/{uuid}' , 'edit')->name('Finances.Expenses.edit');
                Route::post('/expenses/update/{uuid}' , 'update')->name('Finances.Expenses.update');
                Route::post('/expenses/print' , 'print')->name('Finances.Expenses.print');
                Route::get('/expenses/print/{uuid}' , 'printReceipt')->name('Finances.Expenses.printReceipt');
            });
        //     // cash
        //     Route::controller(\App\Http\Controllers\Finances\RevenueController::class)->group( function (){
        //         Route::view('/revenues' , 'finances.revenues.get')->name('Finances.Revenues.get');
        //         Route::get('/revenues/create' , 'create')->name('Finances.Revenues.create');
        //         Route::post('/revenues/store' , 'store')->name('Finances.Revenues.store');
        //         Route::get('/revenues/edit/{uuid}' , 'edit')->name('Finances.Revenues.edit');
        //         Route::post('/revenues/update/{uuid}' , 'update')->name('Finances.Revenues.update');
        //         Route::post('/revenues/print' , 'print')->name('Finances.Revenues.print');
        //         Route::get('/revenues/print/{uuid}' , 'printReceipt')->name('Finances.Revenues.printReceipt');
        //     });
        });
        /**
         *
         *
         */


        Route::get('/updates', function (){ return view('news.updates.index');})->name('Updates.index');
    });

        Route::get('product/purchase/getItems' ,[ProductController::class , 'Getitems'])->name('product.getItems');
        Route::get('product/purchase/filter' , [ProductController::class ,'Purfilter'])->name('product.purchase.filter');
        Route::get('product/sales/filter' , [ProductController::class ,'Salesfilter'])->name('product.sales.filter');
        Route::get('product/stock' , [ProductController::class ,'ItemStock'])->name('product.stock');
    });

    Route::middleware(['auth','CheckIfCashier'])->prefix('report')->group( function() {
        // ./PURCHASE // ITEMS
        // Route::get('/purchase/analysis', PurAnalysisReportController::class)->name('PurAnalysisRep.get');
        // SALES // ITEMS
        // Route::get('/sales/analysis', SalesAnalysisReportController::class)->name('SalesAnalysisRep.get');

        // IN OUT STOCK
        Route::get('/in/out/stock', InOutStockReportController::class)->name('InOutStockRep.get');
		// IN OUT STOCK Detailed
        Route::get('/in/out/stock/detailed', InOutDetailedController::class)->name('InOutStockDetailedRep.get');
        // ./Daily Cash Box
        Route::get('/daily/cashbox', DailyCashBoxController::class)->name('DailyCashBox.get');
        // ./Daily Cash Box
        // Route::get('/inventory', [InventoryController::class , 'index'])->name('InventoryRep.get');
        // ./Expense Statement
        Route::get('/expenses/statement', ExpenseController::class)->name('ExpenseRep.get');
        // ./Revenues Statement
        Route::get('/revenues/statement', \App\Http\Controllers\Report\General\RevenuesController::class)->name('RevenuesRep.get');

        Route::prefix('customers')->group( function () { // Customers Reports
            // ./ CUSTOMER CARD
            Route::get('/card', CustomerCardController::class)->name('CustomerCard.get');
            // ./ CUSTOMER Invoices Withdrawals
            Route::get('/withdrawals/invoices', CustomerInvoicesWithdrawalsController::class)->name('CustomerInvoiceWithdrawals.index');
            // ./ CUSTOMER Items Withdrawals
            Route::get('/withdrawals/items', CustomerItemsWithdrawalsController::class)->name('CustomerItemWithdrawals.index');
        });
        Route::prefix('suppliers')->group( function () { // Suppliers Reports
            // SUPPLIER CARD
            Route::get('/card', SupplierCardController::class)->name('supplierCard.get');
            // ./ SUPPLIER Invoices Withdrawals
            Route::get('/withdrawals/invoices', SupplierInvoicesWithdrawalsController::class)->name('SupplierInvoiceWithdrawals.index');
            // ./ SUPPLIER Items Withdrawals
            Route::get('/withdrawals/items', SupplierItemsWithdrawalsController::class)->name('SupplierItemWithdrawals.index');

        });
        Route::get('/item/profit', ItemProfitsReportController::class)->name('ItemProfits.get');
        Route::get('/pos/sales', PosSalesController::class)->name('PosSales.index');
    });

    Route::get('/get/taxes/subTypes',[SalesInvoiceController::class , 'getTaxesSubTypes'])->name('getTaxesSubTypes');

//
});
