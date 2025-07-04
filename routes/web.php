<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
Route::view('request','request')->middleware('no.role')->name('request');

Route::view('items', 'items')->name('items');
Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::middleware('role:admin|superadmin')->group(function () {
    Route::get('items/export', [ItemController::class,'export'])->name('items.export');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
