<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Bandeja extends Model
{
    public static function bandejaCartera(){
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        cli_cod as codigo,
                        cli_nom as cliente,
                        car_nom as cartera,
                        numero_z,
                        fecha
                    FROM
                    (SELECT
                        ban_gsm as numero_z,
                        SUBSTR(ban_gsm, 4, LENGTH(ban_gsm)) AS numero,
                        CONCAT(                                                    
                                SUBSTR(ban_fec_re, 4, 2),
                                '/',
                                LEFT (ban_fec_re, 2),
                                '/',
                                SUBSTR(ban_fec_re, 7, 4)
                        )as fecha
                    FROM
                        creditoy_sms.bandeja b
                    WHERE
                        (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN left(ban_fec_re,10) BETWEEN date_format(ADDDATE(date(NOW()),INTERVAL -2 DAY),'%m/%d/%Y') and date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                                                        ELSE left(ban_fec_re,10)= date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                        END)
                    and ban_gsm like ('+51%')
                    and ban_body not in ('')
                    )t
                    INNER JOIN creditoy_sms.repositorio_sms r ON t.numero=r.rep_sms_gsm
                    INNER JOIN creditoy_cobranzas.cliente c on r.rep_sms_cli_id=c.cli_id
                    INNER JOIN creditoy_cobranzas.cartera ca on c.car_id_FK=ca.car_id
                    WHERE
                        cli_est=0
                    and cli_pas=0
                    and ca.car_id in (SELECt car_id_FK from creditoy_lotesms.empleado WHERE emp_est=0 and usu_FK=:usu)
                    and (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN fecha BETWEEN date_format(ADDDATE(date(NOW()),INTERVAL -2 DAY),'%d/%m/%Y') and date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%d/%m/%Y')
                            ELSE date(rep_sms_fec)= ADDDATE(date(NOW()),INTERVAL -1 DAY)
                        END)
                    and car_est=0
                    and car_pas=0
                    GROUP BY numero
                                    
        "),array("usu"=>auth()->user()->emp_cod));
    }

    public static function chat($numero){
        $numero_sincod=substr($numero,3,11);
        
        return DB::connection('mysql')->select(DB::raw("
                    SELECT 
                        msj,
                        hora,
                        estado
                    FROM(
                        (SELECT
                            ban_body as msj,
                            RIGHT(ban_fec_re,8) as hora,
                            1 as estado
                        FROM
                            creditoy_sms.bandeja 
                        WHERE ban_body not in ('')
                        and ban_gsm in (:numb)
                        and (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN left(ban_fec_re,10) BETWEEN date_format(ADDDATE(date(NOW()),INTERVAL -2 DAY),'%m/%d/%Y') and date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                                        ELSE left(ban_fec_re,10)= date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                            END)
                        GROUP BY ban_body
                        )
                        UNION
                        (
                        SELECT 
                            rep_sms_det as msj,
                            time(rep_sms_fec) as hora,
                            0 as estado
                        FROM
                        creditoy_sms.repositorio_sms r FORCE INDEX(in_gsm) 
                        WHERE
                        rep_sms_gsm in (:num)
                        and (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN date(rep_sms_fec) BETWEEN ADDDATE(date(NOW()),INTERVAL -2 DAY) and ADDDATE(date(NOW()),INTERVAL -1 DAY)
                                        ELSE date(rep_sms_fec)= ADDDATE(date(NOW()),INTERVAL -1 DAY)
                            END)
                        and rep_sms_pas=1
                        ORDER BY time(rep_sms_fec) asc
                        )
                    )t
                    ORDER BY hora asc
        "),array("num"=>$numero_sincod,"numb"=>$numero));
    }

    public static function bandejaNoIdentificados(){
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        numero_z,
                        fecha
                    FROM
                    (SELECT
                        numero_z,
                        fecha,
                        rep_sms_gsm
                    FROM
                    (SELECT
                        ban_gsm as numero_z,
                        SUBSTR(ban_gsm, 4, LENGTH(ban_gsm)) AS numero,
                        CONCAT(                                                    
                                SUBSTR(ban_fec_re, 4, 2),
                                '/',
                                LEFT (ban_fec_re, 2),
                                '/',
                                SUBSTR(ban_fec_re, 7, 4)
                        )as fecha
                    FROM
                        creditoy_sms.bandeja b
                    WHERE
                        (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN left(ban_fec_re,10) BETWEEN date_format(ADDDATE(date(NOW()),INTERVAL -2 DAY),'%m/%d/%Y') and date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                            ELSE left(ban_fec_re,10)= date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%m/%d/%Y')
                        END)
                    and ban_gsm like ('+51%')
                    and ban_body not in ('')
                    )t
                    left JOIN creditoy_sms.repositorio_sms r ON t.numero=r.rep_sms_gsm
                    and (CASE WHEN DAYNAME(date(NOW()))='Monday' THEN fecha BETWEEN date_format(ADDDATE(date(NOW()),INTERVAL -2 DAY),'%d/%m/%Y') and date_format(ADDDATE(date(NOW()),INTERVAL -1 DAY),'%d/%m/%Y')
                            ELSE date(rep_sms_fec)= ADDDATE(date(NOW()),INTERVAL -1 DAY)
                        END)
                    GROUP BY numero
                    )tt
                    where rep_sms_gsm is null
        "));
    }
}
