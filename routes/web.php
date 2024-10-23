<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeownerController;

Route::post('/parse-csv', [HomeownerController::class, 'parseCSV']);

Route::get('/upload-csv', function () {
    return view('upload');
});

Route::get('/', function () {
    return view('welcome');
});
