<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;

class PagoController extends Controller
{
    public function listaPagos($id){
        return Pago::infoPagos($id);
    }

    // public function importExcelPagos(Request $rq){
    //     $cartera=$rq->cartera;
    //     $file=$rq->file('archivo');
    //     if ($file == null) {    
    //         return "error";       
    //     }else {
    //         $array=Excel::toArray(new PagosImport(), $file);    
    //         $data = array();
    //     }
    // }

    public function actualizarPagosCartera(Request $rq){
        $cartera=$rq->cartera;
        $mes=$rq->mes;
        $campo=$rq->campo;
        $file=$rq->file('archivo');

        if ($file == null) {
            return "error";
        }else {
            try {
                //borrar pagos
                // $rd=Pago::deletePagos($cartera,$mes);
                if($rd=="ok"){
                    //importar pagos
                    Excel::import(new PagosBaseImport($cartera), $file);
                    //completar campos vacios
                    if($campo==1){
                        Pago::updateCodigoPago($cartera,$mes);
                    }
                    if($campo==2){
                        Pago::updateProductoPago($cartera,$mes);
                    }
                    return "ok";
                }
            } catch (\Throwable $th) {
                return "Error";
            }
        }
    }
}
