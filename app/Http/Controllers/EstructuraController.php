<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estructura;
use App\Exports\EstructuraCarteraCarteraExport;
use App\Exports\EstructuraCarteraGestionExport;
use App\Exports\EstructuraGestorCarteraExport;
use App\Exports\EstructuraGestorGestionExport;

class EstructuraController extends Controller
{
    public function reporteEstructuraCartera(Request $rq){
        $ubicabilidad=$rq->ubicabilidad;
        if($ubicabilidad=='todos'){
            return Estructura::reporteEstructuraCarteraTodo($rq);
        }else{
            return Estructura::reporteEstructuraCarteraGestion($rq);
        }
    }

    public function listaGestores($cartera){
        return Estructura::listaGestores($cartera);
    }

    public  function reporteEstructuraGestor(Request $rq){
        return Estructura::estructuraGestor($rq);
    }

    public function reporteEstructuraGestorCartera(Request $rq){
        $ubicabilidad=$rq->ubicabilidad;
        if($ubicabilidad=='todos'){
            return Estructura::reporteEstructuraGestorCarteraTodo($rq);
        }else{
            return Estructura::reporteEstructuraGestorCarteraGestion($rq);
        }
    }

    public function reporteEstructuraGestionCartera(Request $rq){
        $tipo=$rq->tipo;
        if($tipo=='pagos'){
            return Estructura::reporteEstructuraCarteraPagos($rq);
        }else{
            return Estructura::reporteEstructuraGestionCartera($rq);
        }
    }
    
    public function descargarEstructuraCarteraCartera($cartera,$ubicabilidad,$estructura,$valor,$mes){
        return (new EstructuraCarteraCarteraExport($cartera,$ubicabilidad,$estructura,$valor,$mes))->download('estructura_cartera.xlsx');
    }

    public function descargarEstructuraCarteraGestion($cartera,$tipo,$estructura,$valor,$fecInicio,$fecFin){
        return (new EstructuraCarteraGestionExport($cartera,$tipo,$estructura,$valor,$fecInicio,$fecFin))->download('estructura_cartera_gestion.xlsx');
    }
    
    public function descargarEstructuraGestorCartera($cartera,$ubicabilidad,$estructura,$valor,$mes,$gestor){
        return (new EstructuraGestorCarteraExport($cartera,$ubicabilidad,$estructura,$valor,$mes,$gestor))->download('estructura_gestor_cartera.xlsx');
    }

    public function descargarEstructuraGestorGestion($cartera,$tipo,$estructura,$valor,$fecInicio,$fecFin,$gestor){
        return (new EstructuraGestorGestionExport($cartera,$tipo,$estructura,$valor,$fecInicio,$fecFin,$gestor))->download('estructura_gestor_gestion.xlsx');
    }
}
