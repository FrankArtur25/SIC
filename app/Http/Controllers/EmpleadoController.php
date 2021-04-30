<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AsignacionMultipleImport;
use App\Imports\AsignacionMultipleBaseImport;
use App\Exports\BitacoraRepositorioAsignacionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Empleado;

class EmpleadoController extends Controller
{
    protected $connection = 'mysql';

    public function codigoEmpleado(){
        while($codigo = rand(999,9999)){
            $res=Empleado::codigoEmpleado($codigo);
            if(count($res)==0){
                return $codigo;
                break;
            }
        }
    }
    
    public function insertarEmpleado(Request $rq){
        return Empleado::insertarEmpleado($rq);
    }

    public function listEmpleados(Request $rq){
        if($rq->codigo!='' || $rq->nombre!='' || $rq->perfil!=''){
            return Empleado::listEmpleados($rq);
        }
    }

    public function updateEmpleado(Request $rq){
        return Empleado::updateEmpleado($rq);
    }

    public function updateClave(Request $rq){
        return Empleado::updateClave($rq);
    }

    public function insertarGestor(Request $rq){
        return Empleado::insertarGestor($rq);
    }

    public function listGestores(Request $rq){
        return Empleado::listGestores($rq);
    }

    public function listGestoresActivos(){
        return Empleado::listGestoresActivos();
    }

    public function updateFirma(Request $rq){
        return Empleado::updateFirma($rq);
    }

    public function updateGestor(Request $rq){
        return Empleado::updateGestor($rq);
    }

    public function historialLaboral($firma){
        return Empleado::historialLaboral($firma);
    }

    public function registroHistorialLaboral(Request $rq){
        $tipo=$rq->tipoBtn;
        if($tipo==1){
            return Empleado::insertHistorialLaboral($rq);
        }
        if($tipo==2){
            return Empleado::updateHistorialLaboral($rq);
        }
    }

    public function listEmpleadosActivos(){
        return Empleado::listEmpleadosActivos();
    }

    public function updateUsuario(Request $rq){
        return Empleado::updateUsuario($rq);
    }
    
    // asignacion
    public function consultarIntercambio(Request $rq){
        return Empleado::consultarIntercambio($rq);
    }

    public function updateIntercambio(Request $rq){
        $cartera=$rq->cartera;
        $cod_operacion=$rq->codigo;
        $res1=Empleado::insertarBitacoraAsignacion($cod_operacion,$cartera,'IU');
        if($res1=="ok"){
            $res2=Empleado::insertarBitacoraRepositorioIntercambio($rq);
            if($res2=="ok"){
                // return Empleado::updateIntercambio($rq);
                return Empleado::generarAsignacionMultiple($cod_operacion,$cartera);
            }else{
                return "error";
            }
        }
    }

    public function importExcelAsignacion(Request $rq){
        $cartera=$rq->cartera;
        $file=$rq->file('archivo');
        if ($file == null) {    
            return "error";       
        }else {
            $array=Excel::toArray(new AsignacionMultipleImport(), $file);    
            $data = array();
            $dataUsuario = array();
            $suma=0;
            
            foreach ($array[0] as $parte) {
                $usuarios[] = $parte[1];
            }
            
            $unico_usuarios = array_unique($usuarios);
                foreach ($unico_usuarios as $usuario) {
                    foreach ($array[0] as $original) {
                        if ($usuario == $original[1]) {
                            $suma = $suma + 1;
                        }
                    }
                    $dataUsuario['usuario'] = $usuario;
                    $dataUsuario['cantidad'] = $suma;
                    array_push($data, $dataUsuario);
                    $suma = 0;
                }
            return $data;
        }
    }

    public function generarAsignacionMultiple(Request $rq){
        $cartera=$rq->cartera;
        $cod_operacion=$rq->codigo;
        $file=$rq->file('archivo');
        if ($file == null) {    
            return "error";       
        }else {
            try {
                $res1=Empleado::insertarBitacoraAsignacion($cod_operacion,$cartera,'AM');
                if($res1=="ok"){
                    Excel::import(new AsignacionMultipleBaseImport($cod_operacion,$cartera), $file);            
                    $res2=Empleado::updateBitacoraRepositorio($cod_operacion,$cartera);
                    if($res2=="ok"){
                        Empleado::generarAsignacionMultiple($cod_operacion,$cartera);
                    }
                }
                return "ok";
            } catch (\Throwable $th) {
                return "Error";
            }
        }
    }

    public static function generarCodigoAsignacion(){
        $codAleatorio='';
        while($codigo = rand(999,9999)){
            $res=Empleado::codigoAsignacion($codigo);
            if(count($res)==0){
                $codAleatorio=$codigo;
                break;
            }
        }
        return $codAleatorio;
    }

    public function listBitacoraAsignacion(Request $rq){
        return Empleado::listBitacoraAsignacion($rq);
    }

    public function updateRegresarAsignacion($codigo,$cartera){
        return Empleado::updateRegresarAsignacion($codigo,$cartera);
    }

    public function descargarBitacoraRepositorio($codigo){
        return (new BitacoraRepositorioAsignacionExport($codigo))->download('asignacion.xlsx');
    }

    public function generarAsignacionIndividual(Request $rq){
        $cartera=$rq->cartera;
        $cod_operacion=$rq->codigo;
        $res1=Empleado::insertarBitacoraAsignacion($cod_operacion,$cartera,'AI');
        if($res1=="ok"){
            $res2=Empleado::insertarBitacoraRepositorioIndividual($rq);
            if($res2=="ok"){
                return Empleado::generarAsignacionMultiple($cod_operacion,$cartera);
            }else{
                return "error";
            }
        }
    }

    // public function agentesElastix($cartera){
    //     return Empleado::agentesElastix($cartera);
    // }
}
