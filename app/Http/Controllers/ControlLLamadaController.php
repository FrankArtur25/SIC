<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\controlLLamada;
use App\Cartera;

class ControlLLamadaController extends Controller
{
    public function panelcontrolllamadas(){
        $carteras=Cartera::listCarteras();
        $carteras=json_encode($carteras);
        return view('admin.controlLlamadas',compact('carteras'));
    }
    
    public function controlLLamadas(Request $rq){
        return controlLLamada::controlLLamadas($rq);
    }

    public function panelcontrolllamadasgestor(){
        $carteras=Cartera::listCarteras();
        $carteras=json_encode($carteras);
        return view('admin.controlLlamadasGestor',compact('carteras'));
    }

    public function controlLLamadasGestor(Request $rq){
        return controlLLamada::controlLLamadasGestor($rq);
    }

}
