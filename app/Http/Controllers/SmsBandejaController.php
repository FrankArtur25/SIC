<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bandeja;

class SmsBandejaController extends Controller
{
    public function bandejaMsj(){
        $carteras=session()->get('datos')->idcartera;
        if($carteras==0){
            return Bandeja::bandejaNoIdentificados();
        }else{
            return Bandeja::bandejaCartera();
        }
    }
    
    public function chat($numero){
        return Bandeja::chat($numero);
    }
}
