<?php

namespace App\Models\Core;

class Systable extends BaseModel
{
    //
    use \App\Models\Core\Traits\Systable_Mysql_Integration;

    public function onCreateSyscolumn(&$column)
    {
        switch ($column->name) {
            case 'empfil_share':
                $column->type = 'CV';
                $column->sqlcombo = json_encode([
                    'E' => 'Exclusive',
                    'S' => 'Shared',
                    'M' => 'Mixed'
                ]);
                break;
        }
    }
}
