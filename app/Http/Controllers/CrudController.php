<?php

namespace App\Http\Controllers;

use App\Http\Requests\Core\StoreUserRequest;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

abstract class CrudController extends Controller
{
    private $model;
    private $view;

    public function __construct()
    {
        $this->model = $this->getModel();
        $this->view = $this->getView();
    }

    protected function getModel(): Model
    {
        // Substitui 'Http\\Controllers' por 'Models' e ajusta o namespace
        $modelClass = str_replace(
            ['App\\Http\\Controllers', 'Controllers', 'Controller'],
            ['App\\Models', 'Models', ''],
            get_called_class()
        );

        if (class_exists($modelClass)) {
            return new $modelClass;
        }

        throw new \Exception("Model class $modelClass does not exist.");
    }

    protected function getView(): string
    {
        // Substitui 'Http\\Controllers' e ajusta o namespace para gerar a view
        $namespace = str_replace(
            ['App\\Http\\Controllers', 'Controllers', 'Controller'],
            ['', '', ''],
            get_called_class()
        );

        // Converte para notação de view (com pontos) e ajusta a tabela
        $view = strtolower(str_replace('\\', '.', ltrim($namespace, '\\')));
        $parts = explode('.', $view);

        // Substitui o último segmento pelo nome da tabela
        $parts[count($parts) - 1] = $this->model->getTable();

        return implode('.', $parts);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $list = $this->model::query();

            return DataTables::of($list)
                /*
                ->addColumn('action', function ($user) {
                    return view('core.crud-actions', compact('user'))->render();
                },false)
                ->rawColumns(['action']) // Tornar a coluna 'action' como HTML
                */
                ->make(true);
        }

        return view("{$this->view}.index");
    }

    public function create()
    {
        return view("{$this->view}.create");
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

}
