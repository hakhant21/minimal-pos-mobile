<?php

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

Route::get('/history', function () {
    return view('pages.history');
})->name('history');
