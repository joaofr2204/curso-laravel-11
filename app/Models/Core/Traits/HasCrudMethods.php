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
            
            //-- format the way datatables expects
            $formattedColumns = array_map(
                function ($column) {
                    $columns = [
                        'data' => $column['name'],
                        'name' => $column['name'],
                        'width' => $column['grid_width']
                    ];

                    if ($column['type'] == 'CV') {
                        $columns['options'] = Syscolumn::getOptions($column['sqlcombo']);
                    }

                    return $columns;
                },
                $columns
            );
        } else {

            // form
            //-- format the way datatables expects
            $formattedColumns = array_map(
                function ($column) {
                    if($column['type'] == 'CV') {
                        $column['options'] = Syscolumn::getOptions($column['sqlcombo']);
                    }
                    return $column;
                },
                $columns
            );
        }

        return $formattedColumns;

    }

}