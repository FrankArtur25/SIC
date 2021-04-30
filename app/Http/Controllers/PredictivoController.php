<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RepositorioExport;
use App\Exports\ReportePredictivoExport;
use Illuminate\Http\Request;
use App\Predictivo;
use App\Respuesta;
use Carbon\Carbon;
use App\Imports\ResultadosPredictivoImport;

class PredictivoController extends Controller
{
    public function condiciones($cartera){
        $entidades=Respuesta::listaEntidades($cartera);
        $score=Respuesta::listaScore($cartera);
        $prioridad=Respuesta::listaPrioridad($cartera);
        $tramos=Respuesta::listaTramo($cartera);
        $zonas=Respuesta::listaZona($cartera);
        return $datos=[
            "entidades"=>$entidades,
            "score"=>$score,
            "tramos"=>$tramos,
            "prioridades"=>$prioridad,
            "zonas"=>$zonas
        ];
    }

    public function calcularCampana(Request $rq){
        return Predictivo::repositorio($rq,0,'');
    }

    public function crearCampana(Request $rq){
        $fecha=Carbon::now();
        Predictivo::crearCampana($rq,$fecha);
        $datos=Predictivo::IdCampana($rq,$fecha);
        Predictivo::repositorio($rq,1,$datos[0]->id);
        return $datos;
    }

    public function descargar($idCampana){
        return (new RepositorioExport($idCampana))->download('base_predictivo.csv');        
    }

    public function asignar($idcampana,$usuario){
        Predictivo::asignar($idcampana,$usuario);
        Predictivo::validacionAsignacion($idcampana,1);
        return "ok";
    }
    

    public function listaCampanas(Request $rq){
        return Predictivo::listaCampanas($rq);
    }

    public function devolverAsignacion(Request $rq){
        Predictivo::devolverAsignacion($rq);
        Predictivo::validacionAsignacion($rq->idCampana,0);
        if($rq->opcion!="todos"){
            Predictivo::AsignarPdpsTT($rq);
        }
        return "ok";
    }

    public function eliminarCampana($idCampana){
        return Predictivo::eliminarCampana($idCampana);
    }
    
    public function datosGestiones($idCampana){
        $datosGestiones=Predictivo::datosGestiones($idCampana);
        $datosResultados=Predictivo::datosConResultados($idCampana);
        return ["cantGestiones"=>$datosGestiones,"cantResultados"=>$datosResultados];
    }

    public function generarGestiones($idCampana,$total){
        Predictivo::generarGestiones($idCampana);
        Predictivo::actualizarCantidad($idCampana,$total);
        return "ok";
    }

    public function actualizarFechaCampana(Request $rq){
        return Predictivo::actualizarFechaCampana($rq);
    }
    

    public function descargarReporte($idCampana){
        $gestor=Predictivo::reporteCampanaGestor($idCampana);
        $respuestas=Predictivo::reporteCampanaPaletas($idCampana);
        return ["rep_gestor"=>$gestor,"rep_respuestas"=>$respuestas];
    }
    
    public function cargarResultadosPredictivo(Request $rq){
        // ini_set('max_execution_time', 1600);
        $idCampana=$rq->idCampana;
        $file=$rq->file('archivo');
        if ($file == null) {    
            return "error";       
        }else {
            Excel::import(new ResultadosPredictivoImport($idCampana), $file);
            return "ok";
        }
    }

    public function crearIvrCodigo(Request $rq){
        return Predictivo::crearIvrCodigo($rq);
    }

}
