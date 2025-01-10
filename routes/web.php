<?php

use App\Http\Controllers\Core\SyscolumnController;
use App\Http\Controllers\Core\SystableController;
use App\Http\Controllers\Core\UserController;
use App\Http\Controllers\Core\SysdbController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    SysdbController::routes();
    SystableController::routes();
    SyscolumnController::routes();
    UserController::routes();

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/toggle-dark-mode', function () {
        // Alternar entre os modos e salvar na sessão
        $currentMode = Session::get('dark_mode', false);
        Session::put('dark_mode', !$currentMode);
    
        return back(); // Redireciona para a página anterior
    })->name('toggle-dark-mode');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

