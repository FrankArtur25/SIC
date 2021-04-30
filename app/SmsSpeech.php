<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SmsSpeech extends Model
{
    public static function carteraSpeech($cartera){
        return DB::connection('mysql')->select(DB::raw("
            SELECT
                spc_id as id,
                spc_nom as nombre,
                spc_des as descripcion
            FROM
                creditoy_lotesms.speech
            WHERE 
            spc_est=0
            AND car_id_FK=:car
        "),array("car"=>$cartera));
    }

    public static function etiquetas(){
        return DB::connection('mysql')->select(DB::raw(
            "SELECT
                etq_nom as nombre,
                etq_long as cant
            FROM
                creditoy_lotesms.etiquetas
            WHERE
            etq_est=0"
        ));
    }

    public static function insertSpeech(Request $rq){
        $nombre=$rq->nombre;
        $descripcion=$rq->texto;
        $cartera=$rq->cartera;
        $fecha=Carbon::now();
        
        DB::connection('mysql')->insert("
            INSERT INTO creditoy_lotesms.speech (spc_nom,spc_des,car_id_FK,spc_est,fecha_create)
            VALUES (:nom,:des,:car,0,:fecha)
        ",array("nom"=>$nombre,"des"=>$descripcion,"car"=>$cartera,"fecha"=>$fecha));
        return "ok";
    }
}
