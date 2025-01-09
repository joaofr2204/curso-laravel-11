<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Core\CrudController;
use App\Http\Requests\Core\StoreUserRequest;
use App\Http\Requests\Core\UpdateUserRequest;
use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserController extends CrudController
{
    public static function routes()
    {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->id == $id) {
            return redirect()->route("{$this->view}.index")->with('warning', 'Você não pode deletar seu próprio usuário');
        }

        parent::destroy($id);
    }

}
