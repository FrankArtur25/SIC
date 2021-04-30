<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Reporte extends Model
{
    public static function asignacionCall(){
        return DB::connection('mysql')->select(DB::raw("
                 SELECT
                     cal_id as id,
                     cal_nom as valor
                 FROM
                     call_telefonica
                 WHERE
                     cal_est=0
        "));
    }

    public static function reporteGeneralGestiones($cartera,$fecInicio,$fecFin,$perfil){
        $parametros=array();
        $parametros['fecInicio']=$fecInicio;
        $parametros['fecFin']=$fecFin;
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        if($cartera!=0){
            $sql.=" and car_id_FK=:car ";
            $parametros['car']=$cartera;
        }else{
            if($carteras!=0){
                $sql.=" and car_id_FK in ($carteras) ";
            }
        }
        if($perfil!=0){
            $sql.=" and emp_tip_acc=:per ";
            $parametros['per']=$perfil;
        }
        
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        cli_cod as codigo,
                        cli_nom as nombre,
                        car_nom as cartera,
                        ges_cli_fec as fecha,
                        hour(ges_cli_fec) as hora,
                        minute(ges_cli_fec)	as minuto,
                        second(ges_cli_fec)	as segundo,
                        CONCAT(emp_cod,' - ',emp_nom) as gestor,
                        CASE
                            WHEN emp_tip_acc = 1 THEN
                                'ADMINISTRADOR'
                            WHEN emp_tip_acc = 2 THEN
                                'G. TELEFONICO'
                            WHEN emp_tip_acc = 3 THEN
                                'G. DOMICILIARIO'
                            WHEN emp_tip_acc = 4 THEN
                                'DIGITADOR'
                            WHEN emp_tip_acc = 5 THEN
                                'SUPERVISOR'
                            ELSE
                                ''
                        END AS perfil,
                        ges_cli_med as medio,
                        CASE
                            WHEN ges_cli_acc = 1 THEN
                                'LLAMADA RECIBIDA'
                            WHEN ges_cli_acc = 2 THEN
                                'LLAMADA SALIENTE'
                            WHEN ges_cli_acc = 3 THEN
                                'RECIBIR VISITA'
                            WHEN ges_cli_acc = 4 THEN
                                'REALIZAR VISITA'
                            WHEN ges_cli_acc = 5 THEN
                                'LLAMADA RECIBIDA - SMS'
                            WHEN ges_cli_acc = 6 THEN
                                'LLAMADA RECIBIDA - IVR'
                            WHEN ges_cli_acc = 7 THEN
                                'LLAMADA RECIBIDA - EMAIL'
                            ELSE
                                ''
                        END AS accion,
                        ges_cli_fec_visit as fecha_visita,
                        CASE WHEN res_ubi=0 THEN 'CONTACTO' 
                                WHEN res_ubi=1 THEN 'NO CONTACTO' 
                                WHEN res_ubi=2 THEN 'NO DISPONIBLE'
                        END AS ubi,
                        res_des as rpta,
                        mot_res as motivo_no_pago,
                        ges_cli_det as detalle,
                        DATE_FORMAT(ges_cli_com_fec,'%d/%m/%Y') as fecha_compromiso,
                        ges_cli_com_can as monto_compromiso,
                        if(ges_cli_com_can=0,'',if(ges_cli_com_mon=0,'SOLES','DOLARES')) as moneda,
                        ges_cli_conf_fec as fecha_conf,
                        ges_cli_conf_can as monto_conf,
                        cli_dir_dir as direccion,
                        cli_dir_dis as distrito,
                        cli_dir_pro as provincia,
                        cli_dir_dep as departamento
                    FROM
                        cliente c
                    INNER JOIN gestion_cliente g ON c.cli_id=g.cli_id_FK
                    LEFT JOIN empleado e on g.emp_id_FK=e.emp_id
                    INNER JOIN cartera ca on c.car_id_FK=ca.car_id
                    LEFT JOIN cliente_direccion_2 cd ON c.cli_id=cd.cli_id_FK and cli_dir_est=0 AND cli_dir_pas=0
                    INNER JOIN respuesta r on g.res_id_FK=r.res_id
                    LEFT JOIN motivo_nopago m on g.mot_id_FK=m.mot_id
                    WHERE
                        cli_est=0
                    and cli_pas=0
                    and (date(ges_cli_fec) BETWEEN :fecInicio and :fecFin)
                    $sql
                    and car_est=0 
                    and car_pas=0
                    and res_est=0
                    GROUP BY ges_cli_id   
        "),$parametros);
    }

    public static function reporteResumenGestor(Request $rq){
        $cartera=$rq->cartera;
        $call=$rq->call;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $sql="";
        if($call!=''){
            $sql=" and cal_id_FK=$call ";
        }
        $caresp="";
        $carteras=session()->get('datos')->idcartera;
        $sqlc="";
        $sqli="";
        if($cartera!=0){
            $caresp=" where carteras like ('%,".$cartera.",%')";
            $sqlc=" and c.car_id_FK in ($cartera)";
            $sqli=" and i.car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $caresp=" where carteras like ('%,".$carteras.",%')";
                $sqlc=" and c.car_id_FK in ($carteras)";
                $sqli=" and i.car_id_FK in ($carteras)";
            }
        }
        
        return DB::connection('mysql')->select(DB::raw("
                    (SELECT
                        idEmpleado as id,
                        gestor,
                        cartera,
                        'Gestor Telefónico' as perfil,
                        callg,
                        clientes,
                        deuda,
                        gestiones,
                        contactos,
                        negociaciones,
                        nodisponibles,
                        nocontactos,
                        clientes_gestionados,
                        clientes_contacto,
                        pdps,
                        montopdps,
                        nuevos_sin_gestion_mes,
                        sin_gestion_mes,
                        concat(format(((clientes-sin_gestion_mes)/clientes)*100,0),'%') as cobertura
                    FROM
                    (SELECT
                        idEmpleado,
                        cartera,
                        concat(emp_cod,' - ',emp_nom) as gestor,
                        cal_nom as callg,
                        count(cli_cod) as clientes,
                        sum(saldo_deuda) as deuda,
                        sum(cliente_gestionados) as clientes_gestionados,
                        sum(gestiones) as gestiones,
                        sum(cliente_contacto) as clientes_contacto,
                        sum(contactos) as contactos,
                        sum(nocontactos) as nocontactos,
                        sum(nodisponibles) as nodisponibles,
                        sum(negociaciones) AS negociaciones,
                        sum(pdps) as pdps,
                        sum(montopdps) as montopdps,
                        sum(nuevos_sin_gestion) as nuevos_sin_gestion_mes,
                        sum(sin_gestion_mes) as sin_gestion_mes
                        FROM
                            (SELECT 
                                idEmpleado,
                                cli_cod,
                                cartera,
                                saldo_deuda,
                                if(sum(gestion)>0,1,0) as cliente_gestionados,
                                sum(gestion) as gestiones,
                                if(sum(contacto)>0,1,0) as cliente_contacto,
                                sum(contacto) as contactos,
                                sum(nocontacto) as nocontactos,
                                sum(nodisponible) as nodisponibles,
                                sum(negociacion) AS negociaciones,
                                sum(pdp) as pdps,
                                sum(monto_pdp) as montopdps,
                                if(sum(gestion_mes)=0 and nuevo like ('NUEVO%'),1,0) as nuevos_sin_gestion,
                                if(sum(gestion_mes)=0,1,0) as sin_gestion_mes
                            FROM
                                (SELECT
                                    c.emp_tel_id_FK as idEmpleado,
                                    cli_cod,
                                    cli_id,
                                    emp_tel_id_FK as id_ult_ges,
                                    if(g.ges_cli_id is null,0,1) as gestion,
                                    if(gmes.ges_cli_id is null,0,1) as gestion_mes,
                                    if(res_ubi=0,1,0) as contacto,
                                    if(res_ubi=1,1,0) as nocontacto,
                                    if(res_ubi=2,1,0) as nodisponible,
                                    if(g.res_id_FK=33 AND g.mot_id_FK=3,1,0) as negociacion,
                                    if(g.res_id_FK in (1,43),1,0) as pdp,
                                    if(g.res_id_FK in (1,43),g.ges_cli_com_can,0) as monto_pdp
                                FROM	
                                    cliente c 
                                LEFT JOIN gestion_cliente g on c.cli_id=g.cli_id_FK and g.ges_cli_est=0 and (date(g.ges_cli_fec) BETWEEN :fecInicio1 and :fecFin1) and c.emp_tel_id_FK=g.emp_id_FK 
                                LEFT JOIN gestion_cliente gmes on c.cli_id=gmes.cli_id_FK and gmes.ges_cli_est=0 and DATE_FORMAT(gmes.ges_cli_fec,'%Y%m')=DATE_FORMAT(:fecInicio2,'%Y%m')
                                LEFT JOIN respuesta r ON g.res_id_FK=r.res_id and res_est=0
                                WHERE
                                    cli_est=0
                                and cli_pas=0
                                $sqlc
                                GROUP BY cli_cod,g.ges_cli_fec
                            )t
                            INNER JOIN indicadores.cartera_detalle i ON t.cli_cod=i.cuenta
                            WHERE
                                DATE_FORMAT(fecha,'%Y%m')=DATE_FORMAT(:fecInicio3,'%Y%m')
                            $sqli
                            GROUP BY cli_cod
                        )tt
                        INNER JOIN empleado e ON e.emp_id=tt.idEmpleado
                        INNER JOIN call_telefonica ct on e.cal_id_FK=ct.cal_id
                        WHERE
                            emp_est=0
                        and emp_pas=0
                        and emp_tip_acc=2
                        $sql
                        GROUP BY idEmpleado
                        )d 
                    )
                    UNION
                    (
                        SELECT
                            idEmpleado,
                            gestor,
                            cartera,
                            'Supervisor' as perfil,
                            '-' as callg,
                            0 as clientes,
                            0 as deuda,
                            gestiones,
                            contactos,
                            negociaciones,
                            nodisponibles,
                            nocontactos,
                            clientes_gestionados,
                            clientes_contacto,
                            pdps,
                            montopdps,
                            0 as nuevos_sin_gestion_mes,
                            0 as sin_gestion_mes,
                            0 as cobertura
                    FROM
                    (SELECT
                        idEmpleado,
                        cartera,
                        concat(emp_cod,' - ',emp_nom) as gestor,
                        sum(cliente_gestionados) as clientes_gestionados,
                        sum(gestiones) as gestiones,
                        sum(cliente_contacto) as clientes_contacto,
                        sum(contactos) as contactos,
                        sum(nocontactos) as nocontactos,
                        sum(nodisponibles) as nodisponibles,
                        sum(negociaciones) AS negociaciones,
                        sum(pdps) as pdps,
                        sum(montopdps) as montopdps
                    FROM
                    (SELECT 
                        idEmpleado,
                        cartera,
                        cli_cod,
                        if(sum(gestion)>0,1,0) as cliente_gestionados,
                        sum(gestion) as gestiones,
                        if(sum(contacto)>0,1,0) as cliente_contacto,
                        sum(contacto) as contactos,
                        sum(nocontacto) as nocontactos,
                        sum(nodisponible) as nodisponibles,
                        sum(negociacion) AS negociaciones,
                        sum(pdp) as pdps,
                        sum(monto_pdp) as montopdps
                    FROM
                    (SELECT
                        emp_id_FK as idEmpleado,
                        car_nom as cartera,
                        cli_cod,
                        cli_id,
                        if(g.ges_cli_id is null,0,1) as gestion,
                        if(res_ubi=0,1,0) as contacto,
                        if(res_ubi=1,1,0) as nocontacto,
                        if(res_ubi=2,1,0) as nodisponible,
                        if(g.res_id_FK=33 AND g.mot_id_FK=3,1,0) as negociacion,
                        if(g.res_id_FK in (1,43),1,0) as pdp,
                        if(g.res_id_FK in (1,43),g.ges_cli_com_can,0) as monto_pdp
                    FROM	
                        cliente c
                    INNER JOIN gestion_cliente g on c.cli_id=g.cli_id_FK
                    INNER JOIN respuesta r ON g.res_id_FK=r.res_id and res_est=0 
                    INNER JOIN cartera ca on c.car_id_FK=ca.car_id
                    WHERE
                        cli_est=0
                    and cli_pas=0
                    $sqlc
                    and g.ges_cli_est=0 
                    and (date(g.ges_cli_fec) BETWEEN :fecInicio4 and :fecFin4)
                    and emp_id_FK in (select 
                                            emp_id
                                        FROM
                                        (SELECT
                                            emp_id,
                                            CONCAT(',',res_car_id_FK,',') as carteras
                                        FROM
                                            empleado
                                        WHERE
                                            emp_tip_acc = 1
                                            AND emp_est = 0
                                            and res_car_id_FK is not null
                                        )ee
                                        $caresp
                                    )
                    GROUP BY cli_cod,g.ges_cli_fec,emp_id_FK
                    )t
                    GROUP BY idEmpleado
                    )tt
                    INNER JOIN empleado e ON e.emp_id=tt.idEmpleado
                    WHERE
                        emp_est=0
                    and emp_pas=0
                    and emp_tip_acc=1
                    GROUP BY idEmpleado
                    )d 
                )
                
        "),array("fecInicio1"=>$fechaInicio,"fecInicio2"=>$fechaInicio,"fecInicio3"=>$fechaInicio,"fecFin1"=>$fechaFin,
                "fecInicio4"=>$fechaInicio,"fecFin4"=>$fechaFin));
    }

    public static function primerayultimagestion(Request $rq){
        $cartera=$rq->cartera;
        $call=$rq->call;
        $fecha=$rq->fecha;
        $horaInicio=$rq->horaInicio;
        $horaFin=$rq->horaFin;
        $sql="";
        $horasql="";
        if($call!=''){
            $sql=" and cal_id_FK=$call ";
        }
        if($horaInicio!='' && $horaFin!=''){
            $horasql=" and (time(g.ges_cli_fec) BETWEEN '$horaInicio' and '$horaFin')";
        }

        $carteras=session()->get('datos')->idcartera;
        $sql2="";
        if($cartera!=0){
            $sql2=" and c.car_id_FK in ($cartera) and i.car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $sql2=" and c.car_id_FK in ($carteras) and i.car_id_FK in ($carteras)";
            }
        }
        
        return DB::connection('mysql')->select(DB::raw("
                    SELECT 
                        gestor,
                        cartera,
                        perfil,
                        count(cli_cod) as clientes,
                        sum(saldo_deuda) as deuda,
                        callg,
                        min(primerages) as primerages,
                        max(ultges) as ultimages
                    FROM
                        (SELECT
                            CONCAT(emp_cod,' - ',emp_nom) as gestor,
                            cli_cod,
                            cartera,
                            'Gestor Telefónico' as perfil,
                            cal_nom as callg,
                            saldo_deuda,
                            min(time(ges_cli_fec)) as primerages,
                            max(time(ges_cli_fec)) as ultges
                        FROM	
                            cliente c
                        LEFT JOIN gestion_cliente g on c.cli_id=g.cli_id_FK and g.ges_cli_est=0 and date(g.ges_cli_fec)=:fec1 and c.emp_tel_id_FK=g.emp_id_FK $horasql
                        INNER JOIN indicadores.cartera_detalle i ON c.cli_cod=i.cuenta
                        INNER JOIN empleado e ON c.emp_tel_id_FK=e.emp_id
                        LEFT JOIN call_telefonica ct on e.cal_id_FK=ct.cal_id
                        WHERE
                            cli_est=0
                        and cli_pas=0
                        $sql2
                        and date_format(fecha,'%Y%m')=date_format(:fec2,'%Y%m')
                        and emp_tip_acc=2
                        $sql
                        GROUP BY cli_cod
                    )t
                    GROUP BY gestor
        "),array("fec1"=>$fecha,"fec2"=>$fecha));
    }


    public static function cantGestioneHora($cartera){
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        if($cartera!=0){
            $sql=" and car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $sql=" and car_id_FK in ($carteras)";
            }
        }
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        emp_nom as gestor,
                        if(hh1 is null,0,hh1) as hora1,
                        if(hh2 is null,0,hh2) as hora2,
                        if(	hh3 is null,0,	hh3) as hora3, 
                        if(	hh4 is null,0,	hh4) as hora4, 
                        if(	hh5 is null,0,	hh5) as hora5, 
                        if(	hh6 is null,0,	hh6) as hora6, 
                        if(	hh7 is null,0,	hh7) as hora7, 
                        if(	hh8 is null,0,	hh8) as hora8, 
                        if(	hh9 is null,0,	hh9) as hora9, 
                        if(	hh10 is null,0,	hh10) as hora10, 
                        if(	hh11 is null,0,	hh11) as hora11, 
                        if(	hh12 is null,0,	hh12) as hora12, 
                        if(	hh13 is null,0,	hh13) as hora13,
                        if(total is null,0,total) as total
                    FROM
                    sub_empleado s
                    LEFT JOIN (SELECT
                            firma,
                            sum(hor1) as hh1,
                            sum(hor2) as hh2,
                            sum(hor3) as hh3,
                            sum(hor4) as hh4,
                            sum(hor5) as hh5,
                            sum(hor6) as hh6,
                            sum(hor7) as hh7,
                            sum(hor8) as hh8,
                            sum(hor9) as hh9,
                            sum(hor10) as hh10,
                            sum(hor11) as hh11,
                            sum(hor12) as hh12,
                            sum(hor13) as hh13,
                            sum(hor1) +	sum(hor2) +	sum(hor3) +	sum(hor4) +	sum(hor5) +	sum(hor6) +	sum(hor7) +	sum(hor8) +	sum(hor9) +	sum(hor10) +	sum(hor11) +	sum(hor12) +	sum(hor13) +	sum(hor13) as total
                    FROM
                        (SELECT
                            RIGHT(TRIM( TRAILING ' ' FROM  ges_cli_det),3) as firma,
                            if(hour(ges_cli_fec)>=8 and hour(ges_cli_fec)<9,1,0) as hor1,
                            if(hour(ges_cli_fec)>=9 and hour(ges_cli_fec)<10,1,0) as hor2,
                            if(hour(ges_cli_fec)>=10 and hour(ges_cli_fec)<11,1,0) as hor3,
                            if(hour(ges_cli_fec)>=11 and hour(ges_cli_fec)<12,1,0) as hor4,
                            if(hour(ges_cli_fec)>=12 and hour(ges_cli_fec)<13,1,0) as hor5,
                            if(hour(ges_cli_fec)>=13 and hour(ges_cli_fec)<14,1,0) as hor6,
                            if(hour(ges_cli_fec)>=14 and hour(ges_cli_fec)<15,1,0) as hor7,
                            if(hour(ges_cli_fec)>=15 and hour(ges_cli_fec)<16,1,0) as hor8,
                            if(hour(ges_cli_fec)>=16 and hour(ges_cli_fec)<17,1,0) as hor9,
                            if(hour(ges_cli_fec)>=17 and hour(ges_cli_fec)<18,1,0) as hor10,
                            if(hour(ges_cli_fec)>=18 and hour(ges_cli_fec)<19,1,0) as hor11,
                            if(hour(ges_cli_fec)>=19 and hour(ges_cli_fec)<20,1,0) as hor12,
                            if(hour(ges_cli_fec)>=20 and hour(ges_cli_fec)<21,1,0) as hor13,
                            HOUR(ges_cli_fec)
                        FROM
                                gestion_cliente g
                            LEFT JOIN cliente c  on g.cli_id_FK=c.cli_id
                        WHERE 
                                cli_est=0
                            and cli_pas=0
                            $sql
                        and date(ges_cli_fec)=date(NOW())
                        )t 
                    GROUP BY firma
                    )tt ON tt.firma=RIGHT(s.emp_firma,3)
                    WHERE 
                        emp_est=0
                    $sql
                    order by total desc
        "));
    }

    public static function descargarGestionesGestor(Request $rq){
        $opcion=$rq->opcion;
        $cartera=$rq->cartera;
        $id=$rq->id;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $sql="";
        if(strpos($id,",")!=false){
            $id=substr($id,0,strlen($id)-1);
        }
        if($opcion==0 || $opcion==5){
            $sql=" and res_ubi=0 ";
        }
        if($opcion==1){
            $sql=" and res_ubi=1 ";
        }
        if($opcion==2){
            $sql=" and res_ubi=2 ";
        }
        if($opcion==3){
            $sql=" and res_id_FK=33 and mot_id_FK=3 ";
        }
        if($opcion==6){
            $sql=" and res_id_Fk in (1,43) ";
        }
        
        $carteras=session()->get('datos')->idcartera;
        $sqlc="";
        if($cartera!=0){
            $sqlc=" and c.car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $sqlc=" and c.car_id_FK in ($carteras)";
            }
        }
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        car_nom as 'Cartera',
                        cli_cod as 'Código',
                        cli_nom as 'Nombre',
                        (
                            SELECT
                                count(*)
                            FROM
                                gestion_cliente
                            WHERE
                                ges_cli_est = 0
                            AND cli_id_FK = t.cli_id
                            AND emp_id_FK = t.emp_id_FK
                            AND DATE_FORMAT(date(ges_cli_fec), '%Y%m') = DATE_FORMAT(date(:fecInicio2), '%Y%m')
                            AND ges_cli_acc IN (1,2)
                        ) AS 'Cant. de llamadas en el mes',
                        format(sum(det_cli_deu_mor_sol),2) as 'Saldo Moroso Total (S/.)',
                        ges_cli_fec as 'Fecha de Gestión',
                        gestor as 'Usuario / Gestor',
                        perfil AS 'Perfil',
                        ges_cli_med as 'Medio',
                        accion AS 'Acción',
                        ubicabilidad as 'Ubic.',
                        res_des as 'Rspta.',
                        mot_res as 'Motivo No Pago',
                        ges_cli_det as 'Detalle',
                        fecha as 'Fecha',
                        CAST(monto AS SIGNED) as 'Monto',
                        if(ges_cli_com_mon=0,'SOLES','DOLARES') as 'Moneda',
                        cli_dir_dir as 'Dirección',
                        cli_dir_dis as 'Distrito',
                        cli_dir_pro as 'Provincia',
                        cli_dir_dep as 'Departamento'
                    FROM
                    (SELECT
                        emp_id_FK,
                        cli_id,
                        cli_cod,
                        cli_nom,
                        ges_cli_fec,
                        CONCAT(emp_cod,' - ',emp_nom) as gestor,
                        CASE
                            WHEN emp_tip_acc = 1 THEN
                                'ADMINISTRADOR'
                            WHEN emp_tip_acc = 2 THEN
                                'G. TELEFONICO'
                            WHEN emp_tip_acc = 3 THEN
                                'G. DOMICILIARIO'
                            WHEN emp_tip_acc = 4 THEN
                                'DIGITADOR'
                            WHEN emp_tip_acc = 5 THEN
                                'SUPERVISOR'
                            ELSE
                                ''
                        END AS perfil,
                        ges_cli_med,
                        CASE
                            WHEN ges_cli_acc = 1 THEN
                                'LLAMADA RECIBIDA'
                            WHEN ges_cli_acc = 2 THEN
                                'LLAMADA SALIENTE'
                            WHEN ges_cli_acc = 3 THEN
                                'RECIBIR VISITA'
                            WHEN ges_cli_acc = 4 THEN
                                'REALIZAR VISITA'
                            WHEN ges_cli_acc = 5 THEN
                                'LLAMADA RECIBIDA - SMS'
                            WHEN ges_cli_acc = 6 THEN
                                'LLAMADA RECIBIDA - IVR'
                            WHEN ges_cli_acc = 7 THEN
                                'LLAMADA RECIBIDA - EMAIL'
                            ELSE
                                ''
                        END AS accion,
                        CASE WHEN res_ubi=0 THEN 'CONTACTO' 
                                WHEN res_ubi=1 THEN 'NO CONTACTO' 
                                WHEN res_ubi=2 THEN 'NO DISPONIBLE'
                        END AS ubicabilidad,
                        res_des ,
                        mot_res ,
                        ges_cli_det,
                        case when res_id_FK=2 then ges_cli_conf_fec
                                when res_id_FK in (1,43) then ges_cli_com_fec
                        end as fecha,
                        case when res_id_FK=2 then ges_cli_conf_can
                                when res_id_FK in (1,43) then ges_cli_com_can
                        end as monto,
                        ges_cli_com_mon,
                        cli_dir_dir,
                        cli_dir_dis,
                        cli_dir_pro,
                        cli_dir_dep,
                        car_nom
                    FROM
                        cliente c
                    INNER JOIN gestion_cliente g ON c.cli_id=g.cli_id_FK
                    INNER JOIN empleado e on g.emp_id_FK=e.emp_id
                    LEFT JOIN cliente_direccion_2 cd ON c.cli_id=cd.cli_id_FK and cli_dir_est=0 AND cli_dir_pas=0
                    INNER JOIN respuesta r on g.res_id_FK=r.res_id
                    INNER JOIN cartera cc on c.car_id_FK=cc.car_id
                    LEFT JOIN motivo_nopago m on g.mot_id_FK=m.mot_id
                    WHERE
                        cli_est=0
                    and cli_pas=0
                    $sqlc
                    and (date(ges_cli_fec) BETWEEN :fecInicio1 and :fecFin1)
                    and if (emp_tip_acc=2, emp_tel_id_FK=emp_id_FK,emp_tel_id_FK=emp_tel_id_FK)
                    and emp_id in ($id)
                    $sql
                    and res_est=0
                    GROUP BY cli_id,ges_cli_fec
                )t
                INNER JOIN detalle_cliente d on t.cli_id=d.cli_id_FK
                WHERE det_cli_est=0 and det_cli_pas=0
                GROUP BY cli_id,ges_cli_fec
        "),array("fecInicio1"=>$fechaInicio,"fecFin1"=>$fechaFin,"fecInicio2"=>$fechaInicio));
    }

    public static function resumenGestor($cartera){
        $carteras=session()->get('datos')->idcartera;
        $sql1="";$sql2="";
        if($cartera!=0){
            $sql1=" and car_id_FK in ($cartera)";
            $sql2=" and ss.car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $sql1=" and car_id_FK in ($carteras)";
                $sql2=" and ss.car_id_FK in ($carteras)";
            }
        }
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        car_nom as cartera,
                        ss.emp_nom AS gestor,
                        ss.emp_firma as firma,
                        if(gestiones is null,0,gestiones) as gestiones,
                        if(gestiones is null,0,cont) as contacto,
                        if(gestiones is null,0,no_cont) as no_contacto,
                        if(gestiones is null,0,no_disp) as no_disponible
                    FROM
                        sub_empleado ss
                        LEFT JOIN (SELECT
                                if(emp_nom is null,'SIN FIRMA',emp_nom) as emp_nom,
                                emp_firma,
                                sum(gestiones) as gestiones,
                                sum(cont) as cont,
                                sum(no_cont) as no_cont,
                                sum(no_disp) as no_disp
                            FROM
                            (SELECT          	
                                firma,
                                fecha,
                                sum(gestion) as gestiones,
                                sum(contacto) as cont,
                                sum(no_contacto) as no_cont,
                                sum(no_disponible) as no_disp,
                                sum(pdp) as pdps,
                                sum(pago) AS pagos,
                                t.car_id_FK
                            FROM
                            (SELECT
                                date(ges_cli_fec) as fecha,
                                cli_id,
                                RIGHT(TRIM( TRAILING ' ' FROM  gc.ges_cli_det),3) as firma,
                                1 as gestion,
                                if(res_ubi=0,1,0) as contacto,
                                if(res_ubi=1,1,0) as no_contacto,
                                if(res_ubi=2,1,0) as no_disponible,
                                if(res_id in (1,43),ges_cli_com_can,0) as pdp,
                                if(res_id in (2),ges_cli_conf_can,0) as pago,
                                car_id_FK
                            FROM 
                                cliente c
                            INNER JOIN gestion_cliente gc ON gc.cli_id_FK=c.cli_id
                            INNER JOIN respuesta r ON gc.res_id_FK=r.res_id
                            WHERE
                                cli_est=0
                                and cli_pas=0
                                $sql1
                                and DATE(ges_cli_fec) = date(NOW())
                            )t
                            GROUP BY firma
                        )tt 
                        LEFT JOIN sub_empleado s ON tt.firma=s.emp_firma AND emp_est=0 
                        GROUP BY emp_nom
                        ORDER BY gestiones desc 
                    )e ON e.emp_nom=ss.emp_nom
                    LEFT JOIN cartera c ON ss.car_id_FK=c.car_id and car_est=0 and car_pas=0
                    WHERE ss.emp_est=0
                    $sql2
                    ORDER BY gestiones desc
        "));
    }

    public static function resumenGestionesCartera($cartera){
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        if($cartera!=0){
            $sql=" and car_id_FK in ($cartera)";
        }else{
            if($carteras!=0){
                $sql=" and car_id_FK in ($carteras)";
            }
        }
        
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        idcartera,
                        cartera,
                        gestiones,
                        contactos,
                        no_contacto,
                        no_disponible,
                        pdps,
                        monto_pdps,
                        confs,
                        monto_confs
                    FROM
                    (SELECT          	
                            cartera,
                            idcartera,
                            sum(gestion) as gestiones,
                            sum(contacto) as contactos,
                            sum(no_contacto) as no_contacto,
                            sum(no_disponible) as no_disponible,
                            sum(pdp) as pdps,
                            sum(monto_pdp) AS monto_pdps,
                            sum(conf) as confs,
                            sum(monto_conf) AS monto_confs
                        FROM
                        (SELECT
                            car_nom as cartera,
                            car_id as idcartera,
                            cli_id,
                            1 as gestion,
                            if(res_ubi=0,1,0) as contacto,
                            if(res_ubi=1,1,0) as no_contacto,
                            if(res_ubi=2,1,0) as no_disponible,
                            if(res_id in (1,43),1,0) as pdp,
                            if(res_id in (1,43),ges_cli_com_can,0) as monto_pdp,
                            if(res_id in (2),1,0) as conf,
                            if(res_id in (2),ges_cli_conf_can,0) as monto_conf
                        FROM 
                            cliente c
                        INNER JOIN gestion_cliente gc ON gc.cli_id_FK=c.cli_id
                        INNER JOIN respuesta r ON gc.res_id_FK=r.res_id
                        INNER JOIN cartera cc ON c.car_id_FK=cc.car_id
                        WHERE
                            cli_est=0
                            and cli_pas=0
                            $sql
                            and DATE(ges_cli_fec) = date(NOW())
                        )t
                    GROUP BY cartera
                )tt
                ORDER BY gestiones desc
        "));
    }
    
    public static function resumenGestionesCarteraConsolidado($fecha){
        //$sql1="";
        $fec1=date('d', strtotime($fecha));
        $fec2=date('m-d', strtotime($fecha));
        //dd($fec2);

        $data1=DB::connection('mysql')->select(DB::raw("
            select *
            from indicadores.reporte_gestion
            where fecha_registro = '$fecha'
        "));

        if($fec1=='31' || $fec1=='30' || $fec2=='03-29'){
            $data2=DB::connection('mysql')->select(DB::raw("
                select *
                from indicadores.reporte_gestion
                    where fecha_registro = (
                    SELECT MAX(fecha_registro)
                    FROM indicadores.reporte_gestion
                    where MONTH(fecha_registro) = MONTH(date_add('$fecha', INTERVAL -1 MONTH))
                )
            "));
        }else{

            $data2=DB::connection('mysql')->select(DB::raw("
                select *
                from indicadores.reporte_gestion
                where MONTH(fecha_registro) = MONTH(date_add('$fecha', INTERVAL -1 MONTH))
                AND DAY(fecha_registro)=day('$fecha')
            "));
        }

        return response()->json(['data1' => $data1, 'data2' => $data2]);
    }    

    public static function reporteComparativoCarteraPagos(Request $rq){
        $cartera=$rq->cartera;
        $metas=DB::select(DB::raw("
            select month(fecha) as mes, meta,mes_nombre from indicadores.cartera
            where cartera_id_fk=$cartera
            and meta>0
            and date_format(fecha,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(now(),'%Y%m')
        "));

        $pagos=DB::select(DB::raw("
        select month(pag_cli_fec) m, (case
            WHEN month(pag_cli_fec)=1  THEN 'Enero'
            WHEN month(pag_cli_fec)=2 THEN  'Febrero'
            WHEN month(pag_cli_fec)=3 THEN 'Marzo' 
            WHEN month(pag_cli_fec)=4 THEN 'Abril' 
            WHEN month(pag_cli_fec)=5 THEN 'Mayo'
            WHEN month(pag_cli_fec)=6 THEN 'Junio'
            WHEN month(pag_cli_fec)=7 THEN 'Julio'
            WHEN month(pag_cli_fec)=8 THEN 'Agosto'
            WHEN month(pag_cli_fec)=9 THEN 'Septiembre'
            WHEN month(pag_cli_fec)=10 THEN 'Octubre'
            WHEN month(pag_cli_fec)=11 THEN 'Noviembre'
            WHEN month(pag_cli_fec)=12 THEN 'Diciembre'
            END) as mes,
            meta, 
            sum(pag_cli_mon) as recupero
            from pago_cliente_2 as p
            INNER JOIN indicadores.cartera as c on p.car_id_FK=c.cartera_id_fk
            where car_id_FK=$cartera
            and day(pag_cli_fec) < day(now())
            and date_format(pag_cli_fec,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(now(),'%Y%m')
            and date_format(fecha,'%Y-%m')=date_format(pag_cli_fec,'%Y-%m')
            GROUP by month(pag_cli_fec),mes,meta
            order by month(pag_cli_fec)
        "));
        return response()->json(['metas' => $metas, 'pagos' => $pagos]);
    }

    public static function reporteComparativoCarteraCon(Request $rq){
        $cartera=$rq->cartera;

        $metas=DB::select(DB::raw("
            select month(fecha) as mes, meta,mes_nombre from indicadores.cartera
            where cartera_id_fk=$cartera
            and meta>0
            and date_format(fecha,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(now(),'%Y%m')
        "));
        $pagos=DB::select(DB::raw("
            select month(ges_cli_conf_fec) m, (case
                WHEN month(ges_cli_conf_fec)=1  THEN 'Enero'
                WHEN month(ges_cli_conf_fec)=2 THEN  'Febrero'
                WHEN month(ges_cli_conf_fec)=3 THEN 'Marzo' 
                WHEN month(ges_cli_conf_fec)=4 THEN 'Abril' 
                WHEN month(ges_cli_conf_fec)=5 THEN 'Mayo'
                WHEN month(ges_cli_conf_fec)=6 THEN 'Junio'
                WHEN month(ges_cli_conf_fec)=7 THEN 'Julio'
                WHEN month(ges_cli_conf_fec)=8 THEN 'Agosto'
                WHEN month(ges_cli_conf_fec)=9 THEN 'Septiembre'
                WHEN month(ges_cli_conf_fec)=10 THEN 'Octubre'
                WHEN month(ges_cli_conf_fec)=11 THEN 'Noviembre'
                WHEN month(ges_cli_conf_fec)=12 THEN 'Diciembre'
                END) as mes,
                meta, 
                sum(ges_cli_conf_can) as recupero
                from gestion_cliente as g
                INNER JOIN cliente as c on g.cli_id_FK=c.cli_id
                INNER JOIN indicadores.cartera as car on c.car_id_FK=car.cartera_id_fk
                where car_id_FK=$cartera
                and res_id_FK=2
                and day(ges_cli_conf_fec) < day(now())
                and date_format(ges_cli_conf_fec,'%Y%m') BETWEEN date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(now(),'%Y%m')
                and date_format(fecha,'%Y-%m')=date_format(ges_cli_conf_fec,'%Y-%m')
                GROUP by month(ges_cli_conf_fec),mes,meta
                order by month(ges_cli_conf_fec)
        "));
        return response()->json(['metas' => $metas, 'pagos' => $pagos]);
    }

    public static function reporteComparativoCarteraPagosCierre(Request $rq){
        $cartera=$rq->cartera;
        $metas=DB::select(DB::raw("
            select month(fecha) as mes, meta,mes_nombre from indicadores.cartera
            where cartera_id_fk=$cartera
            and meta>0
            and date_format(fecha,'%Y%m') BETWEEN date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(ADDDATE(now(),INTERVAL -1 MONTH),'%Y%m')
        "));

        $pagos=DB::select(DB::raw("
        select month(pag_cli_fec) m, (case
            WHEN month(pag_cli_fec)=1  THEN 'Enero'
            WHEN month(pag_cli_fec)=2 THEN  'Febrero'
            WHEN month(pag_cli_fec)=3 THEN 'Marzo' 
            WHEN month(pag_cli_fec)=4 THEN 'Abril' 
            WHEN month(pag_cli_fec)=5 THEN 'Mayo'
            WHEN month(pag_cli_fec)=6 THEN 'Junio'
            WHEN month(pag_cli_fec)=7 THEN 'Julio'
            WHEN month(pag_cli_fec)=8 THEN 'Agosto'
            WHEN month(pag_cli_fec)=9 THEN 'Septiembre'
            WHEN month(pag_cli_fec)=10 THEN 'Octubre'
            WHEN month(pag_cli_fec)=11 THEN 'Noviembre'
            WHEN month(pag_cli_fec)=12 THEN 'Diciembre'
            END) as mes,
            meta, 
            sum(pag_cli_mon) as recupero
            from pago_cliente_2 as p
            INNER JOIN indicadores.cartera as c
            on p.car_id_FK=c.cartera_id_fk
            where car_id_FK=$cartera
            and date_format(pag_cli_fec,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(ADDDATE(now(),INTERVAL -1 MONTH),'%Y%m')
            and date_format(fecha,'%Y-%m')=date_format(pag_cli_fec,'%Y-%m')
            GROUP by month(pag_cli_fec),mes,meta
            order by month(pag_cli_fec)
        "));
        return response()->json(['metas' => $metas, 'pagos' => $pagos]);
        //dd($results1);
        //return $results1;
    }

    public static function reporteComparativoCarteraConCierre(Request $rq){
        $cartera=$rq->cartera;
        $metas=DB::select(DB::raw("
            select month(fecha) as mes, meta,mes_nombre from indicadores.cartera
            where cartera_id_fk=$cartera
            and meta>0
            and date_format(fecha,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(ADDDATE(now(),INTERVAL -1 MONTH),'%Y%m')
        "));
        $pagos=DB::select(DB::raw("
            select month(ges_cli_conf_fec) m, (case
                WHEN month(ges_cli_conf_fec)=1  THEN 'Enero'
                WHEN month(ges_cli_conf_fec)=2 THEN  'Febrero'
                WHEN month(ges_cli_conf_fec)=3 THEN 'Marzo' 
                WHEN month(ges_cli_conf_fec)=4 THEN 'Abril' 
                WHEN month(ges_cli_conf_fec)=5 THEN 'Mayo'
                WHEN month(ges_cli_conf_fec)=6 THEN 'Junio'
                WHEN month(ges_cli_conf_fec)=7 THEN 'Julio'
                WHEN month(ges_cli_conf_fec)=8 THEN 'Agosto'
                WHEN month(ges_cli_conf_fec)=9 THEN 'Septiembre'
                WHEN month(ges_cli_conf_fec)=10 THEN 'Octubre'
                WHEN month(ges_cli_conf_fec)=11 THEN 'Noviembre'
                WHEN month(ges_cli_conf_fec)=12 THEN 'Diciembre'
                END) as mes,
                meta, 
                sum(ges_cli_conf_can) as recupero
                from gestion_cliente as g
                INNER JOIN cliente as c on g.cli_id_FK=c.cli_id
                INNER JOIN indicadores.cartera as car on c.car_id_FK=car.cartera_id_fk
                where car_id_FK=$cartera
                and res_id_fk=2
                and date_format(ges_cli_conf_fec,'%Y%m') between date_format(ADDDATE(now(),INTERVAL -3 MONTH),'%Y%m') and date_format(ADDDATE(now(),INTERVAL -1 MONTH),'%Y%m')
                and date_format(fecha,'%Y-%m')=date_format(ges_cli_conf_fec,'%Y-%m')
                GROUP by month(ges_cli_conf_fec),mes,meta
                order by month(ges_cli_conf_fec)
        "));
        //dd($results1);
        //return $results1;
        return response()->json(['metas' => $metas, 'pagos' => $pagos]);
    }

    public static function detalleConfirmaciones($cartera){
        $carteras=session()->get('datos')->idcartera;
        $sql="";
        $parametros=array();
        if($cartera!=0){
            $sql=" and car_id_FK in (:car)";
            $parametros["car"]=$cartera;
        }else{
            if($carteras!=0){
                $sql=" and car_id_FK in (:car)";
                $parametros["car"]=$carteras;
            }
        }
        
        return DB::connection('mysql')->select(DB::raw("
            SELECT
                ges_cli_conf_fec as fecha,
                count(ges_cli_conf_can) as cantidad,
                sum(ges_cli_conf_can) as monto
            FROM 
                cliente c
            INNER JOIN gestion_cliente gc ON gc.cli_id_FK=c.cli_id
            WHERE
                cli_est=0
                and cli_pas=0
                and res_id_FK=2
                $sql
                and DATE(ges_cli_fec) = date(NOW())
            GROUP BY ges_cli_conf_fec
            order by ges_cli_conf_fec asc
        "),$parametros);
    }

    public static function reporteEstandarCartera(Request $rq){
        $cartera=$rq->cartera;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    (
                            SELECT
                                    count(s.emp_id)
                            FROM
                                    creditoy_cobranzas.sub_empleado s 
                            INNER JOIN creditoy_cobranzas.empleado ee on s.encargado=ee.emp_cod
                            WHERE
                            s.emp_est=0 and
                            (case when s.car_id_FK=34 and cal_id_FK=1 then  341=ttt.cartera
                                        when s.car_id_FK=34 and cal_id_FK=2 then  342=ttt.cartera
                                        ELSE s.car_id_FK=ttt.cartera
                            END)
                    ) gestores,
                    case when cartera=341 then 'CALL 01'
                                    when cartera=342 then 'CALL 02'
                                    ELSE ''
                    END as cartera,
                    contacto,
                    pdps,
                    conf,
                    estandar_contacto,
                    estandar_pdp,
                    (
                        SELECT
                            if(proyectado_ideal is null,0,proyectado_ideal)
                        FROM
                            creditoy_reporte.proyectado p
                        WHERE
                            p.cartera = idcartera
                        AND p.fecha = date(:fecFin2)
                        and p.dia=day(:fecFin3)
                        AND estado = 0
                        LIMIT 1
                    ) estandar_conf
                    FROM
                    (SELECT
                    cartera,
                    idcartera,
                    if(contacto is null,0,contacto) as contacto,
                    if(pdp is null,0,pdp) as pdps,
                    if(conf is null,0,conf) as conf,
                    car_est_contacto as estandar_contacto,
                    car_est_pdp as estandar_pdp,
                    car_est_conf as estandar_conf
                    -- round(car_est_contacto/11) as estandar_contacto,
                    -- round(car_est_pdp/11) as estandar_pdp,
                    -- round(car_est_conf/11) as estandar_conf,
                    -- left(TIMEDIFF('2020-12-07 19:00:00','2020-12-07 08:00:00'),2) as horas
                    FROM
                    (SELECT
                        (case when idcartera=34 and idcall=1 THEN 341
                            when idcartera=34 and idcall=2 THEN 342
                            ELSE idcartera
                        END )as cartera,	
                        idcartera,
                        count(ges_cli_id) as contacto,
                        sum(ges_cli_com_can) as pdp,
                        sum(ges_cli_conf_can) as conf
                    FROM
                    (
                        SELECT 
                                car_id_FK as idcartera,	
                                if(car_id_FK=34,cal_id_FK,'') as idcall,
                                ges_cli_id,
                                ges_cli_com_can,
                                ges_cli_conf_can,
                                RIGHT(TRIM( TRAILING ' ' FROM  replace(ges_cli_det,'\n','')),3) as firma
                        FROM
                                creditoy_cobranzas.cliente c
                        INNER JOIN creditoy_cobranzas.gestion_cliente g on c.cli_id=g.cli_id_FK
                        INNER JOIN creditoy_cobranzas.empleado e ON g.emp_id_FK=e.emp_id
                        INNER JOIN creditoy_cobranzas.respuesta r on g.res_id_FK=r.res_id
                        WHERE 
                            car_id_FK=:car
                        and ges_cli_fec BETWEEN :fecInicio and :fecFin
                        and res_ubi=0
                        and emp_tip_acc=2
                    )tt
                    INNER JOIN creditoy_cobranzas.sub_empleado s on s.emp_firma like concat('%',tt.firma) and s.car_id_FK=tt.idcartera and s.emp_est=0
                    GROUP BY idcartera,idcall
                    )t 
                    LEFT JOIN creditoy_reporte.cartera_estandar ec on ec.car_id_FK=t.cartera
                    )ttt
       "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin,"fecFin2"=>$fechaFin,"fecFin3"=>$fechaFin));
    }


    // reportes sistemas-------------------------------------------------------------------------------------------------
    public static function generarTablaConfirmaciones(Request $rq){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $idcalls=$rq->calls;
        $calls=explode(",",$rq->calls);
        $tipoFecha=$rq->tipoFecha;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $columnas=explode(",",$rq->columnas);
        
        $sqlTipoFecha="";
        if($tipoFecha==1){
            $sqlTipoFecha="ges_cli_conf_fec";
        }
        if($tipoFecha==2){
            $sqlTipoFecha="ges_cli_fec";
        }

        $sqlEstructura="";
        $sqlEstructuraCant="";
        $sqlEstructuraMonto="";
        $sqlEstructuraSuma="";

        if($estructura==2){
            if($calls!=''){
                for($i=0;$i<count($calls);$i++){
                    $sqlEstructura.=" if(cal_id=".$calls[$i].",1,0) as cant$i, if(cal_id=".$calls[$i].",ges_cli_conf_can,0) as monto$i,";
                    if($columnas!=''){
                        for($j=0;$j<count($columnas);$j++){
                            if($columnas[$j]==2){
                                $sqlEstructuraSuma.=" sum(cant$i) as cant$i,";
                            }
                            if($columnas[$j]==3){
                                $sqlEstructuraSuma.=" sum(monto$i) as monto$i,";
                            }
                            
                        }
                    }
                }
            }
        }
        
        $sqlColumnas="";
        if($columnas!=''){
            if($estructura==2){
                $sqlColumnas.=$sqlEstructuraSuma;
            }
            for($i=0;$i<count($columnas);$i++){
                if($columnas[$i]==2){
                    $sqlColumnas.="count(*) as cantTotal, ";
                }
                if($columnas[$i]==3){
                    $sqlColumnas.="sum(ges_cli_conf_can) as montoTotal, ";
                }
            }
        }

        $sqlCall="";
        if($idcalls!="0"){
            $sqlCall=" and e.cal_id_FK in ($idcalls) ";
        }

        $sqlGroup="";
        $sqlSelect="";
        $sqlCartera="";
        if($cartera=="0"){
            $sqlGroup=" cartera ";
            $sqlSelect=" cartera, ";
            $sqlCartera=" and car_id_FK not in (:car) ";
        }else{
            $sqlGroup=" fecha ";
            $sqlSelect=" if(fecha is null,'TOTAL',fecha) as fecha, ";
            $sqlCartera=" and car_id_FK in (:car) ";
        }


        return DB::select(DB::raw("
            SELECT
                *
            FROM(
                SELECT 
                    $sqlSelect
                    $sqlColumnas
                    ''
                FROM
                    (SELECT
                        car_nom as cartera,
                        date($sqlTipoFecha) as fecha,
                        $sqlEstructura
                        ges_cli_conf_can
                    FROM
                    gestion_cliente g
                    INNER JOIN cliente c on c.cli_id=g.cli_id_FK
                    INNER JOIN cartera a on c.car_id_FK=a.car_id and car_est=0
                    LEFT JOIN empleado e on c.emp_tel_id_FK=e.emp_id 
                    LEFT JOIN call_telefonica l on e.cal_id_FK=l.cal_id
                    WHERE
                        date($sqlTipoFecha) BETWEEN :fecInicio and :fecFin
                        $sqlCartera
                        and res_id_FK=2
                        $sqlCall
                )t
                GROUP BY $sqlGroup WITH ROLLUP
            )tt
            
      "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));  
    }

    public static function reporteDataConfirmaciones(Request $rq){
        $cartera=$rq->cartera;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $tipoFecha=$rq->tipoFecha;
        $idcalls=$rq->calls;
        $sqlTipoFecha="";
        if($tipoFecha==1){
            $sqlTipoFecha="date(ges_cli_conf_fec)";
        }
        if($tipoFecha==2){
            $sqlTipoFecha="date(ges_cli_fec)";
        }
        $sqlCall="";
        if($idcalls!="0"){
            $sqlCall=" and ee.cal_id_FK in ($idcalls) ";
        }

        $sqlCartera="";
        if($cartera=="0"){
            $sqlCartera=" and car_id_FK <> :car  ";
        }else{
            $sqlCartera=" and car_id_FK=:car  ";
        }

        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        cli_cod as codigo,
                        cal_nom as calls,
                        cli_nom as nombre,
                        car_nom as cartera,
                        ges_cli_fec as fecha,
                        hour(ges_cli_fec) as hora,
                        minute(ges_cli_fec)	as minuto,
                        second(ges_cli_fec)	as segundo,
                        CONCAT(e.emp_cod,' - ',e.emp_nom) as gestor,
                        CASE
                            WHEN e.emp_tip_acc = 1 THEN
                                'ADMINISTRADOR'
                            WHEN e.emp_tip_acc = 2 THEN
                                'G. TELEFONICO'
                            WHEN e.emp_tip_acc = 3 THEN
                                'G. DOMICILIARIO'
                            WHEN e.emp_tip_acc = 4 THEN
                                'DIGITADOR'
                            WHEN e.emp_tip_acc = 5 THEN
                                'SUPERVISOR'
                            ELSE
                                ''
                        END AS perfil,
                        ges_cli_med as medio,
                        CASE
                            WHEN ges_cli_acc = 1 THEN
                                'LLAMADA RECIBIDA'
                            WHEN ges_cli_acc = 2 THEN
                                'LLAMADA SALIENTE'
                            WHEN ges_cli_acc = 3 THEN
                                'RECIBIR VISITA'
                            WHEN ges_cli_acc = 4 THEN
                                'REALIZAR VISITA'
                            WHEN ges_cli_acc = 5 THEN
                                'LLAMADA RECIBIDA - SMS'
                            WHEN ges_cli_acc = 6 THEN
                                'LLAMADA RECIBIDA - IVR'
                            WHEN ges_cli_acc = 7 THEN
                                'LLAMADA RECIBIDA - EMAIL'
                            ELSE
                                ''
                        END AS accion,
                        ges_cli_fec_visit as fecha_visita,
                        CASE WHEN res_ubi=0 THEN 'CONTACTO' 
                                WHEN res_ubi=1 THEN 'NO CONTACTO' 
                                WHEN res_ubi=2 THEN 'NO DISPONIBLE'
                        END AS ubi,
                        res_des as rpta,
                        mot_res as motivo_no_pago,
                        ges_cli_det as detalle,
                        DATE_FORMAT(ges_cli_com_fec,'%d/%m/%Y') as fecha_compromiso,
                        ges_cli_com_can as monto_compromiso,
                        if(ges_cli_com_can=0,'',if(ges_cli_com_mon=0,'SOLES','DOLARES')) as moneda,
                        ges_cli_conf_fec as fecha_conf,
                        ges_cli_conf_can as monto_conf,
                        cli_dir_dir as direccion,
                        cli_dir_dis as distrito,
                        cli_dir_pro as provincia,
                        cli_dir_dep as departamento
                    FROM
                        cliente c
                    INNER JOIN gestion_cliente g ON c.cli_id=g.cli_id_FK
                    LEFT JOIN empleado e on g.emp_id_FK=e.emp_id
                    INNER JOIN cartera ca on c.car_id_FK=ca.car_id
                    LEFT JOIN cliente_direccion_2 cd ON c.cli_id=cd.cli_id_FK and cli_dir_est=0 AND cli_dir_pas=0
                    INNER JOIN respuesta r on g.res_id_FK=r.res_id
                    LEFT JOIN motivo_nopago m on g.mot_id_FK=m.mot_id
                    LEFT JOIN empleado ee on c.emp_tel_id_FK=ee.emp_id
                    LEFT JOIN call_telefonica l on ee.cal_id_FK=l.cal_id
                    WHERE
                        ($sqlTipoFecha BETWEEN :fecInicio and :fecFin)
                    $sqlCartera
                    and res_id_FK=2
                    $sqlCall
                    GROUP BY ges_cli_id   
        "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
    }

    public static function generarTablaPdps(Request $rq){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        $idcalls=$rq->calls;
        $calls=explode(",",$rq->calls);
        $tipoFecha=$rq->tipoFecha;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $columnas=explode(",",$rq->columnas);
        
        $sqlTipoFecha="";
        if($tipoFecha==1){
            $sqlTipoFecha="ges_cli_com_fec";
        }
        if($tipoFecha==2){
            $sqlTipoFecha="ges_cli_fec";
        }

        $sqlEstructura="";
        $sqlEstructuraCant="";
        $sqlEstructuraMonto="";
        $sqlEstructuraSuma="";

        if($estructura==2){
            if($calls!=''){
                for($i=0;$i<count($calls);$i++){
                    $sqlEstructura.=" if(cal_id=".$calls[$i].",1,0) as cant$i, if(cal_id=".$calls[$i].",ges_cli_com_can,0) as monto$i,";
                    if($columnas!=''){
                        for($j=0;$j<count($columnas);$j++){
                            if($columnas[$j]==2){
                                $sqlEstructuraSuma.=" sum(cant$i) as cant$i,";
                            }
                            if($columnas[$j]==3){
                                $sqlEstructuraSuma.=" sum(monto$i) as monto$i,";
                            }
                            
                        }
                    }
                }
            }
        }

        $sqlColumnas="";
        if($columnas!=''){
            if($estructura==2){
                $sqlColumnas.=$sqlEstructuraSuma;
            }
            for($i=0;$i<count($columnas);$i++){
                if($columnas[$i]==2){
                    $sqlColumnas.="count(*) as cantTotal, ";
                }
                if($columnas[$i]==3){
                    $sqlColumnas.="sum(ges_cli_com_can) as montoTotal, ";
                }
            }
        }

        $sqlCall="";
        if($idcalls!="0"){
            $sqlCall=" and e.cal_id_FK in ($idcalls) ";
        }

        $sqlGroup="";
        $sqlSelect="";
        $sqlCartera="";
        if($cartera=="0"){
            $sqlGroup=" cartera ";
            $sqlSelect=" cartera, ";
            $sqlCartera=" and car_id_FK not in (:car) ";
        }else{
            $sqlGroup=" fecha ";
            $sqlSelect=" if(fecha is null,'TOTAL',fecha) as fecha, ";
            $sqlCartera=" and car_id_FK in (:car) ";
        }

        return DB::select(DB::raw("
            SELECT
                *
            FROM(
                SELECT 
                    $sqlSelect
                    $sqlColumnas
                    ''
                FROM
                    (SELECT
                        car_nom as cartera,
                        date($sqlTipoFecha) as fecha,
                        $sqlEstructura
                        ges_cli_com_can
                    FROM
                    gestion_cliente g
                    INNER JOIN cliente c on c.cli_id=g.cli_id_FK
                    INNER JOIN cartera a on c.car_id_FK=a.car_id
                    LEFT JOIN empleado e on c.emp_tel_id_FK=e.emp_id 
                    LEFT JOIN call_telefonica l on e.cal_id_FK=l.cal_id
                    WHERE
                        date($sqlTipoFecha) BETWEEN :fecInicio and :fecFin
                        $sqlCartera
                        and res_id_FK in (1,43)
                        $sqlCall
                )t
                GROUP BY $sqlGroup WITH ROLLUP
            )tt
            
      "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));  
    }

    public static function reporteDataPdps(Request $rq){
        $cartera=$rq->cartera;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $tipoFecha=$rq->tipoFecha;
        $idcalls=$rq->calls;
        $sqlTipoFecha="";
        if($tipoFecha==1){
            $sqlTipoFecha="date(ges_cli_com_fec)";
        }
        if($tipoFecha==2){
            $sqlTipoFecha="date(ges_cli_fec)";
        }
        $sqlCall="";
        if($idcalls!="0"){
            $sqlCall=" and ee.cal_id_FK in ($idcalls) ";
        }

        $sqlCartera="";
        if($cartera=="0"){
            $sqlCartera=" and car_id_FK <> :car  ";
        }else{
            $sqlCartera=" and car_id_FK=:car  ";
        }
        
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        cli_cod as codigo,
                        cal_nom as calls,
                        cli_nom as nombre,
                        car_nom as cartera,
                        ges_cli_fec as fecha,
                        hour(ges_cli_fec) as hora,
                        minute(ges_cli_fec)	as minuto,
                        second(ges_cli_fec)	as segundo,
                        CONCAT(e.emp_cod,' - ',e.emp_nom) as gestor,
                        CASE
                            WHEN e.emp_tip_acc = 1 THEN
                                'ADMINISTRADOR'
                            WHEN e.emp_tip_acc = 2 THEN
                                'G. TELEFONICO'
                            WHEN e.emp_tip_acc = 3 THEN
                                'G. DOMICILIARIO'
                            WHEN e.emp_tip_acc = 4 THEN
                                'DIGITADOR'
                            WHEN e.emp_tip_acc = 5 THEN
                                'SUPERVISOR'
                            ELSE
                                ''
                        END AS perfil,
                        ges_cli_med as medio,
                        CASE
                            WHEN ges_cli_acc = 1 THEN
                                'LLAMADA RECIBIDA'
                            WHEN ges_cli_acc = 2 THEN
                                'LLAMADA SALIENTE'
                            WHEN ges_cli_acc = 3 THEN
                                'RECIBIR VISITA'
                            WHEN ges_cli_acc = 4 THEN
                                'REALIZAR VISITA'
                            WHEN ges_cli_acc = 5 THEN
                                'LLAMADA RECIBIDA - SMS'
                            WHEN ges_cli_acc = 6 THEN
                                'LLAMADA RECIBIDA - IVR'
                            WHEN ges_cli_acc = 7 THEN
                                'LLAMADA RECIBIDA - EMAIL'
                            ELSE
                                ''
                        END AS accion,
                        ges_cli_fec_visit as fecha_visita,
                        CASE WHEN res_ubi=0 THEN 'CONTACTO' 
                                WHEN res_ubi=1 THEN 'NO CONTACTO' 
                                WHEN res_ubi=2 THEN 'NO DISPONIBLE'
                        END AS ubi,
                        res_des as rpta,
                        mot_res as motivo_no_pago,
                        ges_cli_det as detalle,
                        DATE_FORMAT(ges_cli_com_fec,'%d/%m/%Y') as fecha_compromiso,
                        ges_cli_com_can as monto_compromiso,
                        if(ges_cli_com_can=0,'',if(ges_cli_com_mon=0,'SOLES','DOLARES')) as moneda,
                        ges_cli_conf_fec as fecha_conf,
                        ges_cli_conf_can as monto_conf,
                        cli_dir_dir as direccion,
                        cli_dir_dis as distrito,
                        cli_dir_pro as provincia,
                        cli_dir_dep as departamento
                    FROM
                        cliente c
                    INNER JOIN gestion_cliente g ON c.cli_id=g.cli_id_FK
                    LEFT JOIN empleado e on g.emp_id_FK=e.emp_id
                    INNER JOIN cartera ca on c.car_id_FK=ca.car_id
                    LEFT JOIN cliente_direccion_2 cd ON c.cli_id=cd.cli_id_FK and cli_dir_est=0 AND cli_dir_pas=0
                    INNER JOIN respuesta r on g.res_id_FK=r.res_id
                    LEFT JOIN motivo_nopago m on g.mot_id_FK=m.mot_id
                    LEFT JOIN empleado ee on c.emp_tel_id_FK=ee.emp_id
                    LEFT JOIN call_telefonica l on ee.cal_id_FK=l.cal_id
                    WHERE
                        ($sqlTipoFecha BETWEEN :fecInicio and :fecFin)
                    $sqlCartera 
                    and res_id_FK in (1,43)
                    $sqlCall
                    GROUP BY ges_cli_id   
        "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
    }


    public static function reporteRankingGestores(Request $rq){
        $dias=[];
        $cartera=$rq->cartera;
        $idcalls=$rq->calls;        
        $fechaInicio = Carbon::parse($rq->fechaInicio);
        $fechaFin = Carbon::parse($rq->fechaFin);
        $diasDiferencia = ($fechaFin->diffInDays($fechaInicio));
        for($i=0;$i<=$diasDiferencia;$i++){
            $fecha = Carbon::parse($rq->fechaInicio);
            if($i==0){
                $dias[0]=substr($fechaInicio,0,10);
            }else{
                $dias[$i]=substr($fecha->addDays($i),0,10);
            }
        }
        
        $sqlDia="";
        $sql="";
        for($k=0;$k<count($dias);$k++){
            $sqlDia.="sum(if(pago_cli_fec='".$dias[$k]."',pago_cli_mon,0)) as dia$k,";
            $sql.="sum(dia$k),";
        }
      
        $sqlCall="";
        if($idcalls!=0){
            $sqlCall=" and cal_id_FK in ($idcalls)";
        }

        return DB::connection('mysql')->select(DB::raw("
                 SELECT
                    if(gestor is null,'TOTAL',gestor) as gestor,
                    $sql
                    sum(pago_cli_mon) as pago,
                    sum(met_emp_meta) as meta,
                    if(sum(met_semanal) is null,0,sum(met_semanal)) as meta_semanal,
                    if(sum(pago_cli_mon) - sum(met_semanal) is null,0,sum(pago_cli_mon) - sum(met_semanal)) as desfase
                 FROM
                    (SELECT
                        pago_gestor,
                        concat(emp_cod,' - ',emp_nom) as gestor,
                        $sqlDia
                        pago_cli_fec,
                        sum(pago_cli_mon) as pago_cli_mon,
                        met_emp_meta,
                        (
                            (SELECT
                                porcentaje_ideal
                            FROM
                                indicadores.timing
                            WHERE
                                fecha_t in (ADDDATE(:fecFin1,INTERVAL -1 DAY))
                            ) -
                            (SELECT
                                if(day(fecha_t)='01',0,porcentaje_ideal)
                            FROM
                                indicadores.timing
                            WHERE
                                fecha_t in (:fecInicio1)
                            )
                        ) * met_emp_meta as met_semanal
                        -- WEEK(:fecInicio2, 5) - WEEK(DATE_SUB(:fecInicio3, INTERVAL DAYOFMONTH(:fecInicio4) - 1 DAY), 5) + 1 as semana
                    FROM
                        creditoy_asignacion.metas_empleado m 
                    INNER JOIN empleado e on m.met_emp_codigo=e.emp_cod and emp_est=0
                    LEFT JOIN indicadores.pago p ON m.met_emp_codigo=pago_gestor
                    WHERE
                        DATE_FORMAT(met_emp_periodo,'%Y%m') in (DATE_FORMAT(:fecInicio2,'%Y%m'),DATE_FORMAT(:fecFin2,'%Y%m'))
                    and pago_cli_fec BETWEEN :fecInicio and :fecFin
                    and car_id=:car1
                    and met_emp_cartera=:car2
                    $sqlCall
                    GROUP BY pago_gestor
                )t
                GROUP BY gestor WITH ROLLUP
                
        "),array("car1"=>$cartera,"car2"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin,"fecInicio1"=>$fechaInicio,"fecFin1"=>$fechaFin,"fecInicio2"=>$fechaInicio,"fecFin2"=>$fechaFin));
    }

    public static function reporteConfirmacionesPagos(Request $rq){
        $fecInicio=$rq->fechaInicio;
        $fecFin=$rq->fechaFin;
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    if(cartera is null,'TOTAL',cartera) as cartera,
                    if(t_conf is null,0,t_conf) as t_conf,
                    if(t_pagos is null,0,t_pagos) as t_pagos,
                    if(t_pag_ges is null,0,t_pag_ges) as t_pag_ges,
                    if(t_pag_sup is null,0,t_pag_sup) as t_pag_sup,
                    if(t_pag_rest is null,0,t_pag_rest) as t_pag_rest,
                    if(t_conf_sin_pag is null,0,t_conf_sin_pag) as t_conf_sin_pag
                FROM
                    (SELECT
                        cartera,
                        sum(conf) as t_conf,
                        sum(monto) as t_pagos,
                        sum(pag_gestor)as t_pag_ges,
                        sum(pag_super) as t_pag_sup,
                        sum(pag_restante) as t_pag_rest,
                        sum(conf_sin_pagos) as t_conf_sin_pag
                    FROM
                    (
                    SELECT
                        car_nom as cartera,
                        tt.car_id,
                        (
                            SELECT
                                sum(ges_cli_conf_can)
                            FROM
                                cliente cc
                            INNER JOIN gestion_cliente g ON cc.cli_id = g.cli_id_FK
                            WHERE ges_cli_fec BETWEEN :fecInicio2 and :fecFin2
                            and res_id_FK=2
                            and car_id_FK=tt.car_id
                        )as conf,
                        monto,
                        pag_gestor,
                        pag_super,
                        pag_restante,
                        (
                            SELECT
                                sum(ges_cli_conf_can)
                            FROM
                                cliente cc
                        INNER JOIN gestion_cliente g ON cc.cli_id = g.cli_id_FK
                            WHERE ges_cli_fec BETWEEN :fecInicio3 and :fecFin3
                            and res_id_FK=2
                            and car_id_FK=tt.car_id
                            AND (
                                SELECT
                                    count(*)
                                FROM
                                    indicadores.pago
                                WHERE
                                    pago_cli_fec BETWEEN :fecInicio1 and :fecFin1
                                    and car_id=tt.car_id
                                    and pago_cli_cod=cc.cli_cod
                            ) = 0
                        )as conf_sin_pagos
                    FROM
                        (SELECT
                            t.car_id,
                            sum(pago_cli_mon) as monto,
                            sum(pag_gestor) as pag_gestor,
                            sum(pag_super) as pag_super,
                            sum(pag_restante) as pag_restante
                            FROM
                            (SELECT
                                    car_id,
                                    pago_gestor,
                                    pago_cli_mon,
                                    if(emp_tip_acc=2,pago_cli_mon,0) as pag_gestor,
                                    if(emp_tip_acc=1,pago_cli_mon,0) as pag_super,
                                    if(emp_tip_acc is null,pago_cli_mon,0) as pag_restante
                                FROM
                                indicadores.pago p
                                LEFT JOIN creditoy_cobranzas.empleado e on p.pago_gestor=e.emp_cod
                                WHERE
                                    pago_cli_fec BETWEEN :fecInicio and :fecFin
                            )t
                        GROUP BY t.car_id
                    )tt
                INNER JOIN cartera ca on tt.car_id=ca.car_id
                )ttt
                GROUP BY cartera WITH ROLLUP
                )t
        "),array("fecInicio"=>$fecInicio,"fecFin"=>$fecFin,
                "fecInicio1"=>$fecInicio,"fecFin1"=>$fecFin,
                "fecInicio2"=>$fecInicio,"fecFin2"=>$fecFin,
                "fecInicio3"=>$fecInicio,"fecFin3"=>$fecFin
            ));        
    }

    public static function generarGestionesFicticias(Request $rq){
        $paleta=$rq->paleta;
        $modalidad=$rq->modalidad;
        $cantidad=$rq->cantidad;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $cartera=$rq->cartera;
        $parametros=array();

        $inhibir=DB::connection('mysql')->select(DB::raw("select * from creditoy_reporte.inh_detalle"));
        $array = array();
        foreach($inhibir as $i){
            $array[] = $i->detalle;
        }
        $cantidad_i=count($array);
        //dd($array);
        //dd($cantidad_i);

        $sqlC=" ";
        $filtro=" ";

        if($modalidad=='1'){
            $filtro= $filtro."
                AND ges_cli_acc IN (2)
				AND emp_tip_acc=2
            ";
        }else{
            $filtro= $filtro."
                AND ges_cli_acc IN (3,4)
				AND emp_tip_acc IN (3,4)
            ";
        }

        if($cartera=='0'){
            $filtro = $filtro." and 1=1";
        }else{
            $sqlC= $sqlC."
                 INNER JOIN cliente as c on c.cli_id=g.cli_id_FK
            ";
            $filtro = $filtro." and car_id_FK=:car";
            $parametros["car"]=$cartera;
        }
        $sql="
                SELECT
                res,
                ges_cli_det
                FROM
                (
                SELECT
                    (select res_des from respuesta where res_id=res_id_FK) as res,
                    ges_cli_det
                FROM
                gestion_cliente g 
                INNER JOIN empleado e on g.emp_id_FK=e.emp_id
                ";

        $sql= $sql." ".$sqlC." ";
                
        $sql= $sql." "." WHERE DATE(ges_cli_fec) BETWEEN :fecInicio AND :fecFin and res_id_FK in (:paleta) and ges_cli_det not in  ('')";
        

        if($cantidad_i>0){
            $sql=$sql." AND ( ";
            for($i=0;$i<$cantidad_i;$i++){ 
                $sql = $sql." UPPER(ges_cli_det) NOT LIKE ('%".$array[$i]."%')".(($i == $cantidad_i-1)?"":" AND ");
            }
            $sql=$sql." ) ";
        }else{
            $sql=$sql." AND 1=1 ";
        }
        
        $sql = $sql." ".$filtro."
                ORDER BY
                    RAND()
                ) b
                LIMIT :cant
        ";

        $parametros["fecInicio"]=$fechaInicio;
        $parametros["fecFin"]=$fechaFin;
        $parametros["paleta"]=$paleta;
        $parametros["cant"]=$cantidad;

        return DB::connection('mysql')->select(DB::raw($sql),$parametros);
    }



}

