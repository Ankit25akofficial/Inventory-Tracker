<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared routes for both admin and staff
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/inventory/invoice/{log}', [InventoryController::class, 'invoice'])->name('inventory.invoice');

    Route::get('/scanner', [QrCodeController::class, 'scanner'])->name('qr.scanner');
    Route::post('/qr/scan', [QrCodeController::class, 'handleScan'])->name('qr.scan');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        
        Route::get('/qr/download/{product}', [QrCodeController::class, 'download'])->name('qr.download');
        Route::get('/qr/print', [QrCodeController::class, 'printAll'])->name('qr.print');
    });

    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

require __DIR__.'/auth.php';
