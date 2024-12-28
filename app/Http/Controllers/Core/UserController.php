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
        User::create(Request::all());
    
        return redirect()->route('users')->with('success', 'Usuário criado com sucesso!');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('core.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('core.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect()->route('users');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users');
    }
}
