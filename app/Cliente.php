<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $connection = 'mysql';

    public static function listarClientes(Request $rq){
        $codigo=$rq->codigo;
        $dni=$rq->dni;
        $nombre=$rq->nombre;
        $telefono=$rq->telefono;
        $tramo=$rq->tramo;
        $respuesta=$rq->respuesta;
        $fec_desde=$rq->fec_desde;
        $fec_hasta=$rq->fec_hasta;
        $ordenar=$rq->ordenar;
        $camp=$rq->camp;
        $deuda=$rq->deuda;
        $capital=$rq->capital;
        $importe=$rq->importe;
        $sueldo=$rq->sueldo;
        $entidades=$rq->entidades;
        $score=$rq->score;
        $motivo=$rq->motivo;
        $oficina=$rq->oficina;
        $descuento=$rq->descuento;
        $prioridad=$rq->prioridad;
        $numproducto=$rq->numproducto;
        $carteraBusqueda=$rq->cartera;
        $tipo=$rq->tipo;
        $respuesta_gestion=$rq->respuesta_gestion;
        $fecha_gestion_inicio=$rq->fecha_gestion_inicio;
        $fecha_gestion_fin=$rq->fecha_gestion_fin;
        $motivo_gestion=$rq->motivo_gestion;
        //dd($camp);
        $idEmpleado=auth()->user()->emp_id;
        $cartera=session()->get('datos')->idcartera;
        if($cartera==''){
            $cartera=0;
        }
        $acceso=auth()->user()->emp_tip_acc;
        if($camp!= null){
            $fec_actual=Carbon::now();
            $query_campana=DB::connection('mysql')->select(DB::raw("
                                select clientes,id_cartera
                                from indicadores.plan
                                WHERE id_cartera in ($cartera)
                                and fecha_i<=:fec1 and fecha_f >= :fec2
                            "),array("fec1"=>$fec_actual,"fec2"=>$fec_actual));
            //dd($query_campana);
            $cadena="";
            $carteraPlan="";
            if($query_campana!=[]){
                foreach($query_campana as $q){
                    $cadena.=",". $q->clientes;
                    $carteraPlan.=",". $q->id_cartera;
                }
                $cadena_cli=substr($cadena,1,strlen($cadena));
                $array=explode(',',$cadena_cli);
                $cantidad_cli=count($array);
            }else{
                $cantidad_cli=-1;
            }
        }else{
            $cantidad_cli=0;
        }

        //tipo=2 expotar, tipo=1 mostrar lista
        if($tipo==1){
            $filtro="
                SELECT 
                    cli_id as id,
                    cli_cod as codigo,
                    cli_nom as nombre,
                    cli_num_doc as dni,
                    det_cli_deu_cap as capital,
                    det_cli_deu_mor as deuda,
                    det_cli_imp_can as importe,
                    det_cli_pro as producto,
                    if(ges_cli_med is null,'-',ges_cli_med) as telefono,
                    if(res_id_FK is null,'Sin Gestión',res_des) as ult_resp,
                    ges_cli_fec as fecha_ges,
                    date(ges_cli_fec) as fecha_gestion,
                    cli_ema as email,
                    concat(emp_cod,' - ',emp_nom) as gestor,
                    car_nom as cartera
                FROM 
                    cliente c
                inner JOIN detalle_cliente dc ON c.cli_id = dc.cli_id_FK
                inner join cartera ca ON c.car_id_FK=ca.car_id
                LEFT join empleado e on c.emp_tel_id_FK=e.emp_id
                left JOIN gestion_cliente g ON c.ges_cli_tel_id_FK=g.ges_cli_id
                left JOIN respuesta as r on r.res_id=g.res_id_FK
            ";
        }else{
            $filtro="
                SELECT 
                    cli_cod as CODIGO,
                    cli_nom as NOMBRE,
                    cli_tip_doc as 'TIPO DOC.',
                    cli_num_doc as 'NUM. DOC',
                    format(det_cli_deu_cap,2) as 'DEUDA CAPITAL (S/.)',
                    format(det_cli_deu_mor,2) as 'SALDO MOROSO TOTAL (S/.)',
                    format(det_cli_imp_can,2) as 'IMP. CANC. TOTAL (S/.)',
                    if(ges_cli_med is null,'-',ges_cli_med) as 'TELÉFONO',
                    det_cli_num_doc as 'NUM. DOC. PRODUCTO',
                    det_cli_pro as PRODUCTO,
                    det_cli_tra as TRAMO,
                    if(res_id_FK is null,'Sin Gestión',res_des) as RESPUESTA,
                    ges_cli_det as 'ULTIMA GESTIÓN',
                    date(ges_cli_com_fec) as 'FECHA COMPROMISO',
                    cli_fec_hor as 'ACTUALIZADO',
                    concat(loc_cod,' - ',loc_nom) as OFICINA,
                    concat(emp_cod,' - ',emp_nom) as 'GESTOR TELEFÓNICO',
                    car_nom as CARTERA
                FROM 
                    cliente as c
                inner JOIN detalle_cliente dc ON c.cli_id = dc.cli_id_FK
                inner join cartera ca ON c.car_id_FK=ca.car_id
                LEFT join empleado e on c.emp_tel_id_FK=e.emp_id
                left JOIN gestion_cliente g ON c.ges_cli_tel_id_FK=g.ges_cli_id
                left JOIN respuesta as r on r.res_id=g.res_id_FK
                LEFT JOIN local l on c.loc_id_FK=l.loc_id
            ";
        }
        
        $sql="
            where 
                cli_est=0 and cli_pas=0 
                and det_cli_est=0 and det_cli_pas=0
                and det_cli_deu_mor = (SELECT MAX(det_cli_deu_mor) FROM detalle_cliente WHERE det_cli_est = 0 AND det_cli_pas = 0 AND cli_id_FK = c.cli_id)
                and car_est=0 and car_pas=0
        ";

        $parametros_busquedas=array();

        if($acceso==2 || $acceso==8){
            $sql.=" and emp_tel_id_FK=:emp ";
            $parametros_busquedas["emp"]=$idEmpleado;
        }
        /*else{
            if($cartera!=0){
                $sql.=" and car_id_FK in ($cartera)";
            }
        }*/

        if($codigo!= null){
            $sql = $sql." and cli_cod=:cod ";
            $parametros_busquedas["cod"]=$codigo;
        }
        
        if($carteraBusqueda!= null){
            $sql = $sql." and car_id_FK=:car ";
            $parametros_busquedas["car"]=$carteraBusqueda;
        }

        if($dni!= null){
            $sql = $sql." and cli_num_doc=:dni ";
            $parametros_busquedas["dni"]=$dni;
        }

        if($nombre!= null){
            $nom = explode(' ',$nombre);
            for($i=0; $i < count($nom); $i++){
                $sql .= " AND cli_nom like (:nom_$i) ";
                $parametros_busquedas["nom_$i"]="%".$nom[$i]."%";
            }
            // $sql = $sql." and cli_nom like '%$nombre%' ";
        }
        
        if($numproducto!= null){
            $sql = $sql." and cli_id in (SELECT cli_id_FK FROM detalle_cliente WHERE det_cli_num_doc in (:producto) and det_cli_est = 0 AND det_cli_pas = 0) ";
            $parametros_busquedas["producto"]=$numproducto;
        }

        if($telefono!= null){
            $sql = $sql." and cli_id in (select cli_id_FK from cliente_telefono where cli_tel_est=0 and cli_tel_pas=0 and cli_tel_tel like (:tel)) ";
            $parametros_busquedas["tel"]=$telefono."%";
        }

        if($tramo!= null){
            $sql = $sql." and det_cli_tra like (:tramo) ";
            $parametros_busquedas["tramo"]="%".$tramo."%";

        }

        // ultima respuesta
        if($respuesta!= null){
            if($respuesta==0){
                $sql = $sql." and res_id_FK IS NULL ";
            }
            if($respuesta==-1){
                $sql = $sql." and (res_id_FK is null or date_format(ges_cli_fec,'%Y%m')<date_format(now(),'%Y%m'))";
            }
            if($respuesta<>0 && $respuesta<>-1){
                $sql = $sql." and res_id_FK=:res ";
                $parametros_busquedas["res"]=$respuesta;
            }
        }

        if($fec_desde!= "undefined-undefined-" && $fec_hasta!= "undefined-undefined-"){
            $sql = $sql." and cli_id in (select cli_id_FK as id from compromiso_cliente where date_format(com_cli_fec_pag,'%Y-%m-%d') between :fecInicio and :fecFin)
                          and res_id_FK not in (2,38,37) ";
            $parametros_busquedas["fecInicio"]=$fec_desde;
            $parametros_busquedas["fecFin"]=$fec_hasta;
        }
        
        // respuesta y fecha de geestion
        if($respuesta_gestion!= null && $fecha_gestion_fin!= "undefined-undefined-" && $fecha_gestion_inicio!= "undefined-undefined-"){
            $sqlMotivo="";
            if($respuesta_gestion==33 && $motivo_gestion!=''){
                $sqlMotivo=" and mot_id_FK=:motivo ";
                $parametros_busquedas["motivo"]=$motivo_gestion;
            }
            $sql .=" and (select count(*) from gestion_cliente where cli_id_FK=cli_id and res_id_FK=:res_ges and date(ges_cli_fec) between :fecInicioGes and :fecFinGes $sqlMotivo)>0 ";
            $parametros_busquedas["res_ges"]=$respuesta_gestion;
            $parametros_busquedas["fecInicioGes"]=$fecha_gestion_inicio;
            $parametros_busquedas["fecFinGes"]=$fecha_gestion_fin;
        }

        if( $cantidad_cli>0){
            $sql = $sql." and cli_cod in ($cadena_cli) and car_id_FK in (".substr($carteraPlan,1,strlen($carteraPlan)).") ";
        } else if($cantidad_cli == 0 ) {
            $sql = $sql . "and 1 = 1" ;
        } else if ( $cantidad_cli == - 1 ) {
            $sql = $sql . "and cli_cod in (0)" ;
        }

        if( $deuda =='1'){
            $sql = $sql." and det_cli_deu_mor <= 500 ";
        }else if($deuda =='2'){
            $sql = $sql." and det_cli_deu_mor > 500 and det_cli_deu_mor <= 1000 ";
        }else if($deuda =='3'){
            $sql = $sql." and det_cli_deu_mor > 1000 and det_cli_deu_mor <= 3000 ";
        }else if($deuda =='4'){
            $sql = $sql." and det_cli_deu_mor > 3000 ";
        }

        
        if( $capital =='1'){
            $sql = $sql." and det_cli_deu_cap <= 500 ";
        }else if($capital =='2'){
            $sql = $sql." and det_cli_deu_cap > 500 and det_cli_deu_cap <= 1000 ";
        }else if($capital =='3'){
            $sql = $sql." and det_cli_deu_cap > 1000 and det_cli_deu_cap <= 3000 ";
        }else if($capital =='4'){
            $sql = $sql." and det_cli_deu_cap > 3000 ";
        }

        if( $importe =='1'){
            $sql = $sql." and det_cli_imp_can <= 500 ";
        }else if($importe =='2'){
            $sql = $sql." and det_cli_imp_can > 500 and det_cli_imp_can <= 1000 ";
        }else if($importe =='3'){
            $sql = $sql." and det_cli_imp_can > 1000 and det_cli_imp_can <= 3000 ";
        }else if($importe =='4'){
            $sql = $sql." and det_cli_imp_can > 3000 ";
        }

        if( $sueldo =='1'){
            $filtro= $filtro." left join cliente_sueldo as s on c.cli_id=s.cli_id_FK";
            $sql = $sql." and cli_suel_can <= 500 ";
        }else if($sueldo =='2'){
            $filtro= $filtro." left join cliente_sueldo as s on c.cli_id=s.cli_id_FK";
            $sql = $sql." and cli_suel_can > 500 and cli_suel_can <= 1000 ";
        }else if($sueldo =='3'){
            $filtro= $filtro." left join cliente_sueldo as s on c.cli_id=s.cli_id_FK";
            $sql = $sql." and cli_suel_can > 1000 and cli_suel_can <= 3000 ";
        }else if($sueldo =='4'){
            $filtro= $filtro." left join cliente_sueldo as s on c.cli_id=s.cli_id_FK";
            $sql = $sql." and cli_suel_can > 3000 ";
        }

        if($motivo!=null){
            $sql = $sql." and mot_id_FK=:motivo ";
            $parametros_busquedas["motivo"]=$motivo;
        }

        if( $entidades !=null || $score !=null){
            $filtro= $filtro." left join cliente_infAdic as i on c.cli_id=i.cli_id_FK";
            if($entidades !=null){
                $sql = $sql." and cli_inf_entidades = :entidad ";
                $parametros_busquedas["entidad"]=$entidades;
            }
            if( $score !=null){
                $sql = $sql." and cli_inf_score = :score ";
                $parametros_busquedas["score"]=$score;
            }          
        }

        if($oficina!= null){
            $sql = $sql." and c.loc_id_FK = :ofic ";
            $parametros_busquedas["ofic"]=$oficina;
        }

        if($descuento!= null){
            $dto="";
            if($carteraBusqueda==9){
                $dto=" AND det_cli_dscto like (:dscto) ";
                $parametros_busquedas["dscto"]=$descuento."%";
            }else{
                $dto=" AND det_cli_dscto_adc like (:dscto) ";
                $parametros_busquedas["dscto"]=$descuento."%";
            }
            $sql = $sql." and (SELECT count(cli_id_FK) FROM detalle_cliente WHERE cli_id_FK = c.cli_id AND det_cli_est = 0 AND det_cli_pas = 0 $dto)>0";
        }

        if($prioridad!= null){
            $sql = $sql." and (SELECT count(cli_id_FK) FROM detalle_cliente WHERE cli_id_FK = c.cli_id AND det_cli_est = 0 AND det_cli_pas = 0 AND det_cli_estado in (:prioridad))>0";
            $parametros_busquedas["prioridad"]=$prioridad;
        }
        

        $sql= $sql." GROUP BY cli_id";

        if($ordenar!= null){
            if($ordenar=='1'){
                $sql = $sql." order by det_cli_deu_cap desc,ges_cli_fec asc ";
            }
            if($ordenar=='2'){
                $sql = $sql." order by det_cli_deu_mor desc,ges_cli_fec asc ";
            }
            if($ordenar=='3'){
                $sql = $sql." order by det_cli_imp_can desc,ges_cli_fec asc";
            }
            if($ordenar=='4'){
                $sql = $sql." order by det_cli_deu_cap asc,ges_cli_fec asc ";
            }
            if($ordenar=='5'){
                $sql = $sql." order by det_cli_deu_mor asc,ges_cli_fec asc ";
            }
            if($ordenar=='6'){
                $sql = $sql." order by det_cli_imp_can asc,ges_cli_fec asc";
            }
        }else{
            $sql = $sql." order by ges_cli_fec asc,det_cli_deu_mor desc";
        }

        $query=DB::connection('mysql')->select(DB::raw($filtro." ".$sql),$parametros_busquedas);
        return $query;
    }

    public static function datosMes(){
        $idEmpleado=auth()->user()->emp_id;
        $cartera=session()->get('datos')->idcartera;
        /*$sql="
            SELECT
                cli_id,
                if(emp_meta is null,0,emp_meta) as meta,
                sum(monto_conf) as monto_conf,
                max(fecha_conf) as fecha_conf,
                sum(if(gestion is null,0,monto_pago)) as monto_pago,
                max(if(gestion is null,0,fecha_pago)) as fecha_pago,
                if(sum(if(gestion is null,0,monto_pago))=0,sum(monto_conf),sum(if(gestion is null,0,monto_pago))) as recupero,
                if(sum(if(gestion is null,0,monto_pago))=0,max(fecha_conf),max(if(gestion is null,0,fecha_pago))) as fecha_recupero,
                sum(monto_pdp) as monto_pdp,
                sum(pendiente) as pdp_pendiente,
                sum(caidos) as pdp_caidos,
                sum(cumplido) as pdp_cumplido,
                format((sum(cumplido)/(sum(cumplido)+sum(pendiente)))*100,2) as efectividad,
                format((sum(gestion)/count(*))*100,2) as cobertura
            FROM
                cliente c
            LEFT JOIN (
                SELECT
                    g.cli_id_FK,
                    sum(ges_cli_conf_can) as monto_conf,
                    day(max(ges_cli_conf_fec)) as fecha_conf,
                    sum(ges_cli_com_can) as monto_pdp,
                    sum(if(com_cli_est <> 0,com_cli_can,0)) as cumplido,
                    sum(if( com_cli_est=0 and DATEDIFF(DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(com_cli_fec_pag,'%Y-%m-%d')) <= 0,com_cli_can,0)) as pendiente,
                    sum(if( com_cli_est=0 and DATEDIFF(DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(com_cli_fec_pag,'%Y-%m-%d')) > 0,com_cli_can,0)) as caidos,
                    g.emp_id_FK,
                    1 as gestion
                FROM
                    gestion_cliente g
                LEFT JOIN compromiso_cliente cc ON cc.cli_id_FK=g.cli_id_FK and cc.ges_cli_id_FK=g.ges_cli_id and date_format(com_cli_fec_pag,'%Y-%m') = date_format(now(),'%Y-%m')
                WHERE
                    date_format(ges_cli_fec,'%Y-%m') = date_format(now(),'%Y-%m')
                    and g.emp_id_FK=:emp2
                    and ges_cli_acc IN (1, 2)
                GROUP BY cli_id_FK
            )g ON c.cli_id = g.cli_id_FK
            INNER JOIN empleado e on c.emp_tel_id_FK=e.emp_id
            LEFT JOIN (
                SELECT
                    pag_cli_cod,
                    sum(pag_cli_mon) as monto_pago,
                    day(max(pag_cli_fec)) as fecha_pago
                FROM
                    pago_cliente_2
                WHERE
                    date_format(pag_cli_fec, '%Y-%m') = date_format(now(), '%Y-%m')
                AND car_id_FK = :car
                GROUP BY pag_cli_cod
            ) p ON c.cli_cod = p.pag_cli_cod 
            WHERE
                cli_est=0
            and cli_pas=0
            and emp_tel_id_FK=:emp
        ";*/
        $sql="
            SELECT
                cli_id,
                if(emp_meta is null,0,emp_meta) as meta,
                sum(monto_conf) as monto_conf,
                max(fecha_conf) as fecha_conf,
                (   SELECT
                        if(sum(pago_cli_mon) is null,0,sum(pago_cli_mon)) as pago
                    FROM
                        indicadores.pago
                    WHERE car_id=c.car_id_FK
                    and DATE_FORMAT(pago_cli_fec,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m')
                    AND pago_gestor=e.emp_cod
                ) as recupero,
                (   SELECT
                        if(day(max(pago_cli_fec)) is null,0,day(max(pago_cli_fec)))
                    FROM
                        indicadores.pago
                    WHERE car_id=c.car_id_FK
                    and DATE_FORMAT(pago_cli_fec,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m')
                    AND pago_gestor=e.emp_cod
                ) as fecha_recupero,
                sum(monto_pdp) as monto_pdp,
                sum(pendiente) as pdp_pendiente,
                sum(caidos) as pdp_caidos,
                sum(cumplido) as pdp_cumplido,
                format((sum(cumplido)/(sum(cumplido)+sum(pendiente)))*100,2) as efectividad,
                format((sum(gestion)/count(*))*100,2) as cobertura
            FROM
                cliente c
            LEFT JOIN (
                SELECT
                    g.cli_id_FK,
                    sum(ges_cli_conf_can) as monto_conf,
                    day(max(ges_cli_conf_fec)) as fecha_conf,
                    sum(ges_cli_com_can) as monto_pdp,
                    sum(if(com_cli_est <> 0,com_cli_can,0)) as cumplido,
                    sum(if( com_cli_est=0 and DATEDIFF(DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(com_cli_fec_pag,'%Y-%m-%d')) <= 0,com_cli_can,0)) as pendiente,
                    sum(if( com_cli_est=0 and DATEDIFF(DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(com_cli_fec_pag,'%Y-%m-%d')) > 0,com_cli_can,0)) as caidos,
                    g.emp_id_FK,
                    1 as gestion
                FROM
                    gestion_cliente g
                LEFT JOIN compromiso_cliente cc ON cc.cli_id_FK=g.cli_id_FK and cc.ges_cli_id_FK=g.ges_cli_id and date_format(com_cli_fec_pag,'%Y-%m') = date_format(now(),'%Y-%m')
                WHERE
                    date_format(ges_cli_fec,'%Y-%m') = date_format(now(),'%Y-%m')
                    and g.emp_id_FK=:emp2
                    and ges_cli_acc IN (1, 2)
                GROUP BY cli_id_FK
            )g ON c.cli_id = g.cli_id_FK
            INNER JOIN empleado e on c.emp_tel_id_FK=e.emp_id
            WHERE
                cli_est=0
            and cli_pas=0
            and emp_tel_id_FK=:emp
        ";
        $datos=DB::connection('mysql')->select(DB::raw($sql),array("emp"=>$idEmpleado,"emp2"=>$idEmpleado));
        return $datos;
        // return response()->json(['metas' => $metas, 'datos' => $datos, 'pdp' => $pdp , 'pagos' => $pagos]);
        //return $query;
    }

    public static function datosEstandar(Request $rq){
        $firma=$rq->firma;
        $sql="
            SELECT 
                count(cli_id_FK) as gestiones,
                sum(contacto) as contactos,
                format((sum(contacto)/count(cli_id_FK))*100,0) as contactabilidad, 
                sum(pdp) as pdps,
                sum(monto_pdp) as monto_pdps,
                sum(conf) as confs,
                sum(monto_conf) as monto_confs
            FROM
                (SELECT
                    cli_id_FK,
                    if(res_ubi=0,1,0) as contacto,
                    if(res_id in (1,43),1,0) as pdp,
                    if(res_id in (1,43),ges_cli_com_can,0) as monto_pdp,
                    if(res_id in (2),1,0) as conf,
                    if(res_id in (2),ges_cli_conf_can,0) as monto_conf
                FROM
                    cliente c
                INNER JOIN gestion_cliente g ON g.cli_id_FK=c.cli_id
                INNER JOIN respuesta r ON g.res_id_FK=r.res_id
                WHERE
                    cli_est=0
                and cli_pas=0
                and date(ges_cli_fec) = date(NOW())
                AND RIGHT (rtrim(ges_cli_det), 4) LIKE ('%$firma')
                )b                   
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }

    public static function estadosCampana(Request $rq){

        $sql_cartera="
                select car_id_FK as idcartera from cliente where cli_est=0 and cli_pas=0 and emp_tel_id_FK=2531 LIMIT 1
            ";
        $query_cartera=DB::connection('mysql')->select(DB::raw($sql_cartera));
        foreach($query_cartera as $q){
            $car_id=$q->idcartera;
        }
        $cartera=$car_id;

        //$fec_actual=date("Y-m-d H:i:00");
        $fec_actual='2020-07-25 16:04:18';

        $sql="
            select 
            id_cartera,nombre_camp,fecha_i,fecha_f,
            DATE_FORMAT(fecha_i, '%d/%m') as dia,TIME_FORMAT(fecha_i, '%H:%i %p') as hora,
            (case 
                WHEN fecha_i > '$fec_actual' THEN 'PC'
                WHEN fecha_i = '$fec_actual' or (fecha_i < '$fec_actual' and fecha_f >= '$fec_actual') THEN 'CE'
                WHEN fecha_i < '$fec_actual' or fecha_f < '$fec_actual' THEN 'CC'
            end) as estado
            from indicadores.campana 
            where id_cartera=$cartera
                and '$fec_actual' BETWEEN fecha_i and fecha_f
            ORDER BY fecha_i asc
            limit 1
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql));
        return $query;
    }


    public static function infoCliente($id){
        $sql="
            select 
                    cli_cod,
                    cli_nom as nombre,
                    cli_num_doc as doc,
                    cli_inf_entidades as entidades,
                    cli_inf_score as score,
                    cli_dir_dir as direccion,
                    cli_dir_dis as distrito,
                    cli_dir_pro as provincia,
                    cli_dir_dep as departamento,
                    cli_suel_emp as laboral,
                    cli_suel_can as sueldo,
                    cli_ema as email
            from
                cliente c
            left join cliente_infAdic i on c.cli_id=i.cli_id_FK
            left join cliente_direccion_2 d on c.cli_id=d.cli_id_FK
            left join cliente_sueldo s on c.cli_id=s.cli_id_FK
            where c.cli_id = :id
            limit 1
        ";
        $query=DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
 
        return $query;
    }
    
    public static function historicoGestiones($id){
        $sql="
            SELECT
                emp_id_FK,
                ges_cli_acc,
                emp_tip_acc as tipo,
                DATE_FORMAT(ges_cli_fec,'%Y-%m-%d') as fecha,
                DATE_FORMAT(ges_cli_fec,'%H:%i:%s') as hora,
                (CASE
                    WHEN emp_tip_acc =1 THEN 'ADMINISTRADOR'
                    WHEN emp_tip_acc =2 THEN 'G. TELEFONICO'
                    WHEN emp_tip_acc =3 THEN 'G. DOMICILIARIO'
                    WHEN emp_tip_acc =4 THEN 'DIGITADOR'
                    WHEN emp_tip_acc =5 THEN 'SUPERVISOR'
                end) as perfil,
                ges_cli_med as medio,
                (case
                    WHEN res_ubi=0 THEN 'C'
                    WHEN res_ubi=1 THEN 'NC'
                    WHEN res_ubi=2 THEN 'ND'
                end) as ubic,
                res_des as respuesta,
                ges_cli_det as detalle,
                (case when res_id_FK in (1,43) then ges_cli_com_fec
                      when res_id_FK in (2) then ges_cli_conf_fec
                 end)as fecha_pdp,
                 (case when res_id_FK in (1,43) then ges_cli_com_can
                      when res_id_FK in (2) then ges_cli_conf_can
                 end)as monto_pdp,
                if(res_id_FK in (1,43,2),1,0) as tolltip,
                concat(emp_cod,' - ',emp_nom) as empleado
            FROM 
                gestion_cliente as gc
            INNER JOIN 
                respuesta as r on gc.res_id_FK=r.res_id
            INNER JOIN
                empleado as e on gc.emp_id_FK=e.emp_id
            WHERE 
                cli_id_FK=:id
                AND date_format(ges_cli_fec,'%Y-%m') BETWEEN date_format(now()- INTERVAL 12 MONTH, '%Y-%m') and date_format(now(), '%Y-%m')
                and ges_cli_est=0
                and ges_cli_acc in (1,2)
            ORDER BY ges_cli_fec DESC
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
        return $query;
    }

    public static function infoDeuda($id){
        $sql="
            SELECT
                cuenta,
                tipo_cuenta,
                tarjeta,
                producto,
                fecha_deuda,
                dias,
                tramo,
                prioridad,
                moneda,
                capital,
                saldo,
                deuda,
                dscto,
                importe_dscto,
                importe,
                dscto_adc,
                importe_adc,
                (case 
                    WHEN dscto_ant IS NULL THEN '-2'
                    WHEN CAST(dscto AS UNSIGNED)=0 THEN '-3'
                    WHEN CAST(dscto AS UNSIGNED) > CAST(dscto_ant AS UNSIGNED) THEN '1'
                    WHEN CAST(dscto AS UNSIGNED) < CAST(dscto_ant AS UNSIGNED) THEN '-1'
                    WHEN CAST(dscto AS UNSIGNED) = CAST(dscto_ant AS UNSIGNED) THEN '0'
                END) AS indicador_dscto
            FROM
            (SELECT 
                    det_cli_num_doc as cuenta,
                    det_cli_tip_doc as tipo_cuenta,
                    det_cli_cyber_mn as tarjeta,
                    det_cli_pro as producto,
                    det_cli_fec_deu as fecha_deuda,
                    det_cli_dia_atr as dias,
                    det_cli_tra as tramo,
                    det_cli_estado as prioridad,
                    (case 
                            WHEN det_cli_mon =1 THEN 'SOLES'
                            WHEN det_cli_mon =2 THEN 'DOLARES'
                    end) as moneda,
                    det_cli_deu_cap as capital,
                    det_cli_sal_deu as saldo,
                    det_cli_deu_mor as deuda,
                    det_cli_dscto as dscto,
                    det_cli_imp_dscto as importe_dscto,
                    det_cli_imp_can as importe,
                    det_cli_dscto_adc as dscto_adc,
                    det_cli_imp_can_adc as importe_adc,
                    (SELECT det_dsc_dscto FROM detalle_dscto WHERE det_dsc_fec=DATE_FORMAT(DATE_ADD(DATE(NOW()),INTERVAL -1 MONTH),'%Y%m') AND det_dsc_num_doc=det_cli_num_doc and det_dsc_est=0) as dscto_ant
            FROM 
                    detalle_cliente
            WHERE 
                    cli_id_FK=:id
                    AND det_cli_est = 0
                    AND det_cli_pas = 0
            )t
        ";

        $query=DB::connection('mysql')->select(DB::raw($sql),array("id"=>$id));
       return $query;
   }

   public static function updateEmail(Request $rq){
        $email=$rq->email;
        $cliente=$rq->idcliente;
        $sql="
            update cliente 
            set cli_ema=:email
            where cli_id=:idcli
        ";
        DB::connection('mysql')->update($sql,array("email"=>$email,"idcli"=>$cliente));
        return "ok";
   }
}
