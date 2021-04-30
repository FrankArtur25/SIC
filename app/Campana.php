<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Campana extends Model
{
    protected $connection = 'mysql';

    public static function estadosCampana(){
        $cartera=session()->get('datos')->idcartera;
        $sql="
            select 
            id_cartera,nombre_camp,fecha_i,fecha_f,
            DATE_FORMAT(fecha_i, '%d/%m') as dia,TIME_FORMAT(fecha_i, '%H:%i %p') as hora,
            (case 
                WHEN fecha_i > date(now()) THEN 'PC'
                WHEN fecha_i = date(now()) or (fecha_i < date(now()) and fecha_f >= date(now())) THEN 'CE'
                WHEN fecha_i < date(now()) or fecha_f < date(now()) THEN 'CC'
            end) as estado
            from indicadores.campana 
            where id_cartera in (:car)
                and date(now()) BETWEEN fecha_i and fecha_f
            ORDER BY fecha_i asc
            limit 1
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }    
}
