<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KeluhanDetailController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;

//dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route::get('/', function () {
//         return view('container.dashboards.index');
//     })
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');
//get
Route::prefix('api')->name('api.')->group(function (){
    //product
    Route::get('/products/search', [HelperController::class, 'searchProducts'])
        ->name('products.search');
    //customer
    Route::get('/customers/search', [HelperController::class, 'searchCustomers'])
        ->name('customers.search');
    //detail-receives
    Route::get('receives/{receive}/details', [HelperController::class, 'getReceiveDetails'])    
        ->name('receives.details');

    Route::get('/keluhan/data', [KeluhanDetailController::class, 'getKeluhanData'])
    ->name('keluhan.data');
});
//keluhan

Route::resource('customer', CustomerController::class);
Route::post('customers/import', [CustomerController::class, 'import'])
    ->name('customers.import');

Route::middleware(['auth', 'permission:view receive'])->group(function () {
    //receive resource
    Route::resource('receives', ReceiveController::class)->parameters([
        'receives' => 'receive'
    ]);  

    Route::resource('teknisi', TeknisiController::class)
    ->only(['index', 'store', 'update', 'destroy']);
    
    Route::resource('product', ProductController::class)
    ->only(['index', 'store', 'update', 'destroy']);

});

Route::middleware(['auth', 'permission:view document'])->group(function () {
    //receives-prefix
    Route::prefix('receives-edit/{receive}')->group(function () {
        // Edit harga detail + jasa service keseluruhan
        Route::get('edit-service', [ReceiveController::class, 'editService'])
            ->name('edit.edit-receives');

        Route::post('update-service', [ReceiveController::class, 'updateService'])
            ->name('edit.update-receives');
    });    

    //kwitansi
    Route::get('download_kwitansi/{invoice}/print', [DocumentController::class, 'kwitansiPrint'])
        ->name('download.kwitansi.pdf');

    //sph
    Route::get('download_penawaran/{receivedetail}/print', [DocumentController::class, 'penawaraPrint'])
        ->name('download.penawaran.pdf');    
    
    //admin resource
    Route::resource('admin', TransactionController::class)
    ->parameters(['admin' => 'receive'])
    ->only([
        'index','show'
    ]);
});

Route::middleware(['auth', 'permission:view keluhan'])->group(function () {    
    //keluhan resource
    Route::resource('keluhan', KeluhanDetailController::class)->parameters([
        'keluhan' => 'receive'
    ])->only(['index', 'edit', 'store']);  
    
    Route::resource('keluh', KeluhanController::class)->only(['index', 'store', 'destroy']);
});

Route::get('surat-jalan/invoice-details/{invoiceId}', [SuratJalanController::class, 'invoiceDetails'])
    ->name('surat-jalan.invoice-details');

Route::middleware(['auth', 'permission:view surat jalan'])->group(function (){
    Route::get('download_suratjalan/{suratjalan}/print', [DocumentController::class, 'penawaraPrint'])
        ->name('download.suratjalan.pdf'); 
    
    Route::resource('surat-jalan', SuratJalanController::class);
});


Route::middleware(['auth', 'permission:view invoice'])->group(function () {
    //invoice    
    Route::resource('invoices', InvoiceController::class);    
    Route::get('download_invoice/{invoice}/print', [DocumentController::class, 'invoicePrint'])
        ->name('download.invoice.pdf');
});

//for teknisi and transaction
Route::middleware(['auth', 'permission:view report'])->group(function () {
    //teknisi
    Route::get('/report/teknisi', [ReportController::class, 'indexTeknisi'])
        ->name('report.teknisi');

    //detail teknisi
    Route::get('/report/teknisi/{id}', [ReportController::class, 'detailTeknisi'])
        ->name('report.teknisi.show');
    
    //teknisi
    Route::get('download_teknisi/print', [DocumentController::class, 'teknisiPrint'])
        ->name('download.teknisi.pdf'); 
});


//for invoice and finance
Route::middleware(['auth', 'permission:view pembayaran'])->group(function (){
    //receivable prefix
    Route::prefix('receivables')->group(function () {
        Route::get('/', [ReceivableController::class, 'index'])->name('receivables.index');
        Route::get('/{invoice}', [ReceivableController::class, 'show'])->name('receivables.show');
        Route::post('/{invoice}/bayar', [ReceivableController::class, 'bayar'])->name('receivables.bayar');
    });
});

require __DIR__.'/auth.php';


