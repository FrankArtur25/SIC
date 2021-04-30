<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cartera;
use App\Incidencia;
use App\Respuesta;
use App\Empleado;

class HomeController extends Controller
{
    
    public function inicio(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        $perfil="";
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.menuPrincipal');
        }
        if($tipo_acceso==2 || $tipo_acceso==8){
            return view('gestor.clientes');
        }
    }

    public function menuPrincipal(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.menuPrincipal');
        }else{
            return view('errors.403');
        }
    }

    public function menuClientes(){
        return view('gestor.clientes');
    }

    public function sms(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.sms.panelControl');
        }else{
            return view('errors.403');
        }
    }

    public function smsbandeja(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.sms.bandeja');
        }else{
            return view('errors.403');
        }
    }

    public function smscampanas(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuarioSms();  
            $carteras=json_encode($carteras);
            $rol=2;
            return view('admin.sms.listaCampanas',compact('carteras','rol'));
        }else{
            return view('errors.403');
        }
    }

    public function smscrearcampana(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuarioSms();       
            $carteras=json_encode($carteras);
            $rol=2;
            return view('admin.sms.crearCampana',compact('carteras','rol'));
        }else{
            return view('errors.403');
        }
    }
    
    public function smslistanegranumero(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.sms.listaNegraNumero');
        }else{
            return view('errors.403');
        }
    }

    public function smslistanegraarchivo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.sms.listaNegraArchivo');
        }else{
            return view('errors.403');
        }
    }

    public function smsbuscarlistanegra(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.sms.listaNegraBuscar');
        }else{
            return view('errors.403');
        }
    }

    public function indicadores(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.indicadores.indicadores');
        }else{
            return view('errors.403');
        }
    }

    public function indicadoresoperativos(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.indicadoresOperativos',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indestructuracartera(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.estructuraCartera',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indestructuragestor(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.estructuraGestor',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indcrearplantrabajo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $respuestas=Respuesta::listRespuestasUbicabilidad();
            $carteras=json_encode($carteras);
            $respuestas=json_encode($respuestas);
            return view('admin.indicadores.crearPlan',compact('carteras','respuestas'));
        }else{
            return view('errors.403');
        }
    }
    
    public function indseguimientoplantrabajo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.seguimientoPlan',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indreporteplantrabajo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.indicadores.reportePlan');
        }else{
            return view('errors.403');
        }
    }
    
    public function indreportegeneral(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reporteGeneral',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indreportegestor(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reporteGestor',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indreportegestionhora(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reporteGestionHora',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indreporteprimyultgestion(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reportePrimyUltGestion',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indresumengestion(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reporteResumenGestion',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }
    
    public function indresumengestionconsolidada(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==5 || $tipo_acceso==6){
            return view('admin.indicadores.reporteResumenGestionConsolidada');
        }else{
            return view('errors.403');
        }
    }

    public function indcomparativocartera(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.comparativoCartera',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function indreporteestandar(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==5 || $tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.reporteEstandar',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }
    
    
    public function timingyproyectado(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.timingyproyectado',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }
    
    public function incidencias(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            if($tipo_acceso==1){
                $supervisores=array();
            }else{
                $supervisores=Incidencia::incListaSupervisores();       
            }
            $gestores=Incidencia::incListaGestores();       
            $incidencias=Incidencia::tiposIncidencias();       
            $datos=array();
            $datos=[$supervisores,$gestores,$incidencias];
            $datos=json_encode($datos);
            return view('admin.incidencias.incidencias',compact('datos'));
        }else{
            return view('errors.403');
        }
    }

    public function nuevaincidencia(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.incidencias.registrarIncidencia',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function estadospdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.estadoPdps',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function estandarpdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.estandarPdps',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function pdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.pdps',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function listadopdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.indicadores.listadoPdps',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function comparativapdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==5 || $tipo_acceso==6){
            return view('admin.indicadores.comparativaPdps');
        }else{
            return view('errors.403');
        }
    }

    public function comparativapagos(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==5 || $tipo_acceso==6){
            return view('admin.indicadores.comparativaPagos');
        }else{
            return view('errors.403');
        }
    }

    public function indlistadoactualizaciones(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==5 || $tipo_acceso==6){
            return view('admin.indicadores.listadoActualizaciones');
        }else{
            return view('errors.403');
        }
    }
    
    public function control(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==1 || $tipo_acceso==5 || $tipo_acceso==6 || $tipo_acceso==7){
            return view('admin.indicadores.reporteControl');
        }else{
            return view('errors.403');
        }
    }

    public function predictivo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            return view('admin.predictivo.predictivo');
        }else{
            return view('errors.403');
        }
    }


    public function registrargestiones(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.predictivo.frmGestiones',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function crearpredictivo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.predictivo.frmPredictivo',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function campanaspredictivo(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.predictivo.listaPredictivo',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function crearivr(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.predictivo.frmIvr',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function campanasivr(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.predictivo.listaIvr',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }


    public function mantenimiento(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            return view('admin.mantenimiento.mantenimiento');
        }else{
            return view('errors.403');
        }
    }

    public function registrarusuario(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();  
            $locales=Respuesta::listaOficinas();
            $calls=Respuesta::listaCall();
            $carteras=json_encode($carteras);
            $locales=json_encode($locales);
            $calls=json_encode($calls);
            $codAleatorio='';
            while($codigo = rand(999,9999)){
                $res=Empleado::codigoEmpleado($codigo);
                if(count($res)==0){
                    $codAleatorio=$codigo;
                    break;
                }
            }
            return view('admin.mantenimiento.formEmpleado',compact('carteras','locales','calls','codAleatorio'));
        }else{
            return view('errors.403');
        }
    }

    public function listausuarios(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();  
            $locales=Respuesta::listaOficinas();
            $calls=Respuesta::listaCall();
            $carteras=json_encode($carteras);
            $locales=json_encode($locales);
            $calls=json_encode($calls);
            return view('admin.mantenimiento.listaEmpleado',compact('carteras','locales','calls'));
        }else{
            return view('errors.403');
        }
    }

    public function registrargestor(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();  
            $carteras=json_encode($carteras);
            return view('admin.mantenimiento.formGestor',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function listagestores(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();  
            $gestores=Empleado::listGestoresActivos();
            $usuarios=Empleado::listEmpleadosActivos();
            $carteras=json_encode($carteras);
            $gestores=json_encode($gestores);
            $usuarios=json_encode($usuarios);
            return view('admin.mantenimiento.listaGestores',compact('carteras','usuarios','gestores'));
        }else{
            return view('errors.403');
        }
    }

    public function reportesgenerales(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            return view('admin.reportes.reportesgenerales');
        }else{
            return view('errors.403');
        }
    }

    public function reporteconfirmaciones(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $calls=Respuesta::listaCall();
            $carteras=json_encode($carteras);
            $calls=json_encode($calls);
            return view('admin.reportes.reporteConfirmaciones',compact('carteras','calls'));
        }else{
            return view('errors.403');
        }
    }

    public function reportepdps(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $calls=Respuesta::listaCall();
            $carteras=json_encode($carteras);
            $calls=json_encode($calls);
            return view('admin.reportes.reportePdps',compact('carteras','calls'));
        }else{
            return view('errors.403');
        }
    }

    public function reporteranking(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $calls=Respuesta::listaCall();
            $carteras=json_encode($carteras);
            $calls=json_encode($calls);
            return view('admin.reportes.reporteRanking',compact('carteras','calls'));
        }else{
            return view('errors.403');
        }
    }
    
    public function reporteconfirmacionespagos(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            return view('admin.reportes.reporteConfirmacionesPagos');
        }else{
            return view('errors.403');
        }
    }

    public function generarGestionesFicticias(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $paletas=Respuesta::listRespuestas();
            $carteras=json_encode($carteras);
            $paletas=json_encode($paletas);
            return view('admin.reportes.generarGestionesFicticias',compact('carteras','paletas'));
        }else{
            return view('errors.403');
        }
    }
    
    public function asignacion(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            return view('admin.asignacion.asignacion');
        }else{
            return view('errors.403');
        }
    }

    public function asignacionmultiple(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            $codAleatorio='';
            while($codigo = rand(999,9999)){
                $res=Empleado::codigoAsignacion($codigo);
                if(count($res)==0){
                    $codAleatorio=$codigo;
                    break;
                }
            }
            return view('admin.asignacion.asignacionMultiple',compact('carteras','codAleatorio'));
        }else{
            return view('errors.403');
        }
    }

    public function intercambio(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $usuarios=Empleado::listEmpleadosActivos();
            $usuarios=json_encode($usuarios);
            $carteras=json_encode($carteras);
            $codAleatorio='';
            while($codigo = rand(999,9999)){
                $res=Empleado::codigoAsignacion($codigo);
                if(count($res)==0){
                    $codAleatorio=$codigo;
                    break;
                }
            }
            return view('admin.asignacion.intercambio',compact("usuarios","carteras","codAleatorio"));
        }else{
            return view('errors.403');
        }
    }
    
    public function bitacoraasignacion(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();
            $carteras=json_encode($carteras);
            return view('admin.asignacion.bitacoraAsignacion',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }

    public function asignacionindividual(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            $codAleatorio='';
            while($codigo = rand(999,9999)){
                $res=Empleado::codigoAsignacion($codigo);
                if(count($res)==0){
                    $codAleatorio=$codigo;
                    break;
                }
            }
            return view('admin.asignacion.asignacionIndividual',compact('carteras','codAleatorio'));
        }else{
            return view('errors.403');
        }
    }

    public function cargarPagos(){
        $tipo_acceso=auth()->user()->emp_tip_acc;
        if($tipo_acceso==6){
            $carteras=Cartera::listCarterasUsuario();       
            $carteras=json_encode($carteras);
            return view('admin.reportes.cargarPagos',compact('carteras'));
        }else{
            return view('errors.403');
        }
    }    



}
