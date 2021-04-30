<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Actualizacion extends Model
{
    public static function infopagos(){
        $carterasPagos=DB::connection('mysql')->select(DB::raw("
            select car_id,car_nom, 'Sin Pagos a la Fecha' as fecha 
            from creditoy_cobranzas.cartera
            where 
                car_est=0 and car_pas=0 
                and car_id not in (23,57,86,74,73,84,85,62,63,81,80,91)
            order by car_nom asc
        "));

        $pagos=DB::connection('mysql')->select(DB::raw("
            select car_id_FK as car_id, max(pag_cli_fec) as fecha 
            FROM pago_cliente_2
            where DATE_FORMAT(pag_cli_fec,'%Y-%m')=DATE_FORMAT(now(),'%Y-%m')
            GROUP BY car_id_FK
        "));
        return response()->json(['carterasPagos' => $carterasPagos,'pagos' => $pagos]);
    }

    public static function infocarteras(){
        $carteras=DB::connection('mysql')->select(DB::raw("
            select car_id,car_nom, 'sincargar' as estado   from creditoy_cobranzas.cartera
            where car_est=0 and car_pas=0 and car_id not in (23,57,86,74,73,91)
            order by car_nom asc
        "));

        $carterasCargadas=DB::connection('mysql')->select(DB::raw("
            select car_id_fk as car_id, 'cargadas' as estado from indicadores.cartera_detalle
            where DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(now(),'%Y-%m')
            group by car_id_fk
        "));

        return response()->json(['carteras' => $carteras,'carterasCargadas'=> $carterasCargadas]);
    }
}
