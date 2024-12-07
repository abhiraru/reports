<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report', [ReportController::class, 'index'])->name('index');
// Route::get('/report-view', [ReportController::class, 'report'])->name('index');
