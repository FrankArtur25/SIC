<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Predictivo extends Model
{
    public static function repositorio(Request $rq,$opcion,$idcampana){
        $cartera=$rq->cartera;
        $tipo_cliente=$rq->tipo_cliente;
        $tipo_numeros=$rq->tipo_numeros;
        $ubicabilidad=implode(',',$rq->ubicabilidad);
        $respuesta=implode("','",$rq->respuesta);
        $capital_i=$rq->capital_i;
        $capital_f=$rq->capital_f;
        $deuda_i=$rq->deuda_i;
        $deuda_f=$rq->deuda_f;
        $importe_i=$rq->importe_i;
        $importe_f=$rq->importe_f;
        $score=implode("','",$rq->score);
        $entidad=implode("','",$rq->entidad);
        $zona=implode("','",$rq->zona);
        $prioridad=implode("','",$rq->prioridad);
        $tramo=implode("','",$rq->tramo);
        $gestion_dia=$rq->gestion_dia;
        $codigos=$rq->codigos;
        $inhCodigos=$rq->inhCodigos;
        $inhPaletas=$rq->inhibir_paletas;
        
        $condicion="";

        if($capital_i!='' && $capital_f!=''){
            $condicion.=" and capital between $capital_i and $capital_f";
        }

        if($deuda_i!='' && $deuda_f!=''){
            $condicion.=" and deuda between $deuda_i and $deuda_f";
        }

        if($importe_i!='' && $importe_f!=''){
            $condicion.=" and importe between $importe_i and $importe_f";
        }

        if($ubicabilidad!=''){
            $condicion.=" and ubicabilidad in ($ubicabilidad)";
        }

        if($respuesta!=''){
            $condicion.=" and res_des in ('$respuesta')";
        }

        if($score!=''){
            $condicion.=" and score in ('$score')";
        }

        if($entidad!=''){
            $condicion.=" and entidades in ('$entidad')";
        }

        if($prioridad!=''){
            $condicion.=" and prioridad in ('$prioridad')";
        }

        if($zona!=''){
            $condicion.=" and zonas in ('$zona')";
        }

        if($tramo!=''){
            $condicion.=" and tramo in ('$tramo')";
        }

        if($gestion_dia!=''){
            $condicion.=" and ult_fecha not in (date(now()))";
        }

        if($inhCodigos!=''){
            $condicion.=" and codigo not in ($inhCodigos)";
        }

        $sqlcodigos="";
        if($codigos!=''){
            $sqlcodigos=" and cli_cod in ($codigos)";
        }

        $inhibirPaletas="";
        if($inhPaletas==""){
            $inhibirPaletas='0';
        }else{
            $inhibirPaletas='1';
        }

        return DB::connection('mysql')->select(DB::raw("
            call creditoy_predictivo.DATA_PREDICTIVO(:opt,:car,:cond,:codigos,:tip_cli,:tip_num,:idcamp,:inhPaletas)
        "),array("opt"=>$opcion,"car"=>$cartera,"cond"=>$condicion,"codigos"=>$sqlcodigos,"tip_cli"=>$tipo_cliente,"tip_num"=>$tipo_numeros,"idcamp"=>$idcampana,"inhPaletas"=>$inhibirPaletas));
    }
    
    public static function crearCampana(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $nombre=$rq->campana;
        $fechaInicio=$rq->fechaInicio;
        $fechaFin=$rq->fechaFin;
        $usuario=$rq->usuario;
        $total=$rq->total;
        $usu_logeado=auth()->user()->emp_cod;

        $tipo_cliente=$rq->tipo_cliente;
        $tipo_numeros=$rq->tipo_numeros;
        $ubicabilidad=implode(',',$rq->ubicabilidad);
        $respuesta=implode(',',$rq->respuesta);
        $capital_i=$rq->capital_i;
        $capital_f=$rq->capital_f;
        $deuda_i=$rq->deuda_i;
        $deuda_f=$rq->deuda_f;
        $importe_i=$rq->importe_i;
        $importe_f=$rq->importe_f;
        $score=implode(',',$rq->score);
        $entidad=implode(',',$rq->entidad);
        $zona=implode(',',$rq->zona);
        $prioridad=implode(',',$rq->prioridad);
        $tramo=implode(',',$rq->tramo);
        $gestion_dia=$rq->gestion_dia;
        // $inhCodigos=$rq->inhCodigos;
        $inhPaletas=$rq->inhibir_paletas;

        $detalle="";

        if($capital_i!='' && $capital_f!=''){
            $detalle.=" Capital: [$capital_i - $capital_f];";
        }

        if($deuda_i!='' && $deuda_f!=''){
            $detalle.=" Deuda: [$deuda_i - $deuda_f];";
        }

        if($importe_i!='' && $importe_f!=''){
            $detalle.=" Importe: [$importe_i - $importe_f];";
        }

        if($ubicabilidad!=''){
            $detalle.=" Ubicabilidad: ".str_replace("2","NO DISPONIBLE",str_replace("1","NO CONTACTO",str_replace("0","CONTACTO",$ubicabilidad))).";";
        }

        if($respuesta!=''){
            $detalle.=" Respuestas: $respuesta;";
        }

        if($score!=''){
            $detalle.=" Score: $score;";
        }

        if($entidad!=''){
            $detalle.=" Entidades: $entidad;";
        }

        if($prioridad!=''){
            $detalle.=" Prioridad: $prioridad;";
        }

        if($zona!=''){
            $detalle.=" Zonas: $zona;";
        }

        if($tramo!=''){
            $detalle.=" Tramo: $tramo;";
        }

        if($gestion_dia!=''){
            $detalle.=" Inhibir: Clientes Gestionados en el día;";
        }

        if($inhPaletas==1){
            $detalle.=" Inhibir: Paletas de Gestión;";
        }

        // if($codigos!=''){
        //     $detalle.=" Inhibir Códigos: $codigos;";
        // }

        return DB::connection('mysql')->insert("
                insert into creditoy_predictivo.predictivo (
                    car_id_FK,
                    pre_nom,
                    pre_detalle,
                    pre_total,
                    pre_fec_inicio,
                    pre_fec_fin,
                    pre_usuario,
                    emp_id_FK,
                    pre_gest_generadas,
                    pre_asignado,
                    pre_est,
                    fecha_add,
                    fecha_update
                )
                VALUES(:car,:nom,:det,:total,:fecInicio,:fecFin,:usu,:emp,0,0,0,:fec,:fec2)
        ",array("car"=>$cartera,"nom"=>$nombre,"det"=>$detalle,"total"=>$total,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin,"usu"=>$usuario,"emp"=>$usu_logeado,"fec"=>$fecha,"fec2"=>$fecha));
    }

    public static function IdCampana(Request $rq,$fecha){
        $cartera=$rq->cartera;
        $nombre=$rq->campana;        
        return DB::connection('mysql')->select(DB::raw("
            select 
                pre_id as id,
                pre_usuario as usuario
            from
                creditoy_predictivo.predictivo
            where
                car_id_FK=:car
                and fecha_add=:fec
                and pre_nom in (:nom)
            limit 1
        "),array("car"=>$cartera,"nom"=>$nombre,"fec"=>$fecha));
    }

    public static function asignar($idcampana,$usuario){
        DB::connection('mysql')->update("
            update cliente c
            inner join creditoy_predictivo.repositorio r on c.cli_cod=r.rep_codigo
            inner join creditoy_predictivo.predictivo p on r.pre_id_FK=p.pre_id 
            set emp_tel_id_FK=(select emp_id from empleado where emp_cod=:usu and emp_est=0 limit 1)
            where pre_id_FK=:id
            and cli_est=0
            and cli_pas=0
            and c.car_id_FK=p.car_id_FK
        ",array("id"=>$idcampana,"usu"=>$usuario));
        return "ok";
    }

    public static function validacionAsignacion($idcampana,$valor){
        DB::connection('mysql')->update("
            update creditoy_predictivo.predictivo 
            set pre_asignado=:val
            where pre_id=:id
        ",array("id"=>$idcampana,"val"=>$valor));
        return "ok";
    }

    public static function devolverAsignacion(Request $rq){
        $idcampana=$rq->idCampana;
        $opcion=$rq->opcion;
        $sql="";

        if($opcion=='pdps'){
            $sql="and (select count(*) 
                        from gestion_cliente 
                        where cli_id_FK=c.cli_id 
                        and res_id_FK in (1,43)
                        and ges_cli_fec between p.pre_fec_inicio and p.pre_fec_fin
                        and emp_id_FK=(select emp_id from empleado where emp_cod=p.pre_usuario and emp_est=0 limit 1)
                      )=0
        ";
        }

        if($opcion=='pdpstt'){
            $sql="and (select count(*) 
                    from gestion_cliente 
                    where cli_id_FK=c.cli_id 
                    and res_id_FK in (1,43,33)
                    and ges_cli_fec between p.pre_fec_inicio and p.pre_fec_fin
                    and emp_id_FK=(select emp_id from empleado where emp_cod=p.pre_usuario and emp_est=0 limit 1)
                )=0";
        }

        DB::connection('mysql')->update("
            update cliente c
            inner join creditoy_predictivo.repositorio r on c.cli_cod=r.rep_codigo
            inner join creditoy_predictivo.predictivo p on r.pre_id_FK=p.pre_id
            set emp_tel_id_FK=rep_asignacion
            where pre_id_FK=:id
            and cli_est=0
            and cli_pas=0
            and c.car_id_FK=p.car_id_FK
            $sql
        ",array("id"=>$idcampana));
        return "ok";
    }

    public static function AsignarPdpsTT(Request $rq){
        $idcampana=$rq->idCampana;
        $opcion=$rq->opcion;
        $respuesta="";

        if($opcion=='pdps'){
            $respuesta="(1,43)";
        }

        if($opcion=='pdpstt'){
            $respuesta="(1,43,33)";
        }
            DB::connection('mysql')->update("
                    update cliente c
                    inner join creditoy_predictivo.repositorio r on c.cli_cod=r.rep_codigo
                    inner join creditoy_predictivo.predictivo p on r.pre_id_FK=p.pre_id
                    inner join gestion_cliente g on g.cli_id_FK=c.cli_id
                    LEFT JOIN sub_empleado s on RIGHT(g.ges_cli_det,3)=s.emp_firma and s.emp_est=0
                    LEFT JOIN empleado e on s.encargado=e.emp_cod and e.emp_est=0
                    set emp_tel_id_FK=if(e.emp_id is null,rep_asignacion,e.emp_id)
                    where pre_id_FK=:id
                    and cli_est=0
                    and cli_pas=0
                    and c.car_id_FK=p.car_id_FK
                    and res_id_FK in $respuesta
                    and ges_cli_fec between p.pre_fec_inicio and p.pre_fec_fin
                    and g.emp_id_FK=(select emp_id from empleado where emp_cod=p.pre_usuario and emp_est=0 limit 1)
            ",array("id"=>$idcampana));
        
            return "ok";
    }

    public static function listaRepositorio($idcampana){
        return DB::connection('mysql')->select(DB::raw("
            select 
                rep_numero,
                rep_codigo,
                rep_nombre
            from
                creditoy_predictivo.repositorio 
            where pre_id_FK=:id
        "),array("id"=>$idcampana));
    }

    public static function listaCampanas(Request $rq){
        $cartera=$rq->cartera;
        $fechaInicio=$rq->fechaInicio;
        $usuario=$rq->usuario;
        $fecha=$rq->fecha;
        $parametros=array();
        $sql="";
        if($cartera!=""){
            $sql.=" and car_id_FK=:car";
            $parametros['car']=$cartera;
        }
        if($fechaInicio!=""){
            $sql.=" and date(pre_fec_inicio)=:fecIni";
            $parametros['fecIni']=$fechaInicio;
        }
        if($fecha!=""){
            $sql.=" and date(fecha_add)=:fec";
            $parametros['fec']=$fecha;
        }
        if($usuario!=""){
            $sql.=" and pre_usuario=:usu";
            $parametros['usu']=$usuario;
        }

        return DB::connection('mysql')->select(DB::raw("
            select 
                pre_id as id,
                car_nom as cartera,
                pre_nom as campana,
                pre_detalle as detalle,
                pre_total as total,
                concat(pre_fec_inicio,' - ',pre_fec_fin) as fecha_evento,
                concat(emp_cod,' - ',emp_nom) as usuario,
                pre_usuario as id_usuario,
                fecha_add as fecha_registro,
                pre_asignado as asignado,
                pre_gest_generadas AS gestiones,
                pre_fec_inicio as fecha_inicio,
                pre_fec_fin as fecha_fin
            from
                creditoy_predictivo.predictivo p
            INNER JOIN creditoy_cobranzas.cartera c on p.car_id_FK=c.car_id
            LEFT JOIN creditoy_cobranzas.empleado e on p.pre_usuario=e.emp_cod
            where
                pre_est=0
                $sql
        "),$parametros);
    }

    public static function eliminarCampana($idcampana){
        DB::connection('mysql')->update("
            update creditoy_predictivo.predictivo
            set pre_est=1,fecha_update=now()
            where pre_id=:id
        ",array("id"=>$idcampana));
        return "ok";
    }

    public static function datosGestiones($idCampana){
        return DB::connection('mysql')->select(DB::raw("
                    select 
                        count(rep_numero) as cantidadGestionada
                    from
                        creditoy_predictivo.predictivo p
                    INNER JOIN creditoy_predictivo.repositorio r ON p.pre_id=r.pre_id_FK
                    INNER JOIN creditoy_cobranzas.empleado e ON p.pre_usuario=e.emp_cod 
                    INNER JOIN creditoy_cobranzas.cliente c ON r.rep_codigo=c.cli_cod
                    where
                            rep_est=0
                    and pre_id=:id
                    and cli_est=0
                    and cli_pas=0
                    and c.car_id_FK=p.car_id_FK
                    and (
                        SELECT count(*) 
                        FROM creditoy_cobranzas.gestion_cliente g	
                        WHERE
                            cli_id_FK=c.cli_id
                        and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin 
                        and ges_cli_med=r.rep_numero_sic
                      and g.emp_id_FK=e.emp_id
                    )>0                    
        "),array("id"=>$idCampana));
    }

    public static function datosConResultados($idCampana){
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    count(*) as cantidad
                FROM
                (SELECT
                            idcliente,
                            emp_id,
                            2,
                            rspta,
                            pre_fec_fin,
                            numero,
                            detalle
                    FROM
                        (SELECT
                            cli_id as idcliente,
                            emp_id,
                            2,
                            case WHEN maxid is null or res_ubi=1 THEN 45
                                    WHEN res_ubi in (0,2) THEN 32
                            END as rspta,
                            pre_fec_fin,
                            case WHEN maxid is null or res_ubi=1 THEN rep_numero_sic
                                    WHEN res_ubi in (0,2) THEN ges_cli_med
                            END as numero,
                            'no contesta - PREDICTIVO' as detalle
                        FROM
                        (select 
                            cli_id,
                            emp_id,
                            pre_fec_fin,
                            rep_numero_sic,
                            (
                                    SELECT max(ges_cli_id) as max
                                    FROM creditoy_cobranzas.gestion_cliente g	
                                    WHERE
                                                    cli_id_FK=c.cli_id
                                    and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin 
                            ) as maxid
                        from
                                creditoy_predictivo.predictivo p
                        INNER JOIN creditoy_predictivo.repositorio r ON p.pre_id=r.pre_id_FK
                        INNER JOIN creditoy_cobranzas.empleado e ON p.pre_usuario=e.emp_cod 
                        INNER JOIN creditoy_cobranzas.cliente c ON r.rep_codigo=c.cli_cod
                        where
                                rep_est=0
                        and pre_id=:id
                        and cli_est=0
                        and cli_pas=0
                        and c.car_id_FK=p.car_id_FK
                        and rep_estado_llamada not in ('')
                        and (
                                SELECT count(*) 
                                FROM creditoy_cobranzas.gestion_cliente g	
                                WHERE
                                                cli_id_FK=c.cli_id
                                and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin 
                                and ges_cli_med=r.rep_numero_sic
                                and g.emp_id_FK=e.emp_id
                        )=0
                    )t
                    LEFT JOIN gestion_cliente g on t.maxid=g.ges_cli_id
                    left JOIN respuesta r on g.res_id_FK=r.res_id
                    )tt
                    GROUP BY idcliente,numero
                )ttt
    
        "),array("id"=>$idCampana));
    }

    public static function generarGestiones($idCampana){
        DB::connection('mysql')->insert("
                INSERT INTO gestion_cliente
                (
                    cli_id_FK,
                    emp_id_FK,
                    ges_cli_acc,
                    res_id_FK,
                    ges_cli_fec,
                    ges_cli_med,
                    ges_cli_det
                )
                SELECT
                    idcliente,
                    emp_id,
                    2,
                    rspta,
                    pre_fec_fin,
                    numero,
                    detalle
                FROM
                (SELECT
                    cli_id as idcliente,
                    emp_id,
                    2,
                    case WHEN maxid is null or res_ubi=1 THEN 45
                        WHEN res_ubi in (0,2) THEN 32
                    END as rspta,
                    pre_fec_fin,
                    case WHEN maxid is null or res_ubi=1 THEN rep_numero_sic
                        WHEN res_ubi in (0,2) THEN ges_cli_med
                    END as numero,
                    'no contesta - PREDICTIVO' as detalle
                FROM
                (select 
                    cli_id,
                    emp_id,
                    pre_fec_fin,
                    rep_numero_sic,
                    (
                        SELECT max(ges_cli_id) as max
                        FROM creditoy_cobranzas.gestion_cliente g	
                        WHERE
                                cli_id_FK=c.cli_id
                        and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin 
                    ) as maxid
                from
                    creditoy_predictivo.predictivo p
                INNER JOIN creditoy_predictivo.repositorio r ON p.pre_id=r.pre_id_FK
                INNER JOIN creditoy_cobranzas.empleado e ON p.pre_usuario=e.emp_cod 
                INNER JOIN creditoy_cobranzas.cliente c ON r.rep_codigo=c.cli_cod
                where
                    rep_est=0
                and pre_id=:id
                and cli_est=0
                and cli_pas=0
                and c.car_id_FK=p.car_id_FK
                and rep_estado_llamada not in ('')
                and (
                    SELECT count(*) 
                    FROM creditoy_cobranzas.gestion_cliente g	
                    WHERE
                            cli_id_FK=c.cli_id
                    and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin 
                    and ges_cli_med=r.rep_numero_sic
                    and g.emp_id_FK=e.emp_id
                )=0
              )t
            LEFT JOIN gestion_cliente g on t.maxid=g.ges_cli_id
            left JOIN respuesta r on g.res_id_FK=r.res_id
            )tt
            GROUP BY idcliente,numero
        ",array("id"=>$idCampana));
        return "ok";
    }

    public static function actualizarCantidad($idCampana,$cantidad){
        DB::connection('mysql')->update("
            update creditoy_predictivo.predictivo
            set pre_gest_generadas=:cant
            where pre_id=:id
        ",array("id"=>$idCampana,"cant"=>$cantidad));
        return "ok";
    }

    public static function actualizarFechaCampana(Request $rq){
        $idcampana=$rq->idCampana;
        $fechaInicio=str_replace('T',' ',$rq->fechaInicio);
        $fechaFin=str_replace('T',' ',$rq->fechaFin);
        
        DB::connection('mysql')->update("
            update creditoy_predictivo.predictivo
            set pre_fec_inicio=:fecInicio,pre_fec_fin=:fecFin 
            where pre_id=:id
        ",array("id"=>$idcampana,"fecInicio"=>$fechaInicio,"fecFin"=>$fechaFin));
        return "ok";
    }

    public static function reporteCampanaGestor($idCampana){
        return DB::connection('mysql')->select(DB::raw("
                SELECT
                    CONCAT(emp_cod,' - ',emp_nom) as gestor,
                    sum(gestion) as total_llamadas,
                    count(DISTINCT cli_id) as total_clientes,
                    sum(contacto) as total_contactos,
                    format((sum(contacto)/sum(gestion))*100,2) as contactabilidad,
                    sum(cant_pdp) as cant_pdp,
                    sum(monto_pdp) as monto_pdp
                FROM
                (SELECT
                    cli_id,
                    emp_tel_id_FK as idEmpleado,
                    1 as gestion,
                    if(res_ubi=0,1,0) as contacto,
                    if(res_id in (1,43),1,0) as cant_pdp,
                    if(res_id in (1,43),ges_cli_com_can,0) as monto_pdp
                FROM
                    creditoy_predictivo.repositorio r
                INNER JOIN creditoy_predictivo.predictivo p on r.pre_id_FK=p.pre_id
                INNER JOIN creditoy_cobranzas.cliente c on r.rep_codigo=c.cli_cod
                INNER JOIN creditoy_cobranzas.gestion_cliente g ON c.cli_id=g.cli_id_FK
                INNER JOIN creditoy_cobranzas.empleado e ON p.pre_usuario=e.emp_cod
                INNER JOIN creditoy_cobranzas.respuesta re on g.res_id_FK=re.res_id
                WHERE
                    pre_id_FK=:id
                    and cli_est=0
                    and cli_pas=0
                    and c.car_id_FK=p.car_id_FK
                    and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin
                    and g.emp_id_FK=e.emp_id
                    and emp_est=0
                    and emp_tel_id_FK not in (e.emp_id)
                )t
                LEFT JOIN empleado ee on t.idEmpleado=ee.emp_id
                WHERE emp_est=0
                GROUP BY idEmpleado
        "),array("id"=>$idCampana));
    }

    public static function reporteCampanaPaletas($idCampana){
        return DB::connection('mysql')->select(DB::raw("
                    SELECT
                        IF(ubicabilidad IS NULL,'INTENTOS MARCADOR',ubicabilidad) as ubicabilidad,
                        respuesta,
                        if(respuesta='Clientes con Intento de Marcado',sum(intento_llamada),sum(llamada)) as clientes
                    from
                    (
                    SELECT
                        IF(ubicabilidad IS NULL,'INTENTOS MARCADOR',ubicabilidad) as ubicabilidad,
                        codigo,
                        respuesta,
                        PRIORIDAD,
                        if(PRIORIDAD=32,1,0) as intento_llamada,
                        if(PRIORIDAD<>32,1,0) as llamada,
                        @ac:=CASE WHEN @rng=codigo THEN @ac + 1  ELSE 1 END AS AC,
                        @rng:=codigo
                    FROM
                        (SELECT
                                cli_cod as codigo,
                                if(res_des is null,'Clientes con Intento de Marcado',res_des) as respuesta,
                                CASE when res_ubi=0 then 'CONTACTO'
                                                when res_ubi=1 then 'NO CONTACTO'
                                                when res_ubi=2 then 'NO DISPONIBLE'
                                END as ubicabilidad,
                                CASE
                                    when g.res_id_FK=38 then 1
                                    when g.res_id_FK=41 then 2
                                    when g.res_id_FK=2 then 3
                                    when g.res_id_FK=1 then 4
                                    when g.res_id_FK=43 then 5
                                    when g.res_id_FK=37 then 6
                                    when g.res_id_FK=33 then 7
                                    when g.res_id_FK=36 then 8
                                    when g.res_id_FK=10 then 9
                                    when g.res_id_FK=8 then 10
                                    when g.res_id_FK=7 then 11
                                    when g.res_id_FK=39 then 12
                                    when g.res_id_FK=22 then 13
                                    when g.res_id_FK=6 then 14
                                    when g.res_id_FK=34 then 15
                                    when g.res_id_FK=35 then 16
                                    when g.res_id_FK=17 then 17
                                    when g.res_id_FK=21 then 18
                                    when g.res_id_FK=32 then 19
                                    when g.res_id_FK=19 then 20
                                    when g.res_id_FK=26 then 21
                                    when g.res_id_FK=27 then 22
                                    when g.res_id_FK=13 then 23
                                    when g.res_id_FK=12 then 24
                                    when g.res_id_FK=15 then 25
                                    when g.res_id_FK=16 then 26
                                    when g.res_id_FK=14 then 27
                                    when g.res_id_FK=45 then 28
                                    when g.res_id_FK=44 then 29
                                    when g.res_id_FK=4 then 30
                                    when g.res_id_FK=11 then 31
                                    else 32
                                END AS PRIORIDAD,
                                res_id_FK
                        FROM
                                        creditoy_predictivo.repositorio r
                        INNER JOIN creditoy_predictivo.predictivo p on r.pre_id_FK=p.pre_id
                        INNER JOIN creditoy_cobranzas.cliente c on r.rep_codigo=c.cli_cod
                        LEFT JOIN creditoy_cobranzas.empleado e ON p.pre_usuario=e.emp_cod and emp_est=0 
                        LEFT JOIN creditoy_cobranzas.gestion_cliente g ON c.cli_id=g.cli_id_FK and ges_cli_fec BETWEEN p.pre_fec_inicio and p.pre_fec_fin and g.emp_id_FK=e.emp_id and rep_numero_sic=ges_cli_med
                        LEFT JOIN creditoy_cobranzas.respuesta re on g.res_id_FK=re.res_id
                        WHERE
                            pre_id_FK=:id
                            and cli_est=0
                            and cli_pas=0
                            and c.car_id_FK=p.car_id_FK
                            and rep_estado_llamada not in ('')
                        order by codigo,PRIORIDAD asc
                        )t		
                        CROSS JOIN (SELECT @ac:= 0) AS dummy
                        CROSS JOIN (
                            SELECT
                                @rng := rep_codigo
                            FROM
                                creditoy_predictivo.repositorio
                            GROUP BY
                                rep_codigo
                            LIMIT 1
                        ) AS dummy2
                    )tt
                    where
                        AC=1
                    GROUP BY respuesta
                    ORDER BY ubicabilidad    
        "),array("id"=>$idCampana));
    }

    public static function actualizarResultados($idCampana,$resultado,$numero,$codigo){
        DB::connection('mysql')->update("
            update creditoy_predictivo.repositorio
            set rep_estado_llamada=:res
            where pre_id_FK=:id
            and rep_numero=:num
            and rep_codigo=:cod
        ",array("id"=>$idCampana,"res"=>$resultado,"num"=>$numero,"cod"=>$codigo));
    }

    public static function crearIvrCodigo(Request $rq){
        $codigo=$rq->codigo;
        return DB::select(DB::raw("

        "));
    }
}


