<?php

use App\Models\Sale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::get('/inventory', function () {
    return view('pages.inventory');
})->name('inventory');

Route::get('/inventory/categories', function () {
    return view('pages.inventory.categories');
})->name('inventory.categories');

Route::get('/inventory/products', function () {
    return view('pages.inventory.products');
})->name('inventory.products');

Route::get('/inventory/units', function () {
    return view('pages.inventory.units');
})->name('inventory.units');

Route::get('/sales', function () {
    return view('pages.sales');
})->name('sales');

Route::get('/sales/{sale}', function (Sale $sale) {
    return view('pages.sales.detail', [
        'sale' => $sale,
    ]);
})->name('sales.detail');

Route::get('/history', function () {
    return view('pages.history');
})->name('history');

Route::get('/reports/profit-loss', function () {
    return view('pages.reports.profit-loss');
})->name('reports.profit-loss');
