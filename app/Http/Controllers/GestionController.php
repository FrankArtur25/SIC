<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gestion;
use App\Recordatorio;
use Carbon\Carbon;

class GestionController extends Controller
{

    public function insertarGestion(Request $rq){
        $detalle=$rq->detalle;
        $monto=$rq->montoPDP;
        $fechapc=$rq->fechaPDP;
        $tel=isset($rq->telefono)?$rq->telefono:0;
        $resp=$rq->respuesta;
        $rec=$rq->rec;
        $fechaRec=$rq->fechaRec;
        $horaRec=$rq->horaRec;
        $id=$rq->id;
        $fechaGestion=Carbon::now();
        $numeroDia=Carbon::now()->dayOfWeek;
        $cartera=session()->get('datos')->idcartera;

        if($cartera==34 && ($numeroDia==6 || $numeroDia==0)){
            return ["Registro de Gestión Desactivada"];
        }else{
            if( $detalle!="" && $resp!="" && $id!=""){
                
                $validacion=Gestion::validarDetalleIdentico($rq);

                if($resp==1 || $resp==2 || $resp==43){
                    if($fechapc!="" && $monto!=""){
                        Gestion::insertarGestion($rq,$fechaGestion);
                        $idGestion=Gestion::selectIdGestion($fechaGestion,$id,$tel);
                        if($resp==1 || $resp==43){
                            // inserta en la tabla compromiso_cliente
                            Gestion::insertarPDP($rq,$fechaGestion,$idGestion[0]->id);
                            $idcomp=Gestion::selectIdCompromiso($id,$idGestion[0]->id,$fechaGestion);
                            // actualizar en la tabla cliente 
                            Gestion::updateUltCompromiso($id,$idcomp[0]->id);
                        }
                        if($resp==2){
                            //cambia el estado del compromiso
                            Gestion::updateCompromisoEstado($id);
                        }
                        // actualizar en la tabla cliente
                        Gestion::updateUltGestion($id,$idGestion[0]->id);
                    }
                }else{
                    Gestion::insertarGestion($rq,$fechaGestion);
                    $idGestion=Gestion::selectIdGestion($fechaGestion,$id,$tel);
                    // actualizar en la tabla cliente
                    Gestion::updateUltGestion($id,$idGestion[0]->id);   
                }
                        
                if($rec==true && $fechaRec!="" && $horaRec!=""){
                    Recordatorio::updateEstadoRecordatorio($id); 
                    Recordatorio::insertRecordatorio($rq);
                }
                return ["ok",$validacion];
            }
        }
    }

    public function validarContacto($id){
        return Gestion::validarContacto($id);
    }

    public function validarPDP($id){
        return Gestion::validarPDP($id);
    }

    public function validarDetalleIdentico(Request $rq){
        return Gestion::validarDetalleIdentico($rq);
    }
}
