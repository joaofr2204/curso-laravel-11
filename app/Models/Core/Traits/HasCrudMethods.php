<?php

namespace App\Models\Core\Traits;

use App\Models\Core\Syscolumn;
use App\Models\Core\Sysdb;
use App\Models\Core\Systable;

trait HasCrudMethods
{

    public function getGridColumns()
    {
        //-- FAZ IMPORTACAO AUTOMATICA, PENSAR EM ALGO QUE NAO FAÇA TODAS AS VEZES
        Sysdb::importDb($this);
        Systable::importMysqlTable($this);
        Syscolumn::importMysqlColumns($this);

        $columns = Syscolumn::where('table', $this->getTable())
            ->where('grid', 1)
            ->get()->toArray();

        //-- format the way datatables expects
        $formattedColumns = array_reduce(
            $columns,
            function ($carry, $column) {
                $col = [
                    'data' => $column['name'],
                    'name' => $column['name'],
                    'width' => $column['grid_width'],
                    'syscolumn' => $column,
                ];

                if (in_array($column['type'], ['CI', 'CV', 'ST', 'CH'])) {
                    $col['syscolumn']['options'] = Syscolumn::getOptions($column['sqlcombo']);
                }

                if ($column['grid_align'] == 'center' || in_array($column['type'], ['CH', 'TS'])) {
                    $col['className'] = 'text-center'; // Classe CSS para centralizar'
                }

                if ($column['type'] == 'BI') {
                    $col['className'] = 'text-right'; // Classe CSS para centralizar'
                }

                $carry[$column['name']] = $col;

                return $carry;
            },
            []
        );

        return $formattedColumns;

    }

    public function getFormColumns($action)
    {
        $columns = Syscolumn::where('table', $this->getTable())
            ->where("form_on_{$action}", 1)
            ->get()->toArray();

        //-- format the way datatables expects
        $formattedColumns = array_map(
            function ($column) {
                if (in_array($column['type'], ['CI', 'CV'])) {
                    $column['options'] = Syscolumn::getOptions($column['sqlcombo']);
                }
                return $column;
            },
            $columns
        );

        return $formattedColumns;

    }

}