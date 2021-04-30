<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cartera; 
use App\SmsCampana;
use App\Imports\NumeroImport;
use Maatwebsite\Excel\Facades\Excel;

class SmsCampanaController extends Controller
{
    public function buscarCampana(Request $rq){
        return SmsCampana::buscarCampana($rq);
    }

    public function detalleCampana($id){
        return SmsCampana::detalleCampana($id);
    }

    public function condicionCampana($id){
        return SmsCampana::condicionCampana($id);
    }

    public function enviarCampana($id){
        return SmsCampana::enviarCampana($id);
    }   

    public function listCampanasDia(){
        return SmsCampana::listCampanasDia();
    }

    public function datosclientesCampana(Request $rq){
        return SmsCampana::datosclientesCampana($rq);
    }

    public function tagCondicion($cartera,$tipo){
        return SmsCampana::tagCondicion($cartera,$tipo);
    }

    public function insertCampana(Request $rq){
        return SmsCampana::insertCampana($rq);
    }

    public function cargarListaNegra(Request $rq){
        // ini_set('max_execution_time', 1600);
        $file=$rq->file('archivo');
        if ($file == null) {                     
            return "error";       
        }else {
            Excel::import(new NumeroImport, $file);
            return "ok";
        }
    }

    public function insertarListaNegra(Request $rq){
        $numeros=$rq->numeros;
        $arrayNumeros=explode(",",$numeros);
        
        foreach($arrayNumeros as $num){
            SmsCampana::insertarListaNegra($num);
        }
        return "ok";
    }

    public function buscarListaNegra(Request $rq){
        return SmsCampana::buscarListaNegra($rq->numero);
    }

    public function retirarListaNegra($id){
        return SmsCampana::retirarListaNegra($id);
    }

    public function validarEnvio($id){
        return SmsCampana::validarEnvio($id);
    }
}
