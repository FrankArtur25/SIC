<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actualizacion;

class ActualizacionController extends Controller
{
    public function infoactualizacionpagos(){
        return Actualizacion::infopagos();
    }

    public function infoactualizacioncarteras(){
        return Actualizacion::infocarteras();
    }
}
