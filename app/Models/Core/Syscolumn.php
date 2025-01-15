<?php

namespace App\Models\Core;

use Illuminate\Support\Facades\DB;
class Syscolumn extends BaseModel
{
    use \App\Models\Core\Traits\Syscolumn_Mysql_Integration;

    private static function nextOrder($table, $field)
    {
        return (Syscolumn::where('table', $table)->max($field) ?? 0) + 1;
    }

    public static function getOptions($sql):array
    {
        if(empty($sql)){
            return [];
        }

        if (substr($sql, 0, 6) == 'select') {
            return self::sqlToArray($sql);
        } else {
            return json_decode($sql,true);
        }
    }

    private static function sqlToArray($sql): array   
    {
        $results = DB::select($sql);

        $assocResults = [];
        foreach ($results as $row) {
            $rowArray = (array) $row; // Converter cada resultado em array associativo
            $assocResults[array_values($rowArray)[0]] = array_values($rowArray)[1];
        }

        return $assocResults;
    }

    public function onCreateSyscolumn(&$column)
    {
        switch ($column->name) {

            case 'table':
                $column->type = 'CV';
                $column->sqlcombo = 'select name a, name from systables';
                break;
            case 'type':
                $column->type = 'CV';
                $column->sqlcombo = json_encode([
                    'VC' => 'Varchar',
                    'CV' => 'Combo Varchar',
                    'CI' => 'Combo Integer',
                    'TS' => 'Data-Hora',
                    'IN' => 'Integer',
                    'BI' => 'BigInteger',
                    'PW' => 'Password',
                    'CH' => 'Checkbox',
                    'ST' => 'Status',
                    'TX' => 'Text',
                ]);
                break;
            case 'context':
                $column->sqlcombo = json_encode([
                    'R' => 'Real',
                    'V' => 'Virtual',
                ]);

                break;

            case 'active':
                $column->type = 'ST'; // status type
                $column->sqlcombo = json_encode([
                    0 => ['red', 'times', 'Inativo'],
                    1 => ['green', 'check', 'Ativo']
                ]); // value of field / css color

                $column->grid_align = 'center';
                $column->grid_label = '';
                $column->grid_width = 30;
                break;
        }


    }
}
