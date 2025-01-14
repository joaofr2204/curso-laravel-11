<?php

namespace App\Models\Core\Traits;

use App\Models\Core\BaseModel;
use App\Models\Core\Syscolumn;
use App\Models\Core\Sysdb;
use App\Models\Core\Systable;
use Illuminate\Support\Facades\DB;

trait Syscolumn_Mysql_Integration
{

    //-- import table columns info from database (mysql)
    public static function importMysqlColumns(BaseModel $model)
    {

        $columns = DB::select('SHOW COLUMNS FROM ' . $model->getTable());

        //-- Associativo, com keyBy
        $exists = Syscolumn::where('table', $model->getTable())
            ->whereIn('name', array_column($columns, 'Field'))
            ->get()->keyBy('name')->toArray();

        foreach ($columns as $col) {
            if (!array_key_exists($col->Field, $exists)) {
                static::createSysColumn($model, $col);
            }
        }
    }

    private static function createSysColumn(BaseModel $model, $col)
    {

        $table = $model->getTable();

        $s = new Syscolumn;

        //-- I need to modify de line bellow because "table" is an laravel Model's attribute
        $s->attributes['table'] = $table;
        $s->name = $col->Field;
        $s->form_label = $col->Field;
        $s->grid_label = $col->Field;
        $s->form_on_create = true;
        $s->form_on_show = true;
        $s->form_on_edit = true;
        $s->form_on_review = true;
        $s->grid = true;
        $s->grid_order = static::nextOrder($table, 'grid_order');
        $s->form_order = static::nextOrder($table, 'grid_order');
        $s->filterby = true;
        $s->searchby = true;
        $s->context = 'R';

        //-- emp / fil
        if ($col->Field == 'emp' || $col->Field == 'fil') {
            $s->readonly_on_create = 1;
            $s->readonly_on_edit = 1;
            $s->readonly_on_review = 1;
            $s->form_on_create = 0;
            $s->form_on_show = 0;
            $s->form_on_edit = 0;
            $s->form_on_review = 0;
            $s->grid = 0;
        }

        //-- id
        if ($col->Field == 'id') {
            $s->form_label = 'Id';
            $s->grid_label = 'Id';

            $s->form_on_create = 0;
            $s->form_on_edit = 0;
            $s->readonly_on_create = 1;
            $s->readonly_on_edit = 1;
            $s->readonly_on_review = 1;
            $s->grid_width = 70;
        }

        //created_at / updated_at
        if ($col->Field == 'created_at' || $col->Field == 'updated_at') {
            $s->form_on_create = 0;
            $s->readonly_on_create = 1;
            $s->readonly_on_edit = 1;
            $s->readonly_on_review = 1;
            $s->form_label = $col->Field == 'created_at' ? 'Criado em' : 'Alterado em';
            $s->grid_label = $col->Field == 'created_at' ? 'Criado em' : 'Alterado em';
            $s->grid = 0;
        }

        if ($col->Field == 'deleted_at') {

            $s->form_on_create = 0;
            $s->form_on_show = 0;
            $s->form_on_edit = 0;
            $s->form_on_review = 0;
            $s->grid = 0;

        }

        // bigint
        if (strpos($col->Type, 'bigint') !== false) {
            $s->type = 'BI';
        }

        // checkbox
        elseif (strpos($col->Type, 'tinyint') !== false) {
            $s->type = 'CH';
        }

        // int
        elseif (strpos($col->Type, 'int(') !== false) {
            $s->type = 'IN';
        }

        // timestamp = datetime
        elseif (strpos($col->Type, 'timestamp') !== false) {
            $s->type = 'TS';
        }

        // combobox
        elseif (strpos($col->Type, 'enum') !== false) {

            $options = str_replace('enum(', '', $col->Type);
            $options = str_replace(')', '', $options);
            $options = explode(',', $options);

            $combovalues = ['' => ''];

            foreach ($options as $o) {

                $o = substr($o, 1, strlen($o) - 2);

                $combovalues[$o] = $o;
            }

            $s->sqlcombo = json_encode($combovalues);

            $s->type = 'CV'; // combo string
        } else {
            $s->type = 'VC'; // varchar
        }

        $s->required_on_create = $col->Null == 'NO' && is_null($col->Default);
        $s->required_on_edit = $col->Null == 'NO' && is_null($col->Default);
        $s->required_on_review = $col->Null == 'NO' && is_null($col->Default);

        // $s->default = $col->Default;

        //-- intercept the syscolumn creation
        if (method_exists($model, 'onCreateSyscolumn')) {
            $model->onCreateSyscolumn($s);
        }

        $s->save();

    }
}