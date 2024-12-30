<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\StoreUserRequest;
use App\Http\Requests\Core\UpdateUserRequest;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        //$users = User::paginate(50);

        if ($request->ajax()) {
            $users = User::query();

            return DataTables::of($users)
                ->make(true);
        }
        return view('core.users.index');
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
        if (!$user = User::find($id)) {
            return redirect()->route('users')->with('warning', 'Usuário não encontrado1');
        }
        ;
        return view('core.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        if (!$user = User::find($id)) {
            return redirect()->route('users')->with('warning', 'Usuário não encontrado2');
        }
        ;

        return view('core.users.edit', compact('user'));
    }
    public function update(UpdateUserRequest $request, string $id)
    {

        if (!$user = User::find($id)) {
            return redirect()->route('users')->with('warning', 'Usuário não encontrado3');
        }
        ;

        $data = $request->only('name', 'email');
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        return redirect()->route('users')->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->id == $id) {
            return redirect()->route('users')->with('warning', 'Você não pode deletar seu próprio usuário');
        }

        if (!$user = User::find($id)) {
            return redirect()->route('users')->with('warning', 'Usuário não encontrado4');
        }

        $user->delete();
        return redirect()->route('users')->with('success', 'Usuário deletado com sucesso');
    }
}
