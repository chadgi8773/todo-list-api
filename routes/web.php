<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotesController;
use PhpParser\Node\Stmt\Nop;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('notes', NotesController::class);
    Route::get('/data/notes', [NotesController::class,'getData'])->name('notes.data');
    // Route::delete('notes/{note}/die', [App\Http\Controllers\NotesController::class, 'die'])->name('notes.die');
});
// Route::get('/notes/{note}', [NotesController::class, 'show']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
