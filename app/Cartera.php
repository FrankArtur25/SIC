<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cartera extends Model
{
    protected $connection = 'mysql';

    public static function listCarteras(){
        return DB::connection('mysql')->select(DB::raw("
            select 
                car_id as id, 
                car_nom as cartera
            from cartera
            where car_est=0 and car_pas=0
        "));
    }

    public static function listCarterasUsuarioSMS(){
        $usuario=auth()->user()->emp_cod;
        $rol=DB::connection('mysql')->select(DB::raw("select rol_id_FK as rol from creditoy_lotesms.usuario where usuario=:usu and usu_est=0 limit 1"),array("usu"=>$usuario));
        $sql="";
        // return $rol;
        if(count($rol)>0){
            if($rol[0]->rol!=1){
                $sql.=" and car_id in (SELECT car_id_FK from creditoy_lotesms.empleado WHERE emp_est=0 and usu_FK=$usuario) ";
            }
        }
        return DB::connection('mysql')->select(DB::raw("
            select 
                car_id as id, 
                car_nom as cartera
            from cartera
            where car_est=0 and car_pas=0
            $sql
        "));
    }

    public static function listCarterasUsuario(){
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        if($carteras!=0){
            $sql="and car_id in ($carteras)";
        }

        return DB::connection('mysql')->select(DB::raw("
            select 
                car_id as id, 
                car_nom as cartera
            from cartera
            where car_est=0 and car_pas=0
            $sql
        "),array("usu"=>auth()->user()->emp_cod));
    }
}
