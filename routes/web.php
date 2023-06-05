<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\DirectoryController::class, 'index'])->name('directory');
Route::post('/', [\App\Http\Controllers\DirectoryController::class, 'store'])->name('create-directory');
Route::get('/edit/{id}', [\App\Http\Controllers\DirectoryController::class, 'edit'])->name('edit-directory');
Route::post('/update/{id}', [\App\Http\Controllers\DirectoryController::class, 'update'])->name('update-directory');
Route::get('/remove/{id}', [\App\Http\Controllers\DirectoryController::class, 'destroy'])->name('remove-directory');
Route::post('/search', [\App\Http\Controllers\DirectoryController::class, 'search'])->name('search');
Route::post('/import', [\App\Http\Controllers\DirectoryController::class, 'import'])->name('import');
