<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Indicador extends Model
{
   public static function asignacion(){
       return DB::connection('mysql')->select(DB::raw("
                SELECT
                    cal_nom as id,
                    cal_nom as valor
                FROM
                    call_telefonica
                WHERE
                    cal_est=0
                and cal_id in (1,2,4)
       "));
   }

    public static function estructuras(Request $rq){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $sql="
            select 
                valor as id,
                valor
            FROM
            (SELECT 
                case when :estr1='tramo' then tramo
                     when :estr2='dep' then dep
                     when :estr3='dep_ind' then dep_ind
                     when :estr4='prioridad' then prioridad
                end as valor
            FROM indicadores.cartera_detalle
            WHERE car_id_fk=:car
            and DATE_FORMAT(fecha,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m')
            )t
            GROUP BY valor
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),
                array("car"=>$cartera,"estr1"=>$estructura,"estr2"=>$estructura,
                      "estr3"=>$estructura,"estr4"=>$estructura
            ));
        return $query;
    }

    public static function cobertura(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym');
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        date_format(fec,'%m-%Y') as mes,
                        day(fec) as dia,
                        SUM(cliente) as cantidad
                    FROM
                    (SELECT
                        cli_id,
                        fec,
                        gestiones,
                        IF (
                                (
                                        SELECT
                                                count(*)
                                        FROM
                                                gestion_cliente
                                        WHERE
                                        DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                                        AND date(ges_cli_fec)<fec
                                        AND cli_id_FK=cli_id
                                ) = 0,
                                1,
                                0
                        ) AS cliente
                    FROM
                        (SELECT
                            cli_id,
                            date(ges_cli_fec) AS fec,
                            count(cuenta) AS gestiones
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                            c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            $sql
                        GROUP BY cuenta,date(ges_cli_fec)
                    )t
                    ORDER BY fec
                )tt
                GROUP BY fec
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha));
        
    }

    public static function totales($rq){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $sql1="";
        $sql2="";
        
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql1.=" and nuevo='NUEVO' ";
                $sql2.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                        $sql1.=" and cal_nom in ('$asignacion') ";
                        $sql2.=" and call_pertenece in ('$asignacion') ";
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql1 .=" and $estructura <500 " ;
                        $sql2 .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql1 .=" and $estructura >= 500 and $estructura < 1000 " ;
                        $sql2 .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql1 .=" and $estructura >= 1000 and $estructura < 3000 " ;
                        $sql2 .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql1 .=" and $estructura >= 3000 " ;
                        $sql2 .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql1.=" and $estructura='$valorEstructura' ";
                    $sql2.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql1.="";
                $sql2.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                 SELECT 
                    mes,
                    total
                 FROM(   
                     (SELECT
                        fecha,
                        month(fecha) as mes,
                        count(cuenta) as total
                    FROM
                        indicadores.cartera_detalle i
                    INNER JOIN cliente c ON c.cli_cod = i.cuenta
                    left JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                    left JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id
                    WHERE
                        cli_est=0 and cli_pas=0
                    and c.car_id_fk=:car1
                    and (date_format(fecha,'%Y%m') = DATE_FORMAT(NOW(),'%Y%m'))
                    and i.car_id_fk=:car2
                    $sql1
                    GROUP BY month(fecha)
                    order by month(fecha)
                    )
                    union all
                    (SELECT
                        fecha,
                        month(fecha) as mes,
                        count(cuenta) as total
                    FROM
                        indicadores.cartera_detalle t
                    WHERE
                        (date_format(fecha,'%Y%m') BETWEEN DATE_FORMAT(ADDDATE(NOW(),INTERVAL -2 MONTH),'%Y%m') and DATE_FORMAT(ADDDATE(NOW(),INTERVAL -1 MONTH),'%Y%m'))
                    and t.car_id_fk=:car3
                    $sql2
                    GROUP BY month(fecha)
                    order by month(fecha)
                    )
                )t
                order by fecha desc
        "),array("car1"=>$cartera,"car2"=>$cartera,"car3"=>$cartera));        
    }

    public static function contactabilidad(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        date_format(fec,'%m-%Y') as mes,
                        day(fec) as dia,
                        SUM(cliente) as cantidad
                    FROM
                    (SELECT
                        cli_id,
                        fec,
                        IF (
                            (
                                SELECT
                                        count(*)
                                FROM
                                        gestion_cliente sg
                                WHERE
                                DATE_FORMAT(sg.ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                                AND date(sg.ges_cli_fec)<fec
                                AND sg.cli_id_FK=cli_id
                                and sg.res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7,34,17,21)
                            ) = 0,
                            1,
                            0
                        ) AS cliente
                    FROM
                        (SELECT
                            cli_id,
                            date(ges_cli_fec) AS fec,
                            (SELECT
                                    max(ges_cli_id) AS maxid
                                FROM
                                    gestion_cliente
                                WHERE
                                    date(ges_cli_fec)<=DATE(g.ges_cli_fec)
                                and cli_id_FK=cli_id
                            ) AS idmax
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            and res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7,34,17,21)
                             -- and res_id_FK in (select res_id from respuesta where res_ubi=0 and res_est=0 and res_pas=0)
                            $sql
                        GROUP BY cuenta,date(ges_cli_fec)
                    )t
                    INNER JOIN gestion_cliente ult_ges ON t.idmax=ult_ges.ges_cli_id
                    WHERE ult_ges.res_id_FK not in (19,27,12,13,26)
                    ORDER BY fec
                )tt
                GROUP BY fec
                ORDER BY fec
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha));
    }

    public static function contactabilidadEfectiva(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        date_format(fec,'%m-%Y') as mes,
                        day(fec) as dia,
                        SUM(cliente) as cantidad
                    FROM
                    (SELECT
                        cli_id,
                        fec,
                        IF (
                            (
                                SELECT
                                        count(*)
                                FROM
                                        gestion_cliente sg
                                WHERE
                                DATE_FORMAT(sg.ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                                AND date(sg.ges_cli_fec)<fec
                                AND sg.cli_id_FK=cli_id
                                and sg.res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7)
                            ) = 0,
                            1,
                            0
                        ) AS cliente
                    FROM
                        (SELECT
                            cli_id,
                            date(ges_cli_fec) AS fec,
                            (SELECT
                                    max(ges_cli_id) AS maxid
                                FROM
                                    gestion_cliente
                                WHERE
                                    date(ges_cli_fec)<=DATE(g.ges_cli_fec)
                                and cli_id_FK=cli_id
                            ) AS idmax
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            and res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7)
                             -- and res_id_FK in (select res_id from respuesta where res_ubi=0 and res_est=0 and res_pas=0)
                            $sql
                        GROUP BY cuenta,date(ges_cli_fec)
                    )t
                    INNER JOIN gestion_cliente ult_ges ON t.idmax=ult_ges.ges_cli_id
                    WHERE ult_ges.res_id_FK not in (19,27,12,13,26,34,17,21)
                    ORDER BY fec
                )tt
                GROUP BY fec
                ORDER BY fec
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha));
    }

    public static function intensidad(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                        select
                            date_format(ges_cli_fec,'%m-%Y') as mes,
                            day(ges_cli_fec) as dia,
                            count(ic.car_id_fk) AS cantidad
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            $sql
                        GROUP BY date(ges_cli_fec)
                        ORDER BY dia asc
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha));
    }

    public static function intensidadDirecta(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                        select
                            date_format(ges_cli_fec,'%m-%Y') as mes,
                            day(ges_cli_fec) as dia,
                            count(ic.car_id_fk) AS cantidad
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            and res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7,34,17,21)
                            $sql
                        GROUP BY date(ges_cli_fec)
                        ORDER BY dia asc
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha));
    }
    
    public static function totalesCEF(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";
        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        date_format(fec,'%m-%Y') as mes,
                        day(fec) as dia,
                        SUM(cliente) as total
                    FROM
                    (SELECT
                        cli_id,
                        fec,
                        IF (
                            (
                                SELECT
                                        count(*)
                                FROM
                                        gestion_cliente sg
                                WHERE
                                DATE_FORMAT(sg.ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                                AND date(sg.ges_cli_fec)<fec
                                AND sg.cli_id_FK=cli_id
                                and sg.res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7)
                            ) = 0,
                            1,
                            0
                        ) AS cliente
                    FROM
                        (SELECT
                            cli_id,
                            date(ges_cli_fec) AS fec,
                            (SELECT
                                    max(ges_cli_id) AS maxid
                                FROM
                                    gestion_cliente
                                WHERE
                                    date(ges_cli_fec)<=DATE(g.ges_cli_fec)
                                and cli_id_FK=cli_id
                            ) AS idmax
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            and res_id_FK in (38,6,22,41,2,37,33,10,1,8,43,39,7)
                             -- and res_id_FK in (select res_id from respuesta where res_ubi=0 and res_est=0 and res_pas=0)
                            $sql
                        GROUP BY cuenta,date(ges_cli_fec)
                    )t
                    INNER JOIN gestion_cliente ult_ges ON t.idmax=ult_ges.ges_cli_id
                    WHERE ult_ges.res_id_FK not in (19,27,12,13,26,34,17,21)
                    ORDER BY fec
                )tt
                GROUP BY fec
                ORDER BY fec
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha));
    }

    public static function tasaCierre(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";

        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                        select
                            date_format(ges_cli_fec,'%m-%Y') as mes,
                            day(ges_cli_fec) as dia,
                            count(ic.car_id_fk) AS cantidad
                        FROM
                            gestion_cliente g
                        INNER JOIN cliente c ON c.cli_id = g.cli_id_FK
                        INNER JOIN indicadores.cartera_detalle ic ON c.cli_cod = ic.cuenta
                        $sqlInner
                        WHERE
                                c.car_id_fk=:car1
                            AND ic.car_id_FK=:car2
                            and ges_cli_acc in (1,2)
                            and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                            and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                            and res_id_FK in (1)
                            $sql
                        GROUP BY date(ges_cli_fec)
                        ORDER BY dia asc
            "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha));
    }

    public static function montoPromesas(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";

        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    select 
                        date(ges_cli_com_fec) as fecha,
                        day(ges_cli_com_fec) as dia,
                        sum(ges_cli_com_can) total
                    from
                    (select                  
                        ges_cli_com_can,
                        ges_cli_com_fec
                    from cliente c
                    INNER JOIN gestion_cliente ge ON ge.cli_id_FK = c.cli_id
                    INNER JOIN indicadores.cartera_detalle cd ON c.cli_cod= cd.cuenta
                    $sqlInner
                    WHERE 
                        c.car_id_fk = :car1
                    and cd.car_id_fk = :car2
                    and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                    and DATE_FORMAT(ges_cli_com_fec,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                    and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                    and res_id_fk in (1,43)    
                    $sql
                ) a
                GROUP BY fecha
                ORDER BY fecha
        "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha));
    }
     
    public static function montoPagos(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $asignacion=$rq->asignacion;
        $valorEstructura=$rq->valorEstructura;
        $actual = Carbon::now();
        $mesActual = $actual->format('Ym');
        $mesParametro=$fecha->format('Ym')  ;
        $sql="";
        $sqlInner="";

        if($asignacion!=''){
            if($asignacion=="0"){
                $sql.=" and nuevo='NUEVO' ";
            }else{
                if($asignacion!="-1"){
                    if($mesActual==$mesParametro){
                        $sql.=" and cal_nom in ('$asignacion') ";
                        $sqlInner=" INNER JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                        INNER JOIN call_telefonica as cal on e.cal_id_FK=cal.cal_id ";
                    }else{
                        $sql.=" and call_pertenece in ('$asignacion') ";
                    }
                }
            }
        }
        
        if($estructura!=''){
            if($valorEstructura!=''){
                if($estructura=="saldo_deuda" || $estructura=="capital" || $estructura=="monto_camp"){
                    if($valorEstructura=='1'){
                        $sql .=" and $estructura <500 " ;
                    }else if($valorEstructura=='2'){
                        $sql .=" and $estructura >= 500 and $estructura < 1000 " ;
                    }else if($valorEstructura=='3'){
                        $sql .=" and $estructura >= 1000 and $estructura < 3000 " ;
                    }else if($valorEstructura=='4'){
                        $sql .=" and $estructura >= 3000 " ;
                    }
                }else{
                    $sql.=" and $estructura='$valorEstructura' ";
                }
            }else{
                $sql.="";
            }
        }

        return DB::connection('mysql')->select(DB::raw("
                    select 
                        day(fecha) as dia,
                        sum(pag_cli_mon) as cantidad
                    from
                    (select                 
                            cli_cod,
                            ges_cli_com_fec as fecha,
                            cd.car_id_FK
                            ges_cli_fec,
                            ges_cli_com_can,
                            pag_cli_mon, 
                            pag_cli_fec,
                            ges_cli_com_fec
                    FROM pago_cliente_2 as p
                    INNER JOIN cliente AS c ON c.cli_cod = p.pag_cli_cod
                    INNER JOIN gestion_cliente AS ge ON ge.cli_id_FK = c.cli_id
                    INNER JOIN indicadores.cartera_detalle AS cd ON p.pag_cli_cod = cd.cuenta
                    $sqlInner
                    WHERE 
                           c.car_id_fk = :car1
                        and cd.car_id_fk = :car2
                        and DATE_FORMAT(ges_cli_fec,'%Y%m') = DATE_FORMAT(:fec1,'%Y%m')
                        and DATE_FORMAT(ges_cli_com_fec,'%Y%m') = DATE_FORMAT(:fec2,'%Y%m')
                        and DATE_FORMAT(fecha,'%Y%m') = DATE_FORMAT(:fec3,'%Y%m')
                        and DATE_FORMAT(pag_cli_fec,'%Y%m') = DATE_FORMAT(:fec4,'%Y%m')
                        and res_id_fk in (1,43)
                        $sql
                        GROUP BY cli_cod,ges_cli_com_fec
                    ) c
                    GROUP BY fecha
                    ORDER BY fecha
        "),array("car1"=>$cartera,"car2"=>$cartera,"fec1"=>$fecha,":fec2"=>$fecha,":fec3"=>$fecha,":fec4"=>$fecha));
        
    }
}

