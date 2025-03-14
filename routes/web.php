<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/product/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
