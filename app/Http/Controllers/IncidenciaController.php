<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incidencia;

class IncidenciaController extends Controller
{
    public function tiposIncidencias(){
        return Incidencia::tiposIncidencias();
    }

    public function insertIncidencia(Request $rq){
        return Incidencia::insertIncidencia($rq);
    }

    public function buscarIncidencias(Request $rq){
        return Incidencia::buscarIncidencias($rq);
    }

    public function editarIncidencia(Request $rq){
        return Incidencia::editarIncidencia($rq);
    }

    // public function incListaGestores(){
    //     return Incidencia::incListaGestores();
    // }
}
