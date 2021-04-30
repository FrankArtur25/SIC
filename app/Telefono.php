<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Telefono extends Model
{
    public static function infoTelefonos($id){
        $sql="
            SELECT 
                ct.cli_id_FK as id,
                count(cli_tel_tel) as gestion,
                sum(if(res_ubi=0,1,0)) as contacto,
                CASE WHEN bit_tel_tipo='Digitador' THEN CONCAT(cli_tel_tel,' - Campo')
                        WHEN bit_tel_tipo='Gestor' THEN CONCAT(cli_tel_tel,' - Call')
                        WHEN bit_tel_tipo='Admin' THEN CONCAT(cli_tel_tel,' - Supervisor')
                        ELSE cli_tel_tel
                END AS tel,
                cli_tel_tel as telefono,
                cli_tel_est as estado
            FROM
                    cliente_telefono ct
            LEFT JOIN gestion_cliente gc ON ct.cli_tel_tel=gc.ges_cli_med and ct.cli_id_FK=gc.cli_id_FK
            LEFT JOIN respuesta r on r.res_id=gc.res_id_FK
            LEFT JOIN bitacora_telefono b ON b.bit_tel_cli_id = ct.cli_id_FK AND b.bit_tel_tel=ct.cli_tel_tel
            WHERE
                    ct.cli_id_FK=:id
                    AND cli_tel_est=0
                    AND cli_tel_pas=0
            GROUP BY cli_tel_id
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
        return $query;
    }

    public static function insertarTelefonos(Request $rq,$fecha){
        $telefono=$rq->telefono;
        $id=$rq->id;
        //$id=121049517;
        DB::connection('mysql')->insert("
            insert into cliente_telefono (cli_id_FK,cli_tel_tel,cli_tel_est,cli_tel_pas,valor,fecha_add)
            values (:id,:tel,0,0,'',:fecha)
        ",array("id"=>$id,"tel"=>$telefono,"fecha"=>$fecha));

        return "ok";
    }

    public static function insertarBitacoraTelefonos(Request $rq,$fecha){
        $telefono=$rq->telefono;
        $id=$rq->id;
        $idEmpleado=auth()->user()->emp_id;
        $tipo_acceso=auth()->user()->emp_tip_acc;
        $tipo='';
        if($tipo_acceso==1){
            $tipo='Admin';
        }else if($tipo_acceso==2){
            $tipo='Gestor';
        }else if($tipo_acceso==3){
            $tipo='Digitador';
        }
        DB::connection('mysql')->insert("
            insert into bitacora_telefono (bit_tel_tipo,bit_tel_user,bit_tel_cli_id,bit_tel_tel,bit_tel_fecha)
            values (:tipo,:user,:id,:tel,:fecha)
        ",array("tipo"=>$tipo,"user"=>$idEmpleado,"id"=>$id,"tel"=>$telefono,"fecha"=>$fecha));
        return "ok";
    }

    public static function actualizarEstadoTelefono(Request $rq){
        $telefono=$rq->telefono;
        $id=$rq->id;
        $estado=$rq->estado;
        $est=0;$pas=0;
        if($estado==1){
            $est=1;
            $pas=0;
        }
        if($estado==2){
            $est=0;
            $pas=1;
        }
        
        DB::connection('mysql')->update("
            update cliente_telefono 
            set cli_tel_est=:est,
                cli_tel_pas=:pas
            where cli_id_FK=:id
            and cli_tel_tel=:tel
        ",array("id"=>$id,"tel"=>$telefono,"est"=>$est,"pas"=>$pas));

        return "ok";
    }
    
}
