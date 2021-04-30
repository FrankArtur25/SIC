<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Control;

class ControlController extends Controller
{
    public function reporteControl(){
        $estandar=Control::estandar();
        $gestiones=Control::reporteControl();
        // foreach ($gestiones as $gest) {
            
        // }
        return $data=['estandar'=>$estandar,'gestiones'=>$gestiones];
    }    

}
