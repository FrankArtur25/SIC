<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Telefono;
use App\Gestion;

class ClienteController extends Controller
{
    public function listaClientes(Request $rq){
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
        $tipo=$rq->tipo;
        $respuesta_gestion=$rq->respuesta_gestion;
        $fecha_gestion_inicio=$rq->fecha_gestion_inicio;
        $fecha_gestion_fin=$rq->fecha_gestion_fin;
        $acceso=auth()->user()->emp_tip_acc;
        $k=0;
        if($acceso==2 || $acceso==8){
            return cliente::listarClientes($rq);
        }else{
            
            if($codigo==null &&	$dni==null &&	$nombre==null &&	$telefono==null &&   $tramo==null 
            &&	$respuesta==null &&	$fec_desde=='undefined-undefined-' &&	$fec_hasta=='undefined-undefined-' 
            &&	$ordenar==null   &&	$camp==null &&	$deuda==null &&	$capital==null &&	$importe==null 
            &&	$sueldo==null    &&	$entidades==null &&	$score==null &&	$motivo==null &&	$oficina==null 
            &&	$descuento==null &&	$prioridad==null && $numproducto==null && $respuesta_gestion==null 
            &&  $fecha_gestion_inicio=='undefined-undefined-' && $fecha_gestion_fin=='undefined-undefined-'
            ){  
                return [];
            }else{
               
                return cliente::listarClientes($rq);
            }
        }
    }

    public function datosMes(){
        return cliente::datosMes();
    }

    public function datosEstandar(Request $rq){
        return cliente::datosEstandar($rq);
    }

    public function estadoCampana(Request $rq){
        return cliente::estadosCampana($rq);
    }

    // public function infoCliente($id){
    //     return cliente::infoCliente($id);
    // }

    public function historicoGestiones($id){
        return cliente::historicoGestiones($id);
    }

    // public function infoDeuda($id){
    //     return cliente::infoDeuda($id);
    // }

    public function detalleCliente($id){
        $infoCliente=cliente::infoCliente($id);
        $infoCuenta=cliente::infoDeuda($id);
        $historicoGestiones=cliente::historicoGestiones($id);
        $telefonos=Telefono::infoTelefonos($id);
        $validacion_contacto=Gestion::validarContacto($id);
        $validacion_pdp=Gestion::validarPDP($id);

        $datosgenerales=['infoCliente'=>$infoCliente,
                         'cuentas'=>$infoCuenta,
                         'gestiones'=>$historicoGestiones,
                         'telefonos'=>$telefonos,
                         'validar_contacto'=>$validacion_contacto,
                         'pdps'=>$validacion_pdp
                        ];
        return $datosgenerales;                        
    }

    public function updateEmail(Request $rq){
        return cliente::updateEmail($rq);
    }


    // integracion ocm
    public function orbelite($id,$tel){
        $infoCliente=cliente::infoCliente($id);
        $infoCuenta=cliente::infoDeuda($id);
        $historicoGestiones=cliente::historicoGestiones($id);
        $telefonos=Telefono::infoTelefonos($id);
        $validacion_contacto=Gestion::validarContacto($id);
        $validacion_pdp=Gestion::validarPDP($id);

        $datosgenerales=['infoCliente'=>$infoCliente,
                         'cuentas'=>$infoCuenta,
                         'gestiones'=>$historicoGestiones,
                         'telefonos'=>$telefonos,
                         'validar_contacto'=>$validacion_contacto,
                         'pdps'=>$validacion_pdp,
                         'telefono'=>$tel
                        ];
        $datosgenerales=JSON_encode($datosgenerales);
        return view('integracion.cliente',compact("id","datosgenerales"));                        
    }
}
