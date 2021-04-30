<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Telefono;
use Carbon\Carbon;
use App\Gestion;

class TelefonoController extends Controller
{
    public function listaTelefonos($id){
        if($id!='undefined'){
            $telefonos=Telefono::infoTelefonos($id);
            $validacion_contacto=Gestion::validarContacto($id);
            $validacion_pdp=Gestion::validarPDP($id);

            $datosTel=['telefonos'=>$telefonos,
                       'validar_contacto'=>$validacion_contacto,
                        'pdps'=>$validacion_pdp
                            ];
            return $datosTel;
        }
    }

    public function insertarTelefonos(Request $rq){
        if($rq->telefono!=""){
            $fecha=Carbon::now();
            Telefono::insertarTelefonos($rq,$fecha);
            Telefono::insertarBitacoraTelefonos($rq,$fecha);
            return "ok";
        }
    }

    public function actualizarEstadoTelefono(Request $rq){
        return Telefono::actualizarEstadoTelefono($rq);
    }
    
}
