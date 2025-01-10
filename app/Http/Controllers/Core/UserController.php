<?php

namespace App\Http\Controllers\Core;

use Illuminate\Support\Facades\Auth;

class UserController extends CrudController
{

    public function destroy(string $id)
    {
        //-- validacao especifica do usuário
        if (Auth::user()->id == $id) {
            return redirect()->route("{$this->view}.index")->with('warning', 'Você não pode deletar seu próprio usuário');
        }

        parent::destroy($id);
    }

}
