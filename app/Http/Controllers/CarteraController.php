<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cartera;

class CarteraController extends Controller
{
    public function listCarteras(){
        return Cartera::listCarteras();
    }

    public function listCarterasUsuario(){
        return Cartera::listCarterasUsuario();
    }
    
}
