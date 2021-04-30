<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Recordatorio extends Model
{
    public static function insertRecordatorio(Request $rq){
        $fechaRec=$rq->fechaRec." ".$rq->horaRec;
        $id=$rq->id;
        $tel_tel=$rq->telefono;
        $tel_rec=$rq->tel_rec;
        if($tel_rec==''){
            $tel=$tel_tel;
        }else{
            $tel=$tel_rec;
        }
        DB::connection('mysql')->insert("
            insert into gestion_recordatorio(cli_id_FK,ges_tel_id_FK,ges_rec_fec_hor,ges_rec_est,ges_rec_pas)
            values(:id,:tel,:fec,0,0)
        ",array("id"=>$id,"tel"=>$tel,"fec"=>$fechaRec));
    }

    public static function updateEstadoRecordatorio($id){
        DB::connection('mysql')->update("
            update gestion_recordatorio
            set ges_rec_est=1
            where cli_id_FK=:id
        ",array("id"=>$id));
        return "ok";
    }

    public static function listarRecordatorio(){
        $sql="
            SELECT
                * 
            FROM
            (SELECT 
                    cli_id as id,
                    emp_tel_id_FK as idempleado,
                    cli_cod as codigo,
                    cli_nom as nombre,
                    cli_num_doc as dni,
                    det_cli_deu_cap as capital,
                    det_cli_deu_mor as deuda,
                    det_cli_imp_can as importe,
                    det_cli_pro as producto,
                    if(ges_cli_med is null,'-',ges_cli_med) as telefono,
                    if(res_id_FK is null,'Sin Gestión',res_des) as ult_resp,
                    date(ges_cli_fec) as fecha_ges,
                    ges_tel_id_FK as tel_prog,
                    time(ges_rec_fec_hor) as hora_programada,
                    TIME(DATE_ADD(time(ges_rec_fec_hor),INTERVAL +1 MINUTE)) as hora_fin
                FROM 
                        cliente as c
                inner JOIN detalle_cliente dc ON c.cli_id = dc.cli_id_FK
                inner join gestion_recordatorio gr ON gr.cli_id_FK=c.cli_id
                left JOIN gestion_cliente g ON c.ges_cli_tel_id_FK=g.ges_cli_id
                left JOIN respuesta as r on r.res_id=g.res_id_FK
                WHERE 
                        cli_est=0
                and cli_pas=0
                AND ges_rec_est=0 
                AND ges_rec_pas=0
                and date(ges_rec_fec_hor)=date(now())
                and TIME(NOW()) BETWEEN time(ges_rec_fec_hor) AND TIME(DATE_ADD(time(ges_rec_fec_hor),INTERVAL +11 MINUTE))
                GROUP BY emp_tel_id_FK,cli_id
                ORDER BY ges_rec_fec_hor desc
            )t
            GROUP BY idempleado
            limit 5
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }
}
