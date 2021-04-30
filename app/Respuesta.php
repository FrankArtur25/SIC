<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Respuesta extends Model
{
    protected $connection = 'mysql';
    
    public static function listRespuestas(){
        return DB::connection('mysql')->select(DB::raw("
            SELECT 
                res_id,res_des
            from 
                respuesta
            WHERE 
                res_est=0 and res_pas=0
                and res_id not in (46,39,10,8,7,13)
                and res_acc like('%2%')
            order by res_des 
            "));
    }

    public static function listaRespuestaUbicabilidad($ubic){
        $sql="
            select 
                res_id as id,
                res_des as respuesta
            from respuesta
            WHERE 
                res_ubi=:ubi
                and res_est=0 
                and res_pas=0
                and res_id not in (46,39,10,8,7,13)
                and res_acc like('%2%')
            order by res_des 
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql),array("ubi"=>$ubic));
        return $query;
    }

    public static function listaMotivosNoPago(){
        return DB::connection('mysql')->select(DB::raw("
            SELECT 
                mot_id as id,
                mot_res as motivo
            from 
                motivo_nopago
            WHERE 
                mot_est=0
            "));
    }

    public static function listaEntidades($cartera){
        // $cartera=session()->get('datos')->idcartera;
        $sql="
            SELECT
                tag_valor as id,
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='entidades'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaScore($cartera){
        // $cartera=session()->get('datos')->idcartera;
        $sql="
            SELECT
                tag_valor as id,
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='score'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaOficinas(){
        $sql="
            SELECT 
                loc_id as idoficina,
                concat(loc_cod,'-',loc_nom)  as local,
                loc_nom as oficina
            FROM local 
            WHERE 
                loc_pas<>1 
            AND loc_est<>1 
            ORDER BY loc_nom ASC 
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }

    public static function listaDescuentos($cartera){
        // $cartera=session()->get('datos')->idcartera;
        $sql="
            SELECT
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='descuento'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaPrioridad($cartera){
        // $cartera=session()->get('datos')->idcartera;
        $sql="
            SELECT
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='prioridad'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaRespuestaUbicSms($ubic){
        $sql="
            select 
                res_id as id,
                res_des as respuesta
            from respuesta
            WHERE 
                res_ubi in ($ubic)
                and res_est=0 
                and res_pas=0
                and res_id NOT IN (1, 43, 2, 6,	12,	13,	19,	22,	27,	28,	37,	38,	41,	46)
                and res_acc like('%2%')
            order by res_des 
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }
    
    public static function listRespuestasUbicabilidad(){
        return DB::connection('mysql')->select(DB::raw("
            SELECT 
                res_id,
                res_des,
                (CASE 
                    WHEN res_id IN ( 38, 6, 22, 41 ) THEN 'C-F-R-N'
                    WHEN res_id IN ( 2,37,33,10,1,8,43,39,7,3,5,9,34,17,21,18,28,30,35,36,46,47,48,49 ) THEN 'Contacto'
                    WHEN res_id IN ( 32 ) THEN 'No Disponible'
                    WHEN res_id IN ( 19,27,12,26,13,4,11,12,20,14,15,16,23,24,29,31 ) THEN 'Ilocalizado'
                    WHEN res_id IN ( 45,44, 25 ) THEN 'No Contacto'
                    ELSE 'NO ENCONTRADO'
                END) AS ubicabilidad
            from 
                respuesta
            WHERE 
                res_est=0 and res_pas=0
                and res_id not in (46,39,10,8,7,13)
                and res_acc like('%2%')
                order by res_des
            "));
    }

    public static function listaTramo($cartera){
        // $cartera=session()->get('datos')->idcartera;
        $sql="
            SELECT
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='tramo'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaZona($cartera){
        $sql="
            SELECT
                tag_valor as valor
            FROM
                creditoy_lotesms.tag_condicion
            WHERE 
                car_id_FK in (:car)
            and tag_tipo='zona'
            and tag_est=0
            group by tag_valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("car"=>$cartera));
        return $query;
    }

    public static function listaCall(){
        $sql="
                SELECT 
                    cal_id as id,
                    cal_nom as calll
                FROM
                    creditoy_cobranzas.call_telefonica
                WHERE cal_est=0
                and cal_pas=0
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }

    public static function listRespuestasCampo(){
        return DB::connection('mysql')->select(DB::raw("
            SELECT 
                res_id,res_des
            from 
                respuesta
            WHERE 
                res_est=0 and res_pas=0
                and res_id not in (46,39,10,8,7,13)
                and res_acc like('%4%')
            order by res_des 
            "));
    }


    public static function listaRespuestaUbicabilidadBolsa($ubic){
        $sql="
            select 
                res_id as id,
                res_des as respuesta
            from respuesta
            WHERE 
                res_ubi=:ubi
                and res_est=0 
                and res_pas=0
                and res_id not in (46,39,10,8,7,13)
                and res_acc like('%8%')
            order by res_des 
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql),array("ubi"=>$ubic));
        return $query;
    }
}
