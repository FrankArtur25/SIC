<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use App\Cartera;
use Carbon\Carbon;
use App\Imports\CodigosPlanTrabajoImport;
use Maatwebsite\Excel\Facades\Excel;

class PlanController extends Controller
{
    public function listaPlanes(Request $rq){
        return Plan::listaPlanes($rq);
    }

    public function resumenPlan(Request $rq){
        return Plan::resumenPlan($rq);
    }

    public function insertarPlan(Request $rq){
        return Plan::insertarPlan($rq);
    }

    public function usuariosPlan($id){
        $clientes="";
        $cartera="";
        $res=Plan::datosPlan($id);
        if($res!=''){
            $clientes=$res[0]->clientes;
            $cartera=$res[0]->cartera;
        }
        return Plan::usuariosPlan($clientes,$cartera);
    }

    public function resultadosPlan(Request $rq){
        $idEmpleado=$rq->idEmpleado;
        $idPlan=$rq->idPlan;
        $clientes="";
        $cartera="";
        $fechaFin="";
        $fechaInicio="";
        $res=Plan::datosPlan($idPlan);
        if($res!=''){
            $clientes=$res[0]->clientes;
            $cartera=$res[0]->cartera;
            $fechaInicio=$res[0]->fechaInicio;
            $fechaFin=$res[0]->fechaFin;
        }
        return Plan::resultadoPlan($idEmpleado,$clientes,$cartera,$fechaInicio,$fechaFin);
    }

    public function datosPlanUsuario(){
        return Plan::datosPlanUsuario();
    }

    public function reportePlan(){
        return Plan::reportePlan();
    }

    public function reporteListaPlan(Request $rq){
        return Plan::reporteListaPlan($rq);
    }

    public function reporteCumplimiento(Request $rq){
        $datos=array();
        $datosGenerales=array();
        $dias=[];
        $fechaInicio = Carbon::parse($rq->fechaInicio);
        $fecha = Carbon::parse($rq->fechaInicio);
        $fechaFin = Carbon::parse($rq->fechaFin);
        $diasDiferencia = ($fechaFin->diffInDays($fechaInicio));
        for($i=0;$i<=$diasDiferencia;$i++){
            $fecha = Carbon::parse($rq->fechaInicio);
            if($i==0){
                $dias[0]=substr($fechaInicio,0,10);
            }else{
                $dias[$i]=substr($fecha->addDays($i),0,10);
            }
        }
        $datosPlan=Plan::codigosCarteraPlan($rq);
        foreach ($datosPlan as $d) {
            $gestiones=Plan::reporteCantGestiones($d->id_cartera,$d->clientes,$d->fecha_i,$d->fecha_f);
            // array_push($datos,["idCartera"=>$d->id_cartera,"cartera"=>$d->nombre_cartera,"cobertura"=>round(($gestiones[0]->gestionados/$d->cant_clientes)*100),"fecha"=>$d->fecha_i]);
            $datosAgrupados=array();
            $datosAgrupados["idCartera"]=$d->id_cartera;
            $datosAgrupados["cartera"]=$d->nombre_cartera;
            $datosAgrupados["plan"]=$d->nombre_plan;
            $datosAgrupados["clientes"]=$d->cant_clientes;
            $datosAgrupados["fecha"]=substr($d->fecha_i,0,10);
            for($i=0;$i<count($dias);$i++){
                if($dias[$i]==substr($d->fecha_i,0,10)){
                    // $datosAgrupados["idDia"]=$i+1;
                    $datosAgrupados["dia_$i"]=round(($gestiones[0]->gestionados/$d->cant_clientes)*100);
                }else{
                    $datosAgrupados["dia_$i"]="";
                }
            }

            array_push($datosGenerales,$datosAgrupados);
        }

        $carteras=Cartera::listCarteras();
        $dataFinal=array();
        foreach ($carteras as $car) {
            $arrayF=array();
            $arrayF['cartera']=$car->cartera;
            $arrayF['idCartera']=$car->id;
            for($i=0;$i<count($dias);$i++){
                $d=$i+1;
                $k=0;
                $suma=0;
                foreach ($datosGenerales as $dato) {
                    if($car->id==$dato['idCartera'] && $dato["dia_$i"]!=''){
                        $suma+=(int)$dato["dia_$i"];
                        $k++;
                    }      
                }
                if($k>0){
                    $arrayF["dia_$d"]=round($suma/$k)."%";
                }else{
                    $arrayF["dia_$d"]="-";
                }
            }
            array_push($dataFinal,$arrayF);
        }
        return ["dataFinal"=>$dataFinal,
                "cantidadDias"=>$diasDiferencia+1,
                "dias"=>$dias,
                "datos"=>$datosGenerales
               ];
    }

    public function resumenPlanArchivo(Request $rq){
        $cartera=$rq->cartera;
        $file=$rq->file('archivo');
        if ($file == null) {    
            return "error";       
        }else {
            $datos=Excel::toArray(new CodigosPlanTrabajoImport(), $file);
            $codigos=$datos[0];
            return Plan::resumenPlanArchivo($cartera,$codigos);
        }
    }

    public function insertarPlanArchivo(Request $rq){
        $file=$rq->file('archivo');
        if ($file == null) {    
            return "error";       
        }else {
            $datos=Excel::toArray(new CodigosPlanTrabajoImport(), $file);
            $codigos=$datos[0];
            return Plan::insertarPlanArchivo($rq,$codigos);
        }
    }

    public function actualizarFechaPlan(Request $rq){
        return Plan::actualizarFechaPlan($rq);
    }
}
