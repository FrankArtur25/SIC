<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campana;

class CampanaController extends Controller
{
    public function estadosCampana(){
        return Campana::estadosCampana();
    }
}
