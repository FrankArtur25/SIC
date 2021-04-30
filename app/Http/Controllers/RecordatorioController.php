<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recordatorio;
use App\Telefono;
use App\Gestion;
use App\Cliente;
use App\Events\WebsocketsRecordatorio;

class RecordatorioController extends Controller
{
    public function insertarRecordatorio(Request $rq){
        $rec=$rq->rec;
        $fechaRec=$rq->fechaRec;
        $horaRec=$rq->horaRec;
        $id=$rq->id;
        $tel=$rq->tel_rec;
        if($rec==true && $fechaRec!="" && $horaRec!="" && $tel!=""){
           Recordatorio::updateEstadoRecordatorio($id); 
           Recordatorio::insertRecordatorio($rq);
           return "ok";
        }
    }

    // public function listarRecordatorio(){
    //     return Recordatorio::listarRecordatorio();
    // }

    public function listarRecordatorio(){
        $datos=Recordatorio::listarRecordatorio();
        if(count($datos)>0){
            for($i=0;$i<count($datos);$i++){
                // $datosgenerales=[];
                $telefonos=Telefono::infoTelefonos($datos[$i]->id);
                $validacion_contacto=Gestion::validarContacto($datos[$i]->id);
                $validacion_pdp=Gestion::validarPDP($datos[$i]->id);
                $gestiones=cliente::historicoGestiones($datos[$i]->id);
                // dd($gestiones);
                $datosgenerales=['recordatorios'=>$datos[$i],
                                'telefonos'=>$telefonos,
                                'validar_contacto'=>$validacion_contacto,
                                'pdps'=>$validacion_pdp,
                                'gestiones'=>$gestiones
                                ];
                
                 event(new WebsocketsRecordatorio($datosgenerales));
            }
        }

    }
    
}
