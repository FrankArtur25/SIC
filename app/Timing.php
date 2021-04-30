<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Timing extends Model
{
    public static function timingDiario($cartera){
        return DB::connection('mysql')->select(DB::raw("
                CALL creditoy_reporte.TIMING_DIARIO(:car)
        "),array("car"=>$cartera));
    }

    public static function proyectado($cartera){
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    dia,
                    if(proyectado_ideal is null,0,proyectado_ideal) as proyectado_ideal,
                    if(proyectado_real is null,0,proyectado_real) as proyectado_real,
                    CONCAT(if(proyectado is null,0,proyectado),'%') as proyectado,
                    if(desfase is null,0,desfase) as desfase
                FROM
                    creditoy_reporte.proyectado
                WHERE
                    date(fecha)=date(NOW())
                AND cartera=:car
                and estado=0
        "),array("car"=>$cartera));
    }

    public static function cumplimientoProyectado($cartera){
        return DB::connection('mysql')->select(DB::raw("
                 CALL creditoy_reporte.CUMPLIMIENTO_PROYECTADO(:car)
        "),array("car"=>$cartera));
    }
}
