<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reporte;
use App\Exports\GestionesExport;
use App\Exports\ReporteConfirmacionesExport;
use App\Exports\ReporteConfirmacionesPagosExport;
use App\Exports\ReportePdpsExport;
use App\Exports\ReporteRankingExport;
use App\Exports\GenerarGestionesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function asignacionCall(){
        return Reporte::asignacionCall();
    }

    public function reporteGeneralGestiones($cartera,$fecInicio,$fecFin,$perfil){
        return (new GestionesExport($cartera,$fecInicio,$fecFin,$perfil))->download('reporte_general.xlsx');
        // return Excel::download(new GestionesExport, 'users.xlsx');
        //  return Reporte::reporteGeneralGestiones($rq);
    }

    // public function reporteGeneralGestiones(Request $rq){
    //     // return (new GestionesExport($rq))->download('reporte_general.xlsx');
    //     // return Excel::download(new GestionesExport, 'users.xlsx');
    //      return Reporte::reporteGeneralGestiones($rq);
    // }
    
    public function reporteResumenGestor(Request $rq){
        return Reporte::reporteResumenGestor($rq);
    }

    public function descargarGestionesGestor(Request $rq){
        return Reporte::descargarGestionesGestor($rq);
    }

    public function primerayultimagestion(Request $rq){
        return Reporte::primerayultimagestion($rq);
    }

    public function cantGestioneHora($cartera){
        return Reporte::cantGestioneHora($cartera);
    }

    public function resumenGestionDia($cartera){
        $resumen=Reporte::resumenGestor($cartera);
        $gestiones=Reporte::resumenGestionesCartera($cartera);
        $datos=["resumen"=>$resumen,"gestiones"=>$gestiones];
        return $datos;
    }

    public function resumenGestionConsolidada($fecha){
        return Reporte::resumenGestionesCarteraConsolidado($fecha);
    }

    public function reportecomparativocartera(Request $rq){
        $comparativo=$rq->comparativo;
        $cartera=$rq->cartera;
        if($comparativo=='afecha'){
            if($cartera=='34' || $cartera=='88' || $cartera=='2' || $cartera=='89' || $cartera=='70' ||  $cartera=='20' || $cartera=='72' || $cartera=='5'){
                return Reporte::reporteComparativoCarteraPagos($rq);
            }else{
                return Reporte::reporteComparativoCarteraCon($rq);
            }
        }else{
            if($cartera=='34' || $cartera=='88' || $cartera=='2' || $cartera=='89' || $cartera=='70' ||  $cartera=='20' || $cartera=='72' || $cartera=='5'){
                return Reporte::reporteComparativoCarteraPagosCierre($rq);
            }else{
                return Reporte::reporteComparativoCarteraConCierre($rq);
            }
        }
    }

    public function detalleConfirmaciones($cartera){
        return Reporte::detalleConfirmaciones($cartera);
    }

    public function reporteEstandarCartera(Request $rq){
        return Reporte::reporteEstandarCartera($rq);
    }


    // reportes sistemas
    public function generarReporteConfirmaciones($cartera,$estructura,$calls,$tipoFecha,$fechaInicio,$fechaFin,$columnas){
        $rq = new Request(array('cartera'=>$cartera,'estructura'=>$estructura,'calls'=>$calls,'tipoFecha'=>$tipoFecha,'fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'columnas'=>$columnas));
        return (new ReporteConfirmacionesExport($rq))->download('reporte_confirmaciones.xlsx');
    }
     
    public function generarReportePdps($cartera,$estructura,$calls,$tipoFecha,$fechaInicio,$fechaFin,$columnas){
        $rq = new Request(array('cartera'=>$cartera,'estructura'=>$estructura,'calls'=>$calls,'tipoFecha'=>$tipoFecha,'fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'columnas'=>$columnas));
        return (new ReportePdpsExport($rq))->download('reporte_pdps.xlsx');
    }

    public function generarReporteRanking($cartera,$estructura,$calls,$fechaInicio,$fechaFin){
        $rq = new Request(array('cartera'=>$cartera,'estructura'=>$estructura,'calls'=>$calls,'fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin));
        return (new ReporteRankingExport($rq))->download('reporte_ranking.xlsx');
    }

    public function generarReporteConfirmacionesPagos($fechaInicio,$fechaFin){
        $rq = new Request(array('fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin));
        return (new ReporteConfirmacionesPagosExport($rq))->download('confirmaciones_vs_pagos.xlsx');
    }

    public function generarGestionesFicticias($paleta,$modalidad,$cantidad,$fechaInicio,$fechaFin,$cartera){
        $rq = new Request(array('paleta'=>$paleta,'modalidad'=>$modalidad,'cantidad'=>$cantidad,'fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'cartera'=>$cartera));
        //return Reporte::generarGestionesFicticias($rq);
        return (new GenerarGestionesExport($rq))->download('gestiones_ficticias.xlsx');
    }
}
