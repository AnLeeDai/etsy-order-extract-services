<?php

use App\Http\Controllers\PDFConvertController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PDFConvertController::class, 'index'])->name('app');
Route::post('/extract', [PDFConvertController::class, 'extract'])->name('pdf.extract');
Route::post('/extract/single', [PDFConvertController::class, 'extractSingle'])->name('pdf.extract.single');
Route::get('/ung-ho', fn () => view('support'))->name('support');
