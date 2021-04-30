<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Respuesta;
use App\Cartera;

class RespuestaController extends Controller
{
    public function listasPanelBusqueda(){
        $respuestas=Respuesta::listRespuestas();
        $motivonopago=Respuesta::listaMotivosNoPago();
        $oficinas=Respuesta::listaOficinas();
        $carteras=Cartera::listCarteras();
        // $entidades=Respuesta::listaEntidades();
        // $score=Respuesta::listaScore();
        // $descuentos=Respuesta::listaDescuentos();
        // $prioridad=Respuesta::listaPrioridad();
        return $opcionesBusqueda=[
            "respuestas"=>$respuestas,
            "motivonopago"=>$motivonopago,
            "oficinas"=>$oficinas,
            "carteras"=>$carteras
            // "entidades"=>$entidades,
            // "score"=>$score,
            // "descuentos"=>$descuentos,
            // "prioridad"=>$prioridad
        ];
    }

    public function listasBusquedaPorCartera($cartera){
        $entidades=Respuesta::listaEntidades($cartera);
        $score=Respuesta::listaScore($cartera);
        $descuentos=Respuesta::listaDescuentos($cartera);
        $prioridad=Respuesta::listaPrioridad($cartera);
        return $opcionesBusqueda=[
            "entidades"=>$entidades,
            "score"=>$score,
            "descuentos"=>$descuentos,
            "prioridad"=>$prioridad
        ];
    }

    public function listRespuestas(){
        return Respuesta::listRespuestas();
    }

    public function listRespuestasCampo(){
        return Respuesta::listRespuestasCampo();
    }

    public function listaMotivosNoPago(){
        return Respuesta::listaMotivosNoPago();
    }

    public function listaRespuestaUbicabilidad($ubic){
        $acceso=auth()->user()->emp_tip_acc;
        if($acceso==8){
            return Respuesta::listaRespuestaUbicabilidadBolsa($ubic);
        }else{
            return Respuesta::listaRespuestaUbicabilidad($ubic);
        }
    }

    public function listaRespuestaUbicSms($ubic){
        return Respuesta::listaRespuestaUbicSms($ubic);
    }

    public function listaEntidades(){
        return Respuesta::listaEntidades();
    }

    public function listaScore($cartera){
        return Respuesta::listaScore($cartera);
    }

    public function listaOficinas(){
        return Respuesta::listaOficinas();
    }
}
