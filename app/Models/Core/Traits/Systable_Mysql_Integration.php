<?php

namespace App\Models\Core\Traits;

use App\Models\Core\Sysdb;
use App\Models\Core\Systable;
use App\Models\Core\BaseModel;
// use Illuminate\Support\Facades\DB;

trait Systable_Mysql_Integration {

    //-- import table columns info from database (mysql)
    public static function importMysqlTable(BaseModel $model){

        if( !Systable::where('name',$model->getTable())
                     ->where('db', Sysdb::ofModel($model)->value('name') )
                     ->exists()
        ) {
            static::createSystable($model);
        }

    }

    private static function createSysTable(BaseModel $model){
        $s = new Systable;

        $s->name     = $model->getTable();
        $s->db = Sysdb::ofModel($model)->value('name');

        //-- intercept the systable creation
        if(method_exists($model,'onCreateSystable')){
            $model->onCreateSystable($s);
        }

        $s->save();
    }
}