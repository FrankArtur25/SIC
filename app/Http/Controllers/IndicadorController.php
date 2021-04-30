<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Indicador;
use App\Respuesta;
use Carbon\Carbon;

class IndicadorController extends Controller
{
    public function asignacion(){
        return Indicador::asignacion();
    }

    public function estructuras(Request $rq){
        $cartera=$rq->cartera;
        $estructura=$rq->estructura;
        if($estructura=="entidades" || $estructura=="score"){
            if($estructura=="entidades"){
                return Respuesta::listaEntidades($cartera);
            }
            if($estructura=="score"){
                return Respuesta::listaScore($cartera);
            }
        }else{
            return Indicador::estructuras($rq);
        }
        
    }

    public function reporteIndicadoresOperativos(Request $rq){
        $array=array();
        $indicador=$rq->indicador;
        $mesActual=Carbon::now();
        $mesMenos1=Carbon::now()->subMonth();
        $mesMenos2=Carbon::now()->subMonth(2);
        $datosMes=[];
        $datosMes1=[];
        $datosMes2=[];
        $array=[];
        $totales=[];
        if($indicador=="cobertura"){
            $totales=Indicador::totales($rq);
            $datosMes= Indicador::cobertura($rq,$mesActual);
            $datosMes1= Indicador::cobertura($rq,$mesMenos1);
            $datosMes2= Indicador::cobertura($rq,$mesMenos2);
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }
        if($indicador=="contactabilidad"){
            $totales=Indicador::totales($rq);
            $datosMes= Indicador::contactabilidad($rq,$mesActual);
            $datosMes1= Indicador::contactabilidad($rq,$mesMenos1);
            $datosMes2= Indicador::contactabilidad($rq,$mesMenos2);
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }

        if($indicador=="efectiva"){
            $totales=Indicador::totales($rq);
            $datosMes= Indicador::contactabilidadEfectiva($rq,$mesActual);
            $datosMes1= Indicador::contactabilidadEfectiva($rq,$mesMenos1);
            $datosMes2= Indicador::contactabilidadEfectiva($rq,$mesMenos2);
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }

        if($indicador=="intensidad"){
            $totales=Indicador::totales($rq);
            $datosMes= Indicador::intensidad($rq,$mesActual);
            $datosMes1= Indicador::intensidad($rq,$mesMenos1);
            $datosMes2= Indicador::intensidad($rq,$mesMenos2);
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }
        
        if($indicador=="directa"){
            $totalActual= Indicador::totalesCEF($rq,$mesActual);
            $totalMes1= Indicador::totalesCEF($rq,$mesMenos1);
            $totalMes2= Indicador::totalesCEF($rq,$mesMenos2);
            $datosMes= Indicador::intensidadDirecta($rq,$mesActual);
            $datosMes1= Indicador::intensidadDirecta($rq,$mesMenos1);
            $datosMes2= Indicador::intensidadDirecta($rq,$mesMenos2);
            $totales=[$totalActual,$totalMes1,$totalMes2];
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }

        if($indicador=="tasa"){
            $totalActual= Indicador::totalesCEF($rq,$mesActual);
            $totalMes1= Indicador::totalesCEF($rq,$mesMenos1);
            $totalMes2= Indicador::totalesCEF($rq,$mesMenos2);
            $datosMes= Indicador::tasaCierre($rq,$mesActual);
            $datosMes1= Indicador::tasaCierre($rq,$mesMenos1);
            $datosMes2= Indicador::tasaCierre($rq,$mesMenos2);
            $totales=[$totalActual,$totalMes1,$totalMes2];
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }

        if($indicador=="promesas"){
            $totalActual= Indicador::montoPromesas($rq,$mesActual);
            $totalMes1= Indicador::montoPromesas($rq,$mesMenos1);
            $totalMes2= Indicador::montoPromesas($rq,$mesMenos2);
            $datosMes= Indicador::montoPagos($rq,$mesActual);
            $datosMes1= Indicador::montoPagos($rq,$mesMenos1);
            $datosMes2= Indicador::montoPagos($rq,$mesMenos2);
            $totales=[$totalActual,$totalMes1,$totalMes2];
            $array=[$totales,$datosMes,$datosMes1,$datosMes2];
            return $array;
        }
    }
}
