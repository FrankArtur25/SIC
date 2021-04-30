<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';
    protected $table = "empleado";
    protected $primaryKey = "emp_id";

    protected $fillable = [
        'emp_cod','emp_cla', 'password', 'emp_nom'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setSession($sql){
        if(count($sql)==1){
            Session::put([
                'datos'=>$sql[0]
                ]
            );
        }
    }

    // public function getAuthPassword()
    // {
    //     return $this->emp_cla;
    // }
}
