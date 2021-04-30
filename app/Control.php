<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Control extends Model
{
    public static function reporteControl(){
        return DB::connection('mysql')->select(DB::raw("CALL indicadores.reporteControl()"));
    }

    public static function estandar(){
        return DB::connection('mysql')->select(DB::raw("
            select est_variable as var,est_valor as val from indicadores.estandar where est_estado=0
        "));
    }
}
