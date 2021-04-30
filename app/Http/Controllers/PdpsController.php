<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pdps;

class PdpsController extends Controller
{
    public function reporteEstadosPdps(Request $rq){
        return Pdps::reporteEstadosPdps($rq);
    }

    public function reporteEstandarPdps(Request $rq){
        return Pdps::reporteEstandarPdps($rq);
    }

    public function reportePdps(Request $rq){
        return Pdps::reportePdps($rq);
    }

    public function listaPdps(Request $rq){
        return Pdps::listaPdps($rq);
    }

    public function descargarListaPdps(Request $rq){
        return Pdps::descargarListaPdps($rq);
    }

    public function comparativaPagosFecha(Request $rq){
        return Pdps::comparativaPagosFecha($rq);
    }

    public function comparativaPdpsFecha(Request $rq){
        return Pdps::comparativaPdpsFecha($rq);
    }
    
}
