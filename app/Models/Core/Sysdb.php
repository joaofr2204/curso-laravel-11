<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Sysdb extends Model
{

    public static function ofModel(Model $model)
    {

        return Sysdb::where('type', $model->getConnection()->getName())
                    ->where('name', $model->getConnection()->getDatabaseName());
    }

    public static function importDb(Model $model)
    {

        if(!Sysdb::ofModel($model)->exists()) {
            static::createSysdb($model);
        }
    }

    private static function createSysdb(Model $model)
    {

        $s = new Sysdb;

        $s->type = $model->getConnection()->getName();
        $s->name = $model->getConnection()->getDatabaseName();

        $s->save();

    }
}