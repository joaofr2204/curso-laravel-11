<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\StoreCrudRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\EloquentDataTable;

abstract class CrudController extends Controller
{
    /**
     * The model instance
     * @var Model
     */
    protected Model $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
    }

    /**
     * Get the model instance from the controller class name
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel(): Model
    {
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

    /**
     * Default CRUD routes
     *
     * @return void
     */
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

    /**
     * Show the datagrid listing all records
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cols = $this->model->getSysColumns('grid');

        //-- aqui fornece os dados para listagem
        if ($request->ajax()) {

            //-- translations for combobox, checkbox and status fields
            $translations = array_reduce($cols, function ($carry, $item) {
                if (isset($item['options'])) {
                    $carry[$item['name']] = $item['options'];
                }
                return $carry;
            }, []);

            $list = $this->model::query()->orderBy('id', 'desc');
            $datatables = DataTables::of($list);

            foreach (array_keys($translations) as $key) {
                $datatables->editColumn($key, function ($row) use ($translations, $key) {
                    return $translations[$key][$row->$key] ?? $row->$key; // Traduz ou mantém o valor original
                });
            }

            $this->customDatatables($datatables,$cols);

            return $datatables->make(true);
        }

        $view = view()->exists("{$this->view}.index") ? "{$this->view}.index" : "core.crud.index";

        return view($view, ['model' => $this->model]);
    }

    protected function customDatatables(EloquentDataTable &$datatables,$cols){
        
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

        // dd($this->model->getSysColumns('form', 'create'));

        return view($view, ['model' => $this->model]);
    }

    public function store(Request $request)
    {
        // Valida os dados
        $validatedData = $this->receiveRequest($request);

        // Cria o registro no banco de dados
        $this->model::create($validatedData);

        return redirect()->route("{$this->model->getTable()}.index")
            ->with('success', 'Registro inserido com sucesso!');
    }

    public function edit(string $id)
    {
        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado');
        }

        // dd($this->model->getSysColumns('form','create'));

        $view = view()->exists("{$this->view}.edit") ? "{$this->view}.edit" : "core.crud.edit";

        return view($view, ['model' => $model]);

    }

    public function update(Request $request, string $id)
    {

        if (!$model = $this->model::find($id)) {
            return redirect()->route("{$this->model->getTable()}.index")->with('warning', 'Registro não encontrado');
        }

        $validatedData = $this->receiveRequest($request);

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

    protected function receiveRequest(Request $request): array
    {
        $action = $request->method() == 'PUT' ? 'edit' : 'create';
        $columns = $this->model->getSysColumns('form', $action);

        $validatedData = [];
        foreach ($columns as $column) {
            $name = $column['name'];

            if ($column['type'] == 'PW') {
                $validatedData[$name] = bcrypt($request->input($name));

                //-- check if is a checkbox
                //}elseif ($column['type'] == 'CH') {
                //  $validatedData[$name] = $request->input($name) == 'on' ? 1 : 0;

            } else {
                $validatedData[$name] = $request->input($name);
            }
        }

        return $validatedData;
    }

    /**
     * Checks if a Request class exists for the current action
     * @return bool|string
     */
    protected function requestClassExists()
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


}
