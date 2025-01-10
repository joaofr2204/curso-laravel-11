<?php

namespace App\Models\Core\Traits;

use App\Models\Core\Syscolumn;
use App\Models\Core\Sysdb;
use App\Models\Core\Systable;

trait HasCrudMethods
{

    public function getColumns($op)
    {
        //-- FAZ IMPORTACAO AUTOMATICA, PENSAR EM ALGO QUE NAO FAÇA TODAS AS VEZES
        Sysdb::importDb($this);
        Systable::importMysqlTable($this);
        Syscolumn::importMysqlColumns($this);

        $columns = Syscolumn::when(
            $op == 'grid', function ($query) {
                $query->where('grid', 1);
            }
        )->when(
            $op == 'form', function ($query) use ($op) {
                    $query->where("form_on_{$op}", 1);
            }
        )->where('table', $this->getTable())
        ->whereNotIn('name', $this->hidden)
        ->get()->toArray();

        $formattedColumns = array_map(
            function ($column) {
                return [
                'data' => $column['name'],
                'name' => $column['name']
                ];
            }, $columns
        );
       
        return $formattedColumns;

    }

}