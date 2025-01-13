<?php

namespace App\Models\Core\Traits;

use App\Models\Core\Syscolumn;
use App\Models\Core\Sysdb;
use App\Models\Core\Systable;

trait HasCrudMethods
{

    public function getSysColumns($op, $action = '')
    {
        //-- FAZ IMPORTACAO AUTOMATICA, PENSAR EM ALGO QUE NAO FAÇA TODAS AS VEZES
        Sysdb::importDb($this);
        Systable::importMysqlTable($this);
        Syscolumn::importMysqlColumns($this);

        $columns = Syscolumn::when(
            $op == 'grid',
            function ($query) {
                $query->where('grid', 1);
            }
        )->when(
                $op == 'form',
                function ($query) use ($action) {
                    $query->where("form_on_{$action}", 1);
                }
            )->where('table', $this->getTable())
            //->whereNotIn('name', $this->hidden)
            ->get()->toArray();

        if ($op == 'grid') {
            $formattedColumns = array_map(
                function ($column) {
                    return [
                        'data' => $column['name'],
                        'name' => $column['name'],
                        'width' => $column['grid_width']
                    ];
                },
                $columns
            );
        } else {

            // form
            $formattedColumns =
                array_filter(
                    $columns,
                    function ($column) use ($action) {
                        return $column["form_on_{$action}"]; // filter by form_on_create, form_on_show, form_on_edit, form_on_review
                    }
                );
        }

        return $formattedColumns;

    }

}