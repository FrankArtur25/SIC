<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Plan extends Model
{
    public static function listaPlanes(Request $rq){
        $cartera = $rq->cartera;
        $fechaInicio = $rq->fechaInicio;
        $fechaFin = $rq->fechaFin;
        return DB::connection('mysql')->select(DB::raw("
                select 
                    id_plan as id,
                    fecha_i as fecha, 
                    fecha_f as fechaFin,
                    nombre_cartera as cartera,
                    nombre_plan as nombre,
                    cant_clientes as clientes,
                    speech,
                    detalle
                from indicadores.plan
                where id_cartera=:car
                and date(fecha_i) between :fecInicio and :fecFin
        "),array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
    }

    public static function resumenPlan(Request $rq){
        $cartera = $rq->cartera;
        $tramo = implode(',',$rq->tramo);
        $departamento = implode(',',$rq->departamento);
        $prioridad = implode(',',$rq->prioridad);
        $situacion = implode(',',$rq->situacion);
        $call = implode(',',$rq->call);
        $sueldo = implode(',',$rq->sueldo);
        $capital = implode(',',$rq->capital);
        $deuda = implode(',',$rq->deuda);
        $importe = implode(',',$rq->importe);
        $ubicabilidad = implode(',',$rq->ubicabilidad);
        $entidad = implode(',',$rq->entidad);
        $tipoCliente = implode(',',$rq->tipoCliente);
        $score = implode(',',$rq->score);
        $respuestas = implode(',',$rq->respuestas);
        $fechas = implode(',',$rq->fechas);
        $gestiones = implode(',',$rq->gestiones);
        $sql="";
        
        if($tramo!='' && $tramo!="'TODOS'"){
            $sql.=" and tramo in ($tramo)";
        }
        if($departamento!='' && $departamento!="'TODOS'"){
            $sql.=" and dep in ($departamento)";
        }
        if($prioridad!='' && $prioridad!="'TODOS'"){
            $sql.=" and prioridad in ($prioridad)";
        }
        if($situacion!='' && $situacion!="'TODOS'"){
            $sql.=" and dep_ind in ($situacion)";
        }
        if($call!='' && $call!="'TODOS'"){
            $sql.=" and cal_nom in ($call)";
        }
        if($sueldo!='' && $sueldo!="'TODOS'"){
            $sql.=" and rango_sueldo in ($sueldo)";
        }
        if($capital!='' && $capital!="'TODOS'"){
            $sql.=" and capital in ($capital)";
        }
        if($deuda!='' && $deuda!="'TODOS'"){
            $sql.=" and saldo_deuda in ($deuda)";
        }
        if($importe!='' && $importe!="'TODOS'"){
            $sql.=" and monto_camp in ($importe)";
        }
        if($ubicabilidad!='' && $ubicabilidad!="'TODOS'"){
            $sql.=" and ubicabilidad in ($ubicabilidad)";
        }
        if($respuestas!='' && $respuestas!="'TODOS'"){
            $sql.=" and res_id_FK in ($respuestas)";
        }
        if($entidad!='' && $entidad!="'TODOS'"){
            $sql.=" and entidad in ($entidad)";
        }
        
        if($score!='' && $score!="'TODOS'"){
            $sql.=" and score in ($score)";
        }
        if($tipoCliente!='' && $tipoCliente!="'TODOS'"){
            $sql.=" and nuevo in ($tipoCliente)";
        }
        if($fechas!='' && $fechas!="'TODOS'"){
            $sql.=" and fechas_gestiones in ($fechas)";
        }
        if($gestiones!='' && $gestiones!="'TODOS'"){
            $sql.=" and cant_gestiones in ($gestiones)";
        }

        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    count(cuenta) as cantidad,
                    emp_cod as usuario, 
                    emp_nom as gestor
                FROM 
                (SELECT
                    cuenta,
                        cli_nom,
                        if(cli_tel_tel is null,'0',cli_tel_tel) as cli_tel_tel,
                        capital as cap,
                        saldo_deuda as sal,
                        monto_camp as importe,
                        (CASE 
                            WHEN rango_sueldo like ('A%') THEN 'AA'
                            WHEN rango_sueldo like ('B%') THEN 'BB'
                            WHEN rango_sueldo like ('C%') THEN 'CC'
                            WHEN rango_sueldo like ('D%') THEN 'DD'
                        END) AS rango_sueldo,
                        dep_ind,
                        prioridad,
                        if(cal_nom is null,'SIN CALL',cal_nom) as cal_nom,
                        if(emp_cod is null,'NO ASIGNADO',emp_cod) as emp_cod,
                        if(emp_nom is null,'NO ASIGNADO',emp_nom) as emp_nom,
                        cartera,
                        cd.car_id_fk as car,
                        (CASE WHEN dep LIKE '%TACNA%' or dep LIKE '%JUNIN%' or dep LIKE '%HUANUCO%' or dep LIKE '%UCAYALI%' or
                                dep LIKE '%LORETO%' or dep LIKE '%CUSCO%' or dep LIKE '%OTROS%' or dep LIKE '%MOQUEGUA%' OR
                                dep LIKE '%HUANCAVELICA%' or dep LIKE '%SAN MARTIN%' or dep LIKE '%PUNO%' or dep LIKE '%TUMBES%' or 
                                dep LIKE '%AYACUCHO%' or dep LIKE '%PASCO%' THEN 'OTROS'
                            ELSE dep
                        END) AS dep,
                        if(tramo <=2016,2016,tramo) AS tramo,
                        (CASE 
                                WHEN capital <500 THEN 'AA'
                                WHEN capital >= 500 and capital < 1000 THEN 'BB'
                                WHEN capital >= 1000 and capital < 3000 THEN 'CC'
                                WHEN capital >= 3000 THEN 'DD'
                        END) AS capital,
                        (CASE 
                                WHEN saldo_deuda <500 THEN 'AA'
                                WHEN saldo_deuda >= 500 and saldo_deuda < 1000 THEN 'BB'
                                WHEN saldo_deuda >= 1000 and saldo_deuda < 3000 THEN 'CC'
                                WHEN saldo_deuda >= 3000 THEN 'DD'
                        END) AS saldo_deuda,
                        (CASE 
                                WHEN monto_camp <500 THEN 'AA'
                                WHEN monto_camp >= 500 and monto_camp < 1000 THEN 'BB'
                                WHEN monto_camp >= 1000 and monto_camp < 3000 THEN 'CC'
                                WHEN monto_camp >= 3000 THEN 'DD'
                        END) AS monto_camp,
                        (CASE 
                                WHEN ges_cli_fec IS NULL OR DATE_FORMAT(ges_cli_fec,'%Y%m')<DATE_FORMAT(NOW(),'%Y%m') THEN 'Sin Gestión' 
                                WHEN res_id_FK IN ( 38, 6, 22, 41 ) THEN 'C-F-R-N'
                                WHEN res_id_FK IN ( 2,37,33,10,1,8,43,39,7,3,5,9,34,17,21,18,28,30,35,36,46,47,48,49 ) THEN 'Contacto'
                                WHEN res_id_FK IN ( 32 ) THEN 'No Disponible'
                                WHEN res_id_FK IN ( 19,27,12,26,13,4,11,12,20,14,15,16,23,24,29,31 ) THEN 'Ilocalizado'
                                WHEN res_id_FK IN ( 45,44, 25 ) THEN 'No Contacto'
                                ELSE 'NO ENCONTRADO'
                        END) AS ubicabilidad,
                    (CASE 
                        WHEN entidades like '%1%' or entidades = 2 THEN 2
                        WHEN entidades like '%2%' or entidades = 3 THEN 3
                        WHEN entidades like '%3%' or entidades >= 4 THEN 4
                        ELSE 1
                    END) AS entidad,
                    if(cli_nuev_cod is null,'Otros','Nuevos/Nuevos Castigo') as nuevo,
                    res_id_FK,
                    score,
                    (CASE 
                        WHEN date(ges_cli_fec)=date(now()) THEN 'Hoy' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -1 DAY) THEN 'Hace 1 día' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -2 DAY) THEN 'Hace 2 días' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -3 DAY) THEN 'Hace 3 días' 
                        WHEN date(ges_cli_fec)<=ADDDATE(date(NOW()),INTERVAL -4 DAY) THEN 'Hace más de 3 días' 
                    END) AS fechas_gestiones,
                    (select 
                        case when count(cli_id_FK)=1 then '1'
                             when count(cli_id_FK)=2 then '2'
                             when count(cli_id_FK)=3 then '3'
                             when count(cli_id_FK)>=4 then '4+'
                             ELSE '0'
                        END
                     from gestion_cliente 
                     where cli_id_FK=cli_id
                     and DATE_FORMAT(ges_cli_fec,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m')
                    ) as cant_gestiones
                FROM
                    indicadores.cartera_detalle cd
                INNER JOIN creditoy_cobranzas.cliente c ON c.cli_cod = cd.cuenta
                LEFT JOIN creditoy_cobranzas.cliente_telefono as tele on c.cli_id=tele.cli_id_FK and cli_tel_est=0 and cli_tel_pas=0
                left JOIN creditoy_cobranzas.empleado as e on c.emp_tel_id_FK=e.emp_id and e.emp_est=0 and e.emp_pas=0
                left JOIN creditoy_cobranzas.call_telefonica as cal on e.cal_id_FK=cal.cal_id
                LEFT JOIN creditoy_cobranzas.nuevo_cliente as cn on c.cli_cod=cn.cli_nuev_cod
                LEFT JOIN gestion_cliente g on c.ges_cli_tel_id_FK=g.ges_cli_id
                WHERE 
                        cli_est=0
                    and cli_pas=0
                    and c.car_id_fk=:car1
                    and cd.car_id_FK=:car2
                    and date_format(cd.fecha,'%Y%m')=date_format(now(),'%Y%m') 
                GROUP BY cuenta
                )t
            where 
                1=1
                $sql
            group by emp_cod
        "),array("car1"=>$cartera,"car2"=>$cartera));
    }

    public static function insertarPlan(Request $rq){
        $cartera = $rq->cartera;        
        $nombreCartera = $rq->nombreCartera;
        $nombrePlan=$rq->plan;
        $total=$rq->total;
        $speech=isset($rq->speech)?$rq->speech:'';
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $orden=$rq->orden;
        $cantidad=$rq->cantidad;
        $tramo = implode(',',$rq->tramo);
        $departamento = implode(',',$rq->departamento);
        $prioridad = implode(',',$rq->prioridad);
        $situacion = implode(',',$rq->situacion);
        $call = implode(',',$rq->call);
        $sueldo = implode(',',$rq->sueldo);
        $capital = implode(',',$rq->capital);
        $deuda = implode(',',$rq->deuda);
        $importe = implode(',',$rq->importe);
        $ubicabilidad = implode(',',$rq->ubicabilidad);
        $entidad = implode(',',$rq->entidad);
        $tipoCliente = implode(',',$rq->tipoCliente);
        $score = implode(',',$rq->score);
        $respuestas = implode(',',$rq->respuestas);
        $respuestasNombres = implode(',',$rq->respuestasNombres);
        $usuarios = implode(',',$rq->usuarios);
        $fechas = implode(',',$rq->fechas);
        $gestiones = implode(',',$rq->gestiones);
        $sql="";
        $parametros=array();
        $parametros["car1"]=$cartera;
        $parametros["car2"]=$cartera;

        if($usuarios!=''){
            $sql.=" and gestor in ($usuarios)";
        }
        if($tramo!='' && $tramo!="'TODOS'"){
            $sql.=" and tramo in ($tramo)";
        }
        if($departamento!='' && $departamento!="'TODOS'"){
            $sql.=" and dep in ($departamento)";
        }
        if($prioridad!='' && $prioridad!="'TODOS'"){
            $sql.=" and prioridad in ($prioridad)";
        }
        if($situacion!='' && $situacion!="'TODOS'"){
            $sql.=" and dep_ind in ($situacion)";
        }
        if($call!='' && $call!="'TODOS'"){
            $sql.=" and cal_nom in ($call)";
        }
        if($sueldo!='' && $sueldo!="'TODOS'"){
            $sql.=" and rango_sueldo in ($sueldo)";
        }
        if($capital!='' && $capital!="'TODOS'"){
            $sql.=" and capital in ($capital)";
        }
        if($deuda!='' && $deuda!="'TODOS'"){
            $sql.=" and saldo_deuda in ($deuda)";
        }
        if($importe!='' && $importe!="'TODOS'"){
            $sql.=" and monto_camp in ($importe)";
        }
        if($ubicabilidad!=''&& $ubicabilidad!="'TODOS'"){
            $sql.=" and ubicabilidad in ($ubicabilidad)";
        }
        if($respuestas!='' && $respuestas!="'TODOS'"){
            $sql.=" and res_id_FK in ($respuestas)";
        }
        if($entidad!='' && $entidad!="'TODOS'"){
            $sql.=" and entidad in ($entidad)";
        }
        if($score!='' && $score!="'TODOS'"){
            $sql.=" and score in ($score)";
        }
        if($tipoCliente!='' && $tipoCliente!="'TODOS'"){
            $sql.=" and nuevo in ($tipoCliente)";
        }
        if($fechas!='' && $fechas!="'TODOS'"){
            $sql.=" and fechas_gestiones in ($fechas)";
        }
        if($gestiones!='' && $gestiones!="'TODOS'"){
            $sql.=" and cant_gestiones in ($gestiones)";
        }

        if($orden!=''){
            if($orden==1){
                $sql.=" order by gestor,cap desc";
            }
            if($orden==2){
                $sql.=" order by gestor,deuda desc";
            }
            if($orden==3){
                $sql.=" order by gestor,importe desc";
            }
            if($orden==4){
                $sql.=" order by gestor,cap asc";
            }
            if($orden==5){
                $sql.=" order by gestor,deuda asc";
            }
            if($orden==6){
                $sql.=" order by gestor,importe asc";
            }
        }else{
            $sql.=" order by gestor";
        }

        $detalle="Tramo: ".str_replace("'","",$tramo).";";
        $detalle.="Departamentos: ".str_replace("'","",$departamento).";";
        $detalle.="Prioridad: ".str_replace("'","",$prioridad).";";
        $detalle.="Situación Laboral: ".str_replace("'","",$situacion).";";
        $detalle.="Call: ".str_replace("'","",$call).";";
        $detalle.="Rango Sueldo: ".str_replace("DD","[3000+>",str_replace("CC","[1000-3000>",str_replace("BB","[500-1000>",str_replace("AA","[0-500>",str_replace("'","",$sueldo))))).";";
        $detalle.="Rango Capital: ".str_replace("DD","[3000+>",str_replace("CC","[1000-3000>",str_replace("BB","[500-1000>",str_replace("AA","[0-500>",str_replace("'","",$capital))))).";";
        $detalle.="Rango Deuda: ".str_replace("DD","[3000+>",str_replace("CC","[1000-3000>",str_replace("BB","[500-1000>",str_replace("AA","[0-500>",str_replace("'","",$deuda))))).";";
        $detalle.="Rango Importe: ".str_replace("DD","[3000+>",str_replace("CC","[1000-3000>",str_replace("BB","[500-1000>",str_replace("AA","[0-500>",str_replace("'","",$importe))))).";";
        $detalle.="Ubicabilidad: ".str_replace("'","",$ubicabilidad).";";
        $detalle.="Respuestas: ".str_replace("'","",$respuestasNombres).";";
        $detalle.="Entidades: ".str_replace("'","",$entidad).";";
        $detalle.="Score: ".str_replace("'","",$score).";";
        $detalle.="Tipo Cliente: ".str_replace("'","",$tipoCliente).";";
        $detalle.="Última Gestión: ".str_replace("'","",$fechas).";";
        $detalle.="Gestiones Mes: ".str_replace("'","",$gestiones).";";
        $detalle.="Usuarios: ".str_replace("'","",$usuarios)."";
        $sqlCantidad='';
        if($cantidad!=''){
            $sqlCantidad=" WHERE id<=:cant ";
            $parametros["cant"]=$cantidad;
        }
        
        $datos=DB::connection('mysql')->select(DB::raw("
                SELECT
                    cli_cod as codigos,
                    gestor
                FROM 
                (SELECT
                    car,
                    cartera,
                    cli_cod,
                    @ac:=CASE WHEN @ges=gestor THEN @ac + 1 ELSE 1 END AS id,
                    @ges:=gestor,
                    gestor
                FROM
                (SELECT
                    cuenta,
                        cli_cod,
                        capital as cap,
                        (CASE 
                            WHEN rango_sueldo like ('A%') THEN 'AA'
                            WHEN rango_sueldo like ('B%') THEN 'BB'
                            WHEN rango_sueldo like ('C%') THEN 'CC'
                            WHEN rango_sueldo like ('D%') THEN 'DD'
                        END) AS rango_sueldo,
                        dep_ind,
                        prioridad,
                        if(cal_nom is null,'SIN CALL',cal_nom) as cal_nom,
                        if(emp_cod is null,'NO ASIGNADO',emp_cod) as emp_cod,
                        if(emp_nom is null,'NO ASIGNADO',emp_nom) as emp_nom,
                        cartera,
                        cd.car_id_fk as car,
                        (CASE WHEN dep LIKE '%TACNA%' or dep LIKE '%JUNIN%' or dep LIKE '%HUANUCO%' or dep LIKE '%UCAYALI%' or
                                dep LIKE '%LORETO%' or dep LIKE '%CUSCO%' or dep LIKE '%OTROS%' or dep LIKE '%MOQUEGUA%' OR
                                dep LIKE '%HUANCAVELICA%' or dep LIKE '%SAN MARTIN%' or dep LIKE '%PUNO%' or dep LIKE '%TUMBES%' or 
                                dep LIKE '%AYACUCHO%' or dep LIKE '%PASCO%' THEN 'OTROS'
                            ELSE dep
                        END) AS dep,
                        if(tramo <=2016,2016,tramo) AS tramo,
                        (CASE 
                                WHEN capital <500 THEN 'AA'
                                WHEN capital >= 500 and capital < 1000 THEN 'BB'
                                WHEN capital >= 1000 and capital < 3000 THEN 'CC'
                                WHEN capital >= 3000 THEN 'DD'
                        END) AS capital,
                        (CASE 
                                WHEN saldo_deuda <500 THEN 'AA'
                                WHEN saldo_deuda >= 500 and saldo_deuda < 1000 THEN 'BB'
                                WHEN saldo_deuda >= 1000 and saldo_deuda < 3000 THEN 'CC'
                                WHEN saldo_deuda >= 3000 THEN 'DD'
                        END) AS saldo_deuda,
                        saldo_deuda as deuda,
                        (CASE 
                                WHEN monto_camp <500 THEN 'AA'
                                WHEN monto_camp >= 500 and monto_camp < 1000 THEN 'BB'
                                WHEN monto_camp >= 1000 and monto_camp < 3000 THEN 'CC'
                                WHEN monto_camp >= 3000 THEN 'DD'
                        END) AS monto_camp,
                        monto_camp as importe,
                        (CASE 
                                WHEN ges_cli_fec IS NULL OR DATE_FORMAT(ges_cli_fec,'%Y%m')<DATE_FORMAT(NOW(),'%Y%m') THEN 'Sin Gestión' 
                                WHEN res_id_FK IN ( 38, 6, 22, 41 ) THEN 'C-F-R-N'
                                WHEN res_id_FK IN ( 2,37,33,10,1,8,43,39,7,3,5,9,34,17,21,18,28,30,35,36,46,47,48,49 ) THEN 'Contacto'
                                WHEN res_id_FK IN ( 32 ) THEN 'No Disponible'
                                WHEN res_id_FK IN ( 19,27,12,26,13,4,11,12,20,14,15,16,23,24,29,31 ) THEN 'Ilocalizado'
                                WHEN res_id_FK IN ( 45,44, 25 ) THEN 'No Contacto'
                                ELSE 'NO ENCONTRADO'
                        END) AS ubicabilidad,
                    (CASE 
                        WHEN entidades like '%1%' or entidades = 2 THEN 2
                        WHEN entidades like '%2%' or entidades = 3 THEN 3
                        WHEN entidades like '%3%' or entidades >= 4 THEN 4
                        ELSE 1
                    END) AS entidad,
                    if(cli_nuev_cod is null,'Otros','Nuevos/Nuevos Castigo') as nuevo,
                    if(emp_cod is null,'NO ASIGNADO',emp_cod) as gestor,
                    res_id_FK,
                    score,
                    (CASE 
                        WHEN date(ges_cli_fec)=date(now()) THEN 'Hoy' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -1 DAY) THEN 'Hace 1 día' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -2 DAY) THEN 'Hace 2 días' 
                        WHEN date(ges_cli_fec)=ADDDATE(date(NOW()),INTERVAL -3 DAY) THEN 'Hace 3 días' 
                        WHEN date(ges_cli_fec)<=ADDDATE(date(NOW()),INTERVAL -4 DAY) THEN 'Hace más de 3 días' 
                    END) AS fechas_gestiones,
                    (select 
                        case when count(cli_id_FK)=1 then '1'
                             when count(cli_id_FK)=2 then '2'
                             when count(cli_id_FK)=3 then '3'
                             when count(cli_id_FK)>=4 then '4+'
                             ELSE '0'
                        END
                     from gestion_cliente 
                     where cli_id_FK=cli_id
                     and DATE_FORMAT(ges_cli_fec,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m')
                    ) as cant_gestiones
                FROM
                    indicadores.cartera_detalle cd
                INNER JOIN creditoy_cobranzas.cliente c ON c.cli_cod = cd.cuenta
                LEFT JOIN creditoy_cobranzas.cliente_telefono as tele on c.cli_id=tele.cli_id_FK and cli_tel_est=0 and cli_tel_pas=0
                left JOIN creditoy_cobranzas.empleado as e on c.emp_tel_id_FK=e.emp_id and e.emp_est=0 and e.emp_pas=0
                left JOIN creditoy_cobranzas.call_telefonica as cal on e.cal_id_FK=cal.cal_id
                LEFT JOIN creditoy_cobranzas.nuevo_cliente as cn on c.cli_cod=cn.cli_nuev_cod
                LEFT JOIN gestion_cliente g on c.ges_cli_tel_id_FK=g.ges_cli_id
                WHERE 
                        cli_est=0
                    and cli_pas=0
                    and c.car_id_fk=:car1
                    and cd.car_id_FK=:car2
                    and date_format(cd.fecha,'%Y%m')=date_format(now(),'%Y%m') 
                GROUP BY cuenta
                )t
            CROSS JOIN (SELECT @ac := 0) AS dummy8
            CROSS JOIN (
                SELECT
                    @ges := emp_cod
                FROM
                    creditoy_cobranzas.empleado
                GROUP BY
                    emp_cod
                LIMIT 1
            ) AS dummy2
            where 
                1=1
                $sql
        )tt 
        $sqlCantidad
        "),$parametros);
        
        $codigos="";
        $array=[];
        if(count($datos)>0){
            foreach($datos as $d){
                $array[] = $d->codigos;
            }
            $codigos=implode(',',$array);
        }
        
        DB::connection('mysql')->insert("
            INSERT INTO indicadores.plan(
                id_cartera,nombre_cartera,nombre_plan,clientes,cant_clientes,speech,detalle,fecha_i,fecha_f,fecha_reg
            )
            VALUES(:idcar,:car,:plan,:cods,:total,:speech,:detalle,:fecInicio,:fecFin,now())
        ",array("idcar"=>$cartera,"car"=>$nombreCartera,"plan"=>$nombrePlan,"total"=>$total,"cods"=>$codigos,
                "speech"=>$speech,"detalle"=>$detalle,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
        
        return "ok";
    }

    public static function usuariosPlan($clientes,$cartera){
        return DB::connection('mysql')->select(DB::raw("
                SELECT 
                    emp_id as id,
                    if(emp_cod is null,'NO ASIGNADO',concat(emp_cod,' - ',emp_nom)) as usuario,
                    count(cli_cod) as cantidad
                FROM
                (
                    SELECT 
                        cli_cod,
                        emp_id,
                        emp_cod,
                        emp_nom
                    FROM cliente as c
                    INNER JOIN indicadores.cartera_detalle as cd on c.cli_cod =cd.cuenta
                    LEFT JOIN empleado as e on c.emp_tel_id_FK=e.emp_id
                    where 
                        cli_est=0 and cli_pas=0
                        and c.car_id_fk=:car1
                        and	cd.car_id_fk=:car2
                        and date_format(fecha,'%Y-%m') = date_format(now(),'%Y-%m')
                        and cli_cod in ($clientes)
                    group By cli_cod
                ) t
                GROUP BY emp_cod
                order by emp_cod desc
        "),array("car1"=>$cartera,"car2"=>$cartera));
    }

    public static function resultadoPlan($idEmpleado,$clientes,$cartera,$fechaInicio,$fechaFin){
        return DB::connection('mysql')->select(DB::raw("
            SELECT
                sum(pdp) as can_pdp,
                sum(monto_pdp) as monto_pdp,
                sum(conf) as can_conf,
                sum(monto_conf)as monto_conf,
                sum(mot_np) as can_mot_np,
                if(cliente,count(DISTINCT cliente),0) as can_clientes,
                sum(gestiones) as cant_gestiones,
                count(DISTINCT contacto) as can_contacto,
                format(sum(gestiones)/if(cliente,count(DISTINCT cliente),0),2) as intensidad
            FROM 
            (SELECT
                if(gg.res_id_FK in (2,37,33,10,1,8,43,39,7,3,5,9,34,17,21,18,28,30,35,36,46,47,48,49) AND (gg.ges_cli_fec between :fecInicio1 and :fecFin1),cli_cod,null) as contacto,
                if(ges_cli_tel_id_FK,1,0) as gestiones,
                cli_cod as cliente,
                if(g.res_id_FK in (1,43),1,0) as pdp,
                if(g.res_id_FK in (1,43),g.ges_cli_com_can,0) as monto_pdp,
                if(g.res_id_FK in (2),1,0) as conf,
                if(g.res_id_FK in (2),g.ges_cli_conf_can,0) as monto_conf,
                if(g.mot_id_FK in (3),1,0) as mot_np
            FROM
            cliente as c
            inner JOIN gestion_cliente as g ON g.cli_id_FK=c.cli_id
            inner JOIN gestion_cliente gg ON c.ges_cli_tel_id_FK=gg.ges_cli_id
            WHERE
                    cli_est=0
                and cli_pas=0
                and car_id_FK=:car
                and if(:usuario=0,g.emp_id_FK=g.emp_id_FK,g.emp_id_FK=:emp)
                AND (g.ges_cli_fec between :fecInicio2 and :fecFin2)
                and cli_cod in ($clientes)
            ) t
        "),array("car"=>$cartera,"fecInicio1"=>$fechaInicio,"fecFin1"=>$fechaFin,"fecInicio2"=>$fechaInicio,
                "fecFin2"=>$fechaFin,"usuario"=>$idEmpleado,"emp"=>$idEmpleado));
    }

    public static function datosPlan($id){
        return DB::connection('mysql')->select(DB::raw("
                select 
                    clientes,
                    id_cartera as cartera,
                    fecha_f as fechaFin,
                    fecha_i as fechaInicio 
                from 
                    indicadores.plan 
                WHERE id_plan=:id
        "),array("id"=>$id));
    }
    
    public static function datosPlanUsuario(){
        $cartera=session()->get('datos')->idcartera;
        if($cartera==''){
            $cartera=0;
        }
        $fec_actual=Carbon::now();
        return DB::connection('mysql')->select(DB::raw("
                    select fecha_i as fechaInicio,fecha_f as fechaFin
                    from indicadores.plan
                    WHERE id_cartera in ($cartera)
                    and fecha_i<=:fec1 and fecha_f >= :fec2
                    LIMIT 1
                "),array("fec1"=>$fec_actual,"fec2"=>$fec_actual));
    }

    public static function reportePlan(){
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        car_id as idCartera,
                        car_nom as cartera,
                        date(NOW()) as fec1,
                        ADDDATE(date(NOW()),INTERVAL +1 DAY) as fec2,
                        ADDDATE(date(NOW()),INTERVAL +2 DAY) as fec3,
                        if(sum(dia1)>0,'SI','NO') as d1,
                        if(sum(dia2)>0,'SI','NO') as d2,
                        if(sum(dia3)>0,'SI','NO') as d3
                    FROM
                        creditoy_cobranzas.cartera c
                        LEFT JOIN
                            (SELECT
                                nombre_cartera,
                                id_cartera,
                                if(date(fecha_i)=date(NOW()),1,0) as dia1,
                                if(date(fecha_i)=ADDDATE(date(NOW()),INTERVAL +1 DAY),1,0) as dia2,
                                if(date(fecha_i)=ADDDATE(date(NOW()),INTERVAL +2 DAY),1,0) as dia3
                            FROM
                                indicadores.plan
                            WHERE date(fecha_i) BETWEEN date(NOW()) and ADDDATE(date(NOW()),INTERVAL +2 DAY)
                            )t on c.car_id=id_cartera
                    WHERE
                    car_est=0
                    and car_pas=0
                    GROUP BY car_nom    
        "));
    }

    public static function codigosCarteraPlan(Request $rq){
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;   
        return DB::connection('mysql')->select(DB::raw("
                    SELECT 
                        id_plan,
                        nombre_cartera,
                        id_cartera,
                        clientes,
                        nombre_plan,
                        cant_clientes,
                        fecha_i,
                        fecha_f
                    FROM
                        indicadores.plan
                    WHERE
                    date(fecha_i) BETWEEN date(:fecInicio) and date(:fecFin)
        "), array("fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
    }

    public static function reporteCantGestiones($cartera,$codigos,$fechaInicio,$fechaFin){
        return DB::connection('mysql')->select(DB::raw("
                SELECT 
                    count(DISTINCT cli_id) as gestionados
                FROM
                    creditoy_cobranzas.cliente c
                INNER JOIN creditoy_cobranzas.gestion_cliente g ON c.cli_id=g.cli_id_FK
                WHERE
                ges_cli_fec BETWEEN :fecInicio and :fecFin 
                and cli_cod in ($codigos)
                and car_id_FK=:car
        "), array("car"=>$cartera,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
    }

    public static function reporteListaPlan(Request $rq){
        $fecha=$rq->fecha;
        $cartera=$rq->idCartera;
        return DB::connection('mysql')->select(DB::raw("
                    SELECT 
                        nombre_plan as plan,
                        cant_clientes as clientes
                    FROM
                        indicadores.plan
                    WHERE
                        id_cartera = :car
                    AND date(fecha_i) = date(:fec)
        "),array("car"=>$cartera,"fec"=>$fecha));
    }

    public static function resumenPlanArchivo($cartera,$codigos){
        $codigo="";
        for($i=1;$i<count($codigos);$i++) {
            $codigo.=",".$codigos[$i][0];
        }
        $codigo=substr($codigo,1,strlen($codigo));
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    if(emp_tel_id_FK>0,emp_nom,'NO ASIGNADO') as gestor,
                    if(emp_cod is null,'NO ASIGNADO',emp_cod) as usuario,
                    count(emp_tel_id_FK) as cantidad
                FROM
                    creditoy_cobranzas.cliente c
                LEFT JOIN creditoy_cobranzas.empleado e on c.emp_tel_id_FK=e.emp_id
                WHERE
                cli_est=0
                and cli_pas=0
                and car_id_FK=:car
                and cli_cod in ($codigo)
                GROUP BY emp_tel_id_FK            
        "),array("car"=>$cartera));
    }

    public static function insertarPlanArchivo(Request $rq,$codigos){
        $cartera = $rq->cartera;        
        $nombreCartera = $rq->nombreCartera;
        $nombrePlan=$rq->plan;
        $total=$rq->total;
        $speech=isset($rq->speech)?$rq->speech:'';
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $usuarios = implode(',',$rq->usuarios);
        $detalle="Usuarios: ".str_replace("'","",$usuarios)."";
        $codigo="";
        for($i=1;$i<count($codigos);$i++) {
            $codigo.=",".$codigos[$i][0];
        }
        $codigo=substr($codigo,1,strlen($codigo));
        DB::connection('mysql')->insert("
            INSERT INTO indicadores.plan(
                id_cartera,nombre_cartera,nombre_plan,clientes,cant_clientes,speech,detalle,fecha_i,fecha_f,fecha_reg
            )
            VALUES(:idcar,:car,:plan,:cods,:total,:speech,:detalle,:fecInicio,:fecFin,now())
        ",array("idcar"=>$cartera,"car"=>$nombreCartera,"plan"=>$nombrePlan,"total"=>$total,"cods"=>$codigo,
                "speech"=>$speech,"detalle"=>$detalle,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
        
        return "ok";
    }

    public static function actualizarFechaPlan(Request $rq){
        $idcampana=$rq->idCampana;
        $fechaInicio=str_replace('T',' ',$rq->fechaInicio);
        $fechaFin=str_replace('T',' ',$rq->fechaFin);
        
        DB::connection('mysql')->update("
            update indicadores.plan
            set fecha_i=:fecInicio,fecha_f=:fecFin 
            where id_plan=:id
        ",array("id"=>$idcampana,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
        return "ok";
    }
}
