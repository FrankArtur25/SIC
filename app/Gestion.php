<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Gestion extends Model
{
    
    public static function insertarGestion(Request $rq,$fechaGestion){
        $detalle=$rq->detalle;
        $monto=isset($rq->montoPDP)?$rq->montoPDP:'';
        $fechapc=isset($rq->fechaPDP)?$rq->fechaPDP:'';
        $moneda=$rq->moneda;
        $tel=isset($rq->telefono)?$rq->telefono:'0';
        $resp=$rq->respuesta;
        $id=$rq->id;
        $idEmpleado=auth()->user()->emp_id;
        $fechaPDP='0000-00-00';
        $montoPDP=0;
        $fechaConf='0000-00-00';
        $montoConf=0;
        $motivo=0;
        if($resp==1 || $resp==43 ){
            $fechaPDP=$fechapc;
            $montoPDP=$monto;
        }
        if($resp==2){
            $fechaConf=$fechapc;
            $montoConf=$monto;
        }
        
        $acceso=auth()->user()->emp_tip_acc;

        if($resp==33 && $acceso!=8){
            $motivo=$rq->motivoNoPago;
        }

        DB::connection('mysql')->insert("
            insert into gestion_cliente (
                cli_id_FK,emp_id_FK,ges_cli_acc,res_id_FK,ges_cli_fec,ges_cli_med,
                ges_cli_det,ges_cli_com_fec,ges_cli_com_can,ges_cli_com_mon,ges_cli_ret,
                ges_cli_est,ges_cli_pas,ges_cli_conf_can,ges_cli_conf_fec,ges_cli_fec_visit,mot_id_FK)
            values (:id,:idEmp,2,:res,:fecGes,:tel,:det,:fecpdp,:monpdp,:monedapdp,0,0,0,:monconf,:fecconf,'0000-00-00',:motivo)
        ",array("id"=>$id,"idEmp"=>$idEmpleado,"res"=>$resp,"fecGes"=>$fechaGestion,"tel"=>$tel,
                "det"=>$detalle,"fecpdp"=>$fechaPDP,"monpdp"=>$montoPDP,"monedapdp"=>$moneda,"monconf"=>$montoConf,"fecconf"=>$fechaConf,"motivo"=>$motivo));

        return "ok";
    }

    public static function insertarPDP(Request $rq,$fechaGestion,$idGestion){
        $monto=$rq->montoPDP;
        $fechapc=$rq->fechaPDP;
        $moneda=$rq->moneda;
        $id=$rq->id;
        $idEmpleado=auth()->user()->emp_id;
        
        DB::connection('mysql')->insert("
            insert into  compromiso_cliente(
                cli_id_FK,emp_id_FK,ges_cli_id_FK,com_cli_fec,com_cli_fec_pag,com_cli_can,
                com_cli_mon,com_cli_est,com_cli_pas)
            values (:id,:idEmp,:idGes,:fecGes,:fecpdp,:monto,:moneda,0,0)
        ",array("id"=>$id,"idEmp"=>$idEmpleado,"idGes"=>$idGestion,"fecGes"=>$fechaGestion,"fecpdp"=>$fechapc,"monto"=>$monto,"moneda"=>$moneda));
            
        return "ok";
    }

    public static function selectIdGestion($fechaGestion,$id,$tel){
        $idEmpleado=auth()->user()->emp_id;
        return DB::connection('mysql')->select(DB::raw("
            select ges_cli_id as id
            from gestion_cliente
            where ges_cli_fec=:fec
            and cli_id_FK=:id
            and emp_id_FK=:idEmp
            and ges_cli_med=:tel
        "),array("fec"=>$fechaGestion,"id"=>$id,"idEmp"=>$idEmpleado,"tel"=>$tel));
    }

    public static function updateUltGestion($id,$idGestion){
        DB::connection('mysql')->update("
            update cliente
            set ges_cli_tel_id_FK=:idGes
            where cli_id=:id
        ",array("id"=>$id,"idGes"=>$idGestion));
        return "ok";
    }

    public static function validarContacto($id){
        $sql="
              SELECT count(*) AS cant_contacto
              FROM  gestion_cliente g
              INNER JOIN respuesta r ON g.res_id_fk=r.res_id
              WHERE  cli_id_fk = :id
                AND res_ubi = 0
                AND ( Date(ges_cli_fec) BETWEEN Date(Date_add(Date_add( Last_day(Now()), INTERVAL -2 MONTH), INTERVAL 1 DAY )) AND Date(Now()) )
                AND ges_cli_acc IN (1,2)
            ";
        return DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
    }

    public static function validarPDP($id){
        $sql="
            SELECT 
                com_cli_fec as fecha_ges,
                com_cli_fec_pag as fecha_pag,
                com_cli_can as monto,
                com_cli_mon as moneda,
                emp_nom as usuario
            FROM compromiso_cliente cc 
            INNER JOIN empleado e ON cc.emp_id_FK = e.emp_id 
            WHERE cli_id_FK =:id
            AND com_cli_est<>1 
            AND com_cli_pas<>1 
            AND date_format(com_cli_fec_pag,'%Y%m')=date_format(now(),'%Y%m')
            ORDER BY com_cli_id DESC 
            LIMIT 1 
        ";
        return DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
    }

    public static function updateCompromisoEstado($id){
        DB::connection('mysql')->update("
            UPDATE compromiso_cliente SET com_cli_est=1 WHERE cli_id_FK=:id
        ",array("id"=>$id));
        return "ok";
    }

    public static function updateUltCompromiso($id,$idcomp){
        DB::connection('mysql')->update("
            UPDATE cliente SET com_cli_id_FK=:idcomp WHERE cli_id=:id 
        ",array("idcomp"=>$idcomp,"id"=>$id));
        return "ok";
    }

    public static function selectIdCompromiso($id,$idGestion,$fechaGestion){
        return DB::connection('mysql')->select(DB::raw("
            select com_cli_id as id
            from compromiso_cliente
            where 
            cli_id_FK=:id
            and com_cli_fec=:fec
            and ges_cli_id_FK=:idGes
        "),array("id"=>$id,"fec"=>$fechaGestion,"idGes"=>$idGestion));
    }
    
    public static function validarDetalleIdentico(Request $rq){
        $idEmpleado=auth()->user()->emp_id;
        $detalle=$rq->detalle;
        return DB::connection('mysql')->select(DB::raw("
            select count(*) as cant
            from gestion_cliente
            where 
                date_format(ges_cli_fec,'%Y%m')=date_format(now(),'%Y%m')
            and emp_id_FK=:idEmp
            and ges_cli_det like ('".$detalle."')
        "),array("idEmp"=>$idEmpleado));
    }
}
