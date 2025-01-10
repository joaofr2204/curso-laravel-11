<?php

namespace App\Models\Core;

class Syscolumn extends BaseModel
{
    use \App\Models\Core\Traits\Syscolumn_Mysql_Integration;

    private static function nextOrder($table,$field)
    {
        return (Syscolumn::where('table', $table)->max($field) ?? 0) + 1;
    }
}
