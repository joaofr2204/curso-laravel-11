<?php

namespace App\Models\Core;

use App\Models\Core\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function onCreateSyscolumn(&$column)
    {
        switch ($column->name) {
            case 'password':
                $column->type = 'PW';
                $column->required_on_edit = false;
                $column->grid = false;
                break;
            case 'active':
                $column->type = 'CH'; // status type
                $column->sqlcombo = json_encode([
                    0 => ['red', 'times', 'Inativo'],
                    1 => ['green', 'check', 'Ativo']
                ]); // value of field / css color
                $column->grid_align = 'center';
                $column->grid_label = '';
                $column->grid_width = 30;
                break;
            case 'remember_token':
                $column->form_on_create = 0;
                $column->grid = 0;
                break;
            case 'email_verified_at':
                $column->form_on_create = 0;
                break;
        }


    }
}
