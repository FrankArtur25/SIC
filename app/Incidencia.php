<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Incidencia extends Model
{
    public static function tiposIncidencias(){
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        tip_inc_id as id,
                        tip_inc_des as incidencia
                    FROM
                        db_incidencias.tipo_incidencia
                    WHERE
                        tip_inc_est = 0
        "));
    }

    public static function insertIncidencia(Request $rq){
        $gestor=$rq->gestor;
        $incidencia=$rq->incidencia;
        $detalle=$rq->detalle;
        $horaInicio=$rq->horaInicio;
        $horaFin=isset($rq->horaFin)?$rq->horaFin:null;
        $fecha=isset($rq->fecha)?$rq->fecha:'0000-00-00';
        $usuario=auth()->user()->emp_nom;
        $codigo=auth()->user()->emp_cod;
        $tipoUsuario=session()->get('datos')->perfil;
        DB::connection('mysql')->insert("
                INSERT INTO db_incidencias.incidencia 
                (inc_gestor,inc_usuario,inc_tipo_usuario,tip_inc_id_FK,inc_detalle,inc_hora_inicio,inc_hora_fin,inc_fecha,inc_codigo,inc_est_recupero,fecha_add,inc_est)
                VALUES (:gestor,:usu,:tipusu,:inc,:det,:horaInicio,:horaFin,:fec,:cod,'',now(),0)              
        ",array('gestor'=>$gestor,"usu"=>$usuario,"tipusu"=>$tipoUsuario,"inc"=>$incidencia,"det"=>$detalle,"horaInicio"=>$horaInicio,"horaFin"=>$horaFin,"fec"=>$fecha,"cod"=>$codigo));
        return "ok";
    }

    public static function buscarIncidencias(Request $rq){
        $incidencia=$rq->incidencia;
        $fechaDesde=$rq->fechaDesde;
        $fechaHasta=$rq->fechaHasta;
        $supervisor=$rq->supervisor;
        $gestor=$rq->gestor;
        $estadoRec=$rq->estadoRec;
        $estadoHora=$rq->estadoHora;
        $codigo=auth()->user()->emp_cod;
        $tipoacceso=auth()->user()->emp_tip_acc;
        $sql="";

        if($tipoacceso!=7 && $supervisor==''){
            $sql.=" and inc_codigo=$codigo ";
        }

        if($fechaDesde!='' && $fechaHasta!=''){
            $sql.=" and (case when tip_inc_id_FK=4 then date(inc_fecha) between '$fechaDesde' and '$fechaHasta'
                         ELSE date(fecha_add) between '$fechaDesde' and '$fechaHasta'
                    END)";
        }
        if($incidencia!=''){
            $sql.=" and tip_inc_id_FK=$incidencia ";
        }

        if($supervisor!=''){
            $sql.=" and inc_codigo=$supervisor";
        }
        
        if($gestor!=''){
            $sql.=" and inc_gestor like ('%$gestor%')";
        }

        if($estadoRec!=''){
            $sql.=" and inc_est_recupero=$estadoRec";
        }

        if($estadoHora!=''){
            if($estadoHora==0){
                $sql.=" and inc_hora_fin is null || inc_hora_fin in ('00:00:00')";
            }
    
            if($estadoHora==1){
                $sql.=" and inc_hora_fin not in ('00:00:00')";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        inc_id as id,
                        inc_gestor as gestor,
                        inc_usuario as usuario,
                        inc_tipo_usuario as tipo_usuario,
                        tip_inc_des as incidencia,
                        tip_inc_id_FK as idIncidencia,
                        inc_detalle as detalle,
                        inc_fecha as fecha_permiso,
                        inc_hora_inicio as horaInicio,
                        if(inc_hora_fin is null || inc_hora_fin in ('00:00:00'),'No definida',inc_hora_fin) as horaFin,
                        fecha_add,
                        inc_est_recupero as idestado,
                        if(tip_inc_id_FK=4,date(inc_fecha),fecha_add) as fecha,
                        case when inc_est_recupero=1 then 'Pendiente de Recuperación'
                             when inc_est_recupero=2 then 'Recuperado' 
                             else ''
                        end as estado
                    FROM
                        db_incidencias.incidencia i 
                    INNER JOIN db_incidencias.tipo_incidencia t on i.tip_inc_id_FK=t.tip_inc_id
                    WHERE
                        inc_est=0
                    $sql
        "));
    }
    
    public static function incListaSupervisores(){
        return DB::connection('mysql')->select(DB::raw("
            select emp_cod as codigo,emp_nom as supervisor from empleado where emp_est=0 and emp_tip_acc=1 and password is not null order by emp_nom asc
        "));
    }

    public static function incListaGestores(){
        $tipoacceso=auth()->user()->emp_tip_acc;
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        if($tipoacceso==1){ 
            $sql=" and car_id_FK in ($carteras)";
        }
        return DB::connection('mysql')->select(DB::raw("
            select emp_id as id,emp_nom as gestor from sub_empleado where emp_est=0 $sql and emp_firma not in ('') order by emp_nom asc
        "));
    }

    public static function editarIncidencia(Request $rq){
        $horaInicio=$rq->horaInicio;
        $horaFin=$rq->horaFin;
        $detalle=$rq->detalle;
        $estadoRec=isset($rq->estadoRec)?$rq->estadoRec:0;
        $id=$rq->id;

        DB::connection('mysql')->update("
            UPDATE db_incidencias.incidencia 
            SET inc_hora_inicio=:horaIni,
                inc_hora_fin=:horaFin,
                inc_detalle=:detalle,
                inc_est_recupero=:estado
            WHERE inc_id=:id
        ",array("id"=>$id,"horaIni"=>$horaInicio,"horaFin"=>$horaFin,"estado"=>$estadoRec,"detalle"=>$detalle));

        return "ok";
    }
}
