<?php

namespace App\Models\Core;  

use App\Models\Core\Traits\HasCrudMethods;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasCrudMethods;
}