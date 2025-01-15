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
        $cols = $this->model->getGridColumns();

        //-- aqui fornece os dados para listagem
        if ($request->ajax()) {

            $list = $this->model::query();
            $datatables = DataTables::of($list);

            foreach ($cols as $col) {

                //-- COMBOBOX render
                if (in_array($col['syscolumn']['type'], ['CI', 'CV']) && isset($col['syscolumn']['options'])) {
                    $datatables->editColumn($col['name'], function ($row) use ($col) {
                        $colname = $col['name'];
                        // return $row->$colname; // Traduz ou mantém o valor original
                        return $col['syscolumn']['options'][$row->$colname] ?? $row->$colname; // Traduz ou mantém o valor original
                    });

                    //-- CHECKBOX render
                } else if (in_array($col['syscolumn']['type'], ['CH'])) {

                    $datatables->editColumn($col['name'], function ($row) use ($col) {
                        //-- for checkboxes, the sqlcombo options must have 3 positions each one.
                        //-- 0 = color, 1 = font awesome icon, 2 = title
                        $col_name = $col['name'];
                        if (isset($col['syscolumn']['options']) && !empty($col['syscolumn']['options'])) {
                            $status = $col['syscolumn']['options'][$row->$col_name];
                            return "<i class=\"fas fa-{$status[1]}\" style=\"color:{$status[0]}\" title=\"{$status[2]}\"></i>";
                        } else {
                            return $row->$col_name ? 'Sim' : 'Não';
                        }
                    })->rawColumns([$col['name']]); // Necessário para renderizar o HTML

                }

            }

            $this->modifyColumns($datatables, $cols);

            return $datatables->make(true);
        }

        $view = view()->exists("{$this->view}.index") ? "{$this->view}.index" : "core.crud.index";

        return view($view, [
            'model' => $this->model,
            'cols' => array_values($cols)
        ]);
    }

    protected function modifyColumns(EloquentDataTable &$datatables, $cols)
    {

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

        // dd($this->model->getFormColumns('create'));

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

        // dd($this->model->getFormColumns('create'));

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
        $columns = $this->model->getFormColumns($action);

        $validatedData = [];
        foreach ($columns as $column) {
            $name = $column['name'];

            if(!$request->has($name)) {
                continue;
            }

            if ($column['type'] == 'PW') {
                $validatedData[$name] = bcrypt($request->input($name));
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
