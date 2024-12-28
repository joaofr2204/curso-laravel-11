<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::paginate(15);
        return view('core.users.index', compact('users'));
    }

    public function create()
    {
        return view('core.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->all());

        return redirect()->route('users')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('core.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        if(!$user = User::find($id)){
            return redirect()->route('users')->with('warning','Usuário não encontrado');
        };
        return view('core.users.edit', compact('user'));
    }
    public function update(Request $request, string $id)
    {

        if(!$user = User::find($id)){
            return redirect()->route('users')->with('warning','Usuário não encontrado');
        };

        $user->update($request->only('name','email'));
        return redirect()->route('users')->with('success','Usuário atualizado com sucesso');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users');
    }
}
