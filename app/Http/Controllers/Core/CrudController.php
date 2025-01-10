<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

abstract class CrudController extends Controller
{
    protected Model $model;
    protected string $view;

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

    protected static function getTableName(): string
    {
        $fullClassName = get_called_class();
        $controllerName = class_basename($fullClassName);
        $tableName = str_replace('Controller', '', $controllerName);
        return Str::plural(Str::snake($tableName));
    }

    public static function routes()
    {
        $table = self::getTableName();

        Route::get("/$table", [get_called_class(), 'index'])->name("$table.index");
        Route::get("/$table/create", [get_called_class(), 'create'])->name("$table.create");
        Route::get("/$table/{id}", [get_called_class(), 'show'])->name("$table.show");
        Route::post("/$table", [get_called_class(), 'store'])->name("$table.store");
        Route::get("/$table/{id}/edit", [get_called_class(), 'edit'])->name("$table.edit");
        Route::put("/$table/{id}", [get_called_class(), 'update'])->name("$table.update");
        Route::delete("/$table/{id}/destroy", [get_called_class(), 'destroy'])->name("$table.destroy");
    }

    public function index(Request $request)
    {
        //-- aqui fornece os dados para listagem
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

        $view = view()->exists("{$this->view}.index") ? "{$this->view}.index" : "core.crud.index";

        return view($view, ['model' => $this->model]);
    }

    public function show($id)
    {
        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado');
        }

        $view = view()->exists("{$this->view}.show") ? "{$this->view}.show" : "core.crud.show";

        return view($view, data: ['model' => $model]);
    }

    public function create()
    {
        $view = view()->exists("{$this->view}.create") ? "{$this->view}.create" : "core.crud.create";

        return view($view, ['model' => $this->model]);
    }

    public function store(Request $request)
    {
        // Valida os dados
        $validatedData = ($requestClass = $this->requestClassExists())
            ? app($requestClass)->validated()
            : $request->all();

        // Cria o registro no banco de dados
        $this->model::create($validatedData);

        return redirect()->route("{$this->model->getTable()}.index")
            ->with('success', 'Registro inserido com sucesso!');
    }

    private function requestClassExists()
    {

        // Obter informações da pilha de chamadas
        $backtrace = debug_backtrace();

        // Retornar o nome do método chamador
        $action = $backtrace[1]['function'] ?? null;

        // Obtém o namespace completo da classe do modelo
        $fullClass = get_class($this->model);

        // Remove o prefixo 'App\Models\' do namespace completo
        $relativeClass = str_replace('App\\Models\\', '', $fullClass);

        // Divide o restante em partes usando o separador '\'
        $parts = explode('\\', $relativeClass);

        // O último elemento é o nome do modelo
        $modelName = array_pop($parts);

        // O restante (se existir) é o caminho intermediário
        $path = implode('\\', $parts);

        if ($path) {
            $path .= '\\';
        }

        $action = ucfirst($action);
        // Exemplo de uso no Request personalizado
        $requestClass = "App\\Http\\Requests\\{$path}{$action}{$modelName}Request";

        return class_exists($requestClass) ? $requestClass : false;
    }


    public function edit(string $id)
    {
        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado');
        }

        $view = view()->exists("{$this->view}.edit") ? "{$this->view}.edit" : "core.crud.edit";

        return view($view, ['model' => $model]);

    }

    public function update(Request $request, string $id)
    {

        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado');
        }

        // Valida os dados
        $validatedData = ($requestClass = $this->requestClassExists())
            ? app($requestClass)->validated()
            : $request->all();

        //if ($validatedData['password']) {
        //    $validatedData['password'] = bcrypt($validatedData->password);
        // }

        $model->update($validatedData);

        return redirect()->route("{$this->model->getTable()}.index")->with('success', 'Registro atualizado com sucesso');
    }

    public function destroy(string $id)
    {
        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado!');
        }

        $model->delete();

        return redirect()->route("{$this->model->getTable()}.index")->with('success', 'Registro deletado com sucesso');
    }

}
