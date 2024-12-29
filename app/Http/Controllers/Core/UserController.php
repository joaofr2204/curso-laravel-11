<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\StoreUserRequest;
use App\Http\Requests\Core\UpdateUserRequest;
use App\Models\Core\User;

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
        User::create($request->validated());

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
    public function update(UpdateUserRequest $request, string $id)
    {

        if(!$user = User::find($id)){
            return redirect()->route('users')->with('warning','Usuário não encontrado');
        };

        $data = $request->only('name','email');
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        return redirect()->route('users')->with('success','Usuário atualizado com sucesso');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users');
    }
}
