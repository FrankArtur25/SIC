<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Pago extends Model
{
    public static function infoPagos($id){
            $sql="
                select 
                    pag_cli_pro as cuenta,
                    format(pag_cli_mon,2) as pagos,
                    pag_cli_fec as fecha
                from 
                    pago_cliente_2 as p
                inner join 
                    cliente as c on p.pag_cli_cod=c.cli_cod
                where 
                    cli_est=0 and cli_pas=0
                    AND cli_id=:id
                order by pag_cli_fec desc
            ";
   
        $query=DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
        return $query;
    }

    public static function deletePagos($cartera,$mes){
        DB::connection('mysql')->delete("            
            DELETE FROM pago_cliente_2
            WHERE car_id_FK=:car
            and date_format(pag_cli_fec,'%Y%m')=:mes
        ",array("car"=>$cartera,"mes"=>$mes));
    }

    public static function updateCodigoPago($cartera,$mes){
        DB::connection('mysql')->update("
            update 
                pago_cliente_2 p
            INNER JOIN detalle_cliente dc ON p.pag_cli_pro=dc.det_cli_num_doc
            INNER JOIN cliente c ON dc.cli_id_FK=c.cli_id and c.car_id_FK=:car
            set pag_cli_cod=cli_cod
            WHERE 
            p.car_id_FK=:car2
            and date_format(pag_cli_fec,'%Y%m')=:mes
        ",array("car"=>$cartera,"car2"=>$cartera,"mes"=>$mes));
        return "ok";
    }

    public static function updateProductoPago($cartera,$mes){
        DB::connection('mysql')->update("
            update 
                pago_cliente_2 p
            INNER JOIN cliente c ON p.pag_cli_cod=c.cli_cod and c.car_id_FK=:car
            INNER JOIN detalle_cliente dc ON c.cli_id=dc.cli_id_FK
            set pag_cli_pro=det_cli_num_doc
            WHERE 
            p.car_id_FK=:car2
            and date_format(pag_cli_fec,'%Y%m')=:mes
        ",array("car"=>$cartera,"car2"=>$cartera,"mes"=>$mes));
        return "ok";
    }
    

}
