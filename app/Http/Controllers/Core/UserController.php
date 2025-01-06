<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\CrudController;
use App\Http\Requests\Core\StoreUserRequest;
use App\Http\Requests\Core\UpdateUserRequest;
use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserController extends CrudController
{

    public function show($id)
    {
        if (!$user = User::find($id)) {
            return redirect()->route('users.index')->with('warning', 'Usuário não encontrado');
        }
        return view('core.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        if (!$user = User::find($id)) {
            return redirect()->route('users.index')->with('warning', 'Usuário não encontrado');
        }

        return view('core.users.edit', compact('user'));
    }
    public function update(UpdateUserRequest $request, string $id)
    {

        if (!$user = User::find($id)) {
            return redirect()->route('users')->with('warning', 'Usuário não encontrado');
        }

        $data = $request->only('name', 'email');
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->id == $id) {
            return redirect()->route('users.index')->with('warning', 'Você não pode deletar seu próprio usuário');
        }

        if (!$user = User::find($id)) {
            return redirect()->route('users.index')->with('warning', 'Usuário não encontrado4');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso');
    }

    public static function routes()
    {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    }
}
