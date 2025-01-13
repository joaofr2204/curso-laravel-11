<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

abstract class Controller
{
    /**
     * The view name
     * @var string
     */
    protected string $view;

    public function __construct()
    {
        $this->view = $this->getView();
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
        $parts[count($parts) - 1] = $this->getTableName();

        return implode('.', $parts);
    }

    protected static function getTableName(): string
    {
        $fullClassName = get_called_class();
        $controllerName = class_basename($fullClassName);
        $tableName = str_replace('Controller', '', $controllerName);
        return Str::plural(Str::snake($tableName));
    }
}
