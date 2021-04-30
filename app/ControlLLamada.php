<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ControlLLamada extends Model
{
    //Conexion a servidor elastix (local)
    protected $connection = 'elastix';

    public static function controlLLamadas(Request $rq){
        $cartera=$rq->cartera;
        $fecha=$rq->fecha;
        return DB::connection('elastix')->select(DB::raw("
            SELECT
                fecha,
                src_FK as extension,
                concat(codigo,' - ',nombre) as agente,
                intento_llamadas,
                llamadas_conectadas,
                llamadas_noconectadas,
                promedio_duracion,
                tiempo_logeo
            FROM
                agente a
            LEFT JOIN(SELECT
                fecha,
                src,
                sum(conectada)+ sum(no_conectada) as intento_llamadas,
                sum(conectada) as llamadas_conectadas,
                sum(no_conectada) as llamadas_noconectadas,
                SEC_TO_TIME((format(sum(duracion)/SUM(conectada),0))) as promedio_duracion,
                SEC_TO_TIME(ultima_llamada-primera_llamada) as tiempo_logeo
            FROM
                (SELECT
                        date(calldate) as fecha,
                        src,
                        if(disposition='ANSWERED',1,0) as conectada,
                        if(disposition='ANSWERED',0,1) as no_conectada,
                        if(disposition='ANSWERED',duration,0) as duracion,
                        (HOUR(primera_llamada)*3600) + (MINUTE(primera_llamada)*60)+ SECOND(primera_llamada) as primera_llamada,
                        (HOUR(ultima_llamada)*3600) + (MINUTE(ultima_llamada)*60)+ SECOND(ultima_llamada) as ultima_llamada
                    FROM
                        cdr c
                    LEFT JOIN (SELECT MIN(calldate) as primera_llamada,src as src_p FROM cdr WHERE date(calldate)=:fecha_p GROUP BY src)p ON c.src=p.src_p
                    LEFT JOIN (SELECT MAX(calldate) as ultima_llamada,src as src_u FROM cdr WHERE date(calldate)=:fecha_u GROUP BY src)u ON c.src=u.src_u
                    WHERE 
                    date(calldate)=:fecha
                )t
            GROUP BY src
            )tt ON tt.src=a.src_FK
            where
                estado=0
            and cartera=:car
        "),array("car"=>$cartera,"fecha"=>$fecha,"fecha_p"=>$fecha,"fecha_u"=>$fecha));
    }

    public static function controlLLamadasGestor(Request $rq){
        $agente=$rq->agente;
        $fecha=$rq->fecha;
        return DB::connection('elastix')->select(DB::raw("
            SELECT
                calldate as fecha,
                dstchannel as grupo_timbrado,
                dst as destino,
                if(disposition='ANSWERED',duration,0) as duracion_segundos,
                SEC_TO_TIME(duration) as duracion,
                disposition as estado
            FROM
                cdr
            WHERE 
                src=:extension
            and date(calldate)=:fecha
            order by calldate desc
        "),array("extension"=>$agente,"fecha"=>$fecha));
    }
}
