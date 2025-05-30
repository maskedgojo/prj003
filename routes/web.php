<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RelationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


Route::resource('document' , DocumentController::class);
Route::resource('relation' , RelationController::class);

Route::post('/relation/sort', [RelationController::class, 'sort'])->name('relation.sort');
Route::post('/document/sort', [DocumentController::class, 'sort'])->name('document.sort');
Route::post('/search', [DocumentController::class, 'search'])->name('document.search');
