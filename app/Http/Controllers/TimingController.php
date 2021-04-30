<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timing;
class TimingController extends Controller
{
    public function timingProyectado($cartera){
        $timing=Timing::timingDiario($cartera);
        $proyectado=Timing::proyectado($cartera);
        $cumplimiento=Timing::cumplimientoProyectado($cartera);
        $datos=["timing"=>$timing,"proyectado"=>$proyectado,"cumplimiento"=>$cumplimiento];
        return $datos;
    }
}
