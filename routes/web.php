<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => ['auth']], function(){
    
    Route::get('/', 'HomeController@inicio')->name('inicio');
    Route::get('/home', 'HomeController@inicio')->name('home');
    Route::get('/menu', 'HomeController@menuPrincipal')->name('menu');
    Route::get('/clientes', 'HomeController@menuClientes')->name('clientes');
    
    //clientes
    //Route::get('clientes', function () {return view('gestor.clientes');})->name("clientes");
    Route::post('listClientes', 'ClienteController@listaClientes');
    Route::post('datosEstandar', 'ClienteController@datosEstandar');
    Route::get('datosMes', 'ClienteController@datosMes');
    // Route::get('estadosCampana', 'ClienteController@estadoCampana');
    Route::get('detalleCliente/{id}', 'ClienteController@detalleCliente');
    Route::put('updateEmail', 'ClienteController@updateEmail');
    
    // Route::get('infoCliente/{id}', 'ClienteController@infoCliente');
    Route::get('historicoGestiones/{id}', 'ClienteController@historicoGestiones');
    // Route::get('infoDeuda/{id}', 'ClienteController@infoDeuda');
    
    //telefonos
    Route::get('listaTel/{id}', 'TelefonoController@listaTelefonos');
    Route::post('insertarTel', 'TelefonoController@insertarTelefonos');
    Route::put('actualizarEstadoTelefono', 'TelefonoController@actualizarEstadoTelefono');
    
    //Gestion
    Route::post('insertarGestion', 'GestionController@insertarGestion');
    // Route::get('validarContacto/{id}', 'GestionController@validarContacto');
    // Route::get('validarPDP/{id}', 'GestionController@validarPDP');
    Route::post('validarDetalleIdentico', 'GestionController@validarDetalleIdentico');
    
    //Recordatorio
    Route::post('insertarRecordatorio', 'RecordatorioController@insertarRecordatorio');
    Route::get('listarRecordatorio', 'RecordatorioController@listarRecordatorio');

    //Pagos
    Route::get('listaPagos/{id}', 'PagoController@listaPagos');
    
    //campañas
    Route::get('estadosCampana', 'CampanaController@estadosCampana');
    
    //respuestas
    Route::get('listasPanelBusqueda', 'RespuestaController@listasPanelBusqueda');
    Route::get('listasBusquedaPorCartera/{car}', 'RespuestaController@listasBusquedaPorCartera');
    Route::get('listRespuestas', 'RespuestaController@listRespuestas');
    Route::get('listRespuestasCampo', 'RespuestaController@listRespuestasCampo');
    Route::get('listaMotivosNoPago', 'RespuestaController@listaMotivosNoPago');
    Route::get('listaRespuesta/{ubi}', 'RespuestaController@listaRespuestaUbicabilidad');
    Route::get('listaRespuestaSms/{ubi}', 'RespuestaController@listaRespuestaUbicSms');
    Route::get('listaScore/{cartera}', 'RespuestaController@listaScore');
    Route::get('listaEntidades', 'RespuestaController@listaEntidades');
    Route::get('listaOficinas', 'RespuestaController@listaOficinas');
    // v2
    Route::get('motivo/listaMotivosNoPago', 'RespuestaController@listaMotivosNoPago');
    

    // ACTUALIZAR PAGOS POR cartera
    Route::get('/cargarpagos', 'HomeController@cargarPagos')->name('cargarpagos');
    Route::post('/actualizarPagosCartera', 'PagoController@actualizarPagosCartera')->name('actualizarPagosCartera');
    // Route::post('/importExcelPagos', 'PagoController@importExcelPagos')->name('importExcelPagos');


// SMS--------------------------------------------------------------------------------------------
    // vistas sms
    Route::get('/sms', 'HomeController@sms')->name('sms');
    
    // Bandeja sms
    Route::get('/smsbandeja', 'HomeController@smsbandeja')->name('smsbandeja');
    Route::get('/bandejaMsj', 'SmsBandejaController@bandejaMsj')->name('bandejaMsj');
    Route::get('/chat/{numero}', 'SmsBandejaController@chat')->name('chat');
    
    //campana sms
    Route::get('/smscampanas', 'HomeController@smscampanas')->name('smscampanas');
    Route::get('/smscrearcampana', 'HomeController@smscrearcampana')->name('smscrearcampana');
    Route::post('/buscarCampana', 'SmsCampanaController@buscarCampana')->name('buscarCampana');
    Route::get('/detalleCampana/{id}', 'SmsCampanaController@detalleCampana')->name('detalleCampana');
    Route::get('/condicionCampana/{id}', 'SmsCampanaController@condicionCampana')->name('condicionCampana');
    Route::get('/enviarCampana/{id}', 'SmsCampanaController@enviarCampana')->name('enviarCampana');
    Route::get('/listCampanasDia', 'SmsCampanaController@listCampanasDia')->name('listCampanasDia');
    Route::post('/datosclientesCampana', 'SmsCampanaController@datosclientesCampana')->name('datosclientesCampana');
    Route::post('/insertCampana', 'SmsCampanaController@insertCampana')->name('insertCampana');

    //speech
    Route::get('/carteraSpeech/{cartera}', 'SmsSpeechController@carteraSpeech')->name('carteraSpeech');
    Route::post('/insertSpeech', 'SmsSpeechController@insertSpeech')->name('insertSpeech');
    Route::get('/listEtiquetas', 'SmsSpeechController@etiquetas')->name('listEtiquetas');
    
    //condiciones:entidades,score,prioridad,etc
    Route::get('/tagCondicion/{car}/{tipo}', 'SmsCampanaController@tagCondicion')->name('tagCondicion');

    //carteras
    Route::get('listCarterasUsuario', 'CarteraController@listCarterasUsuario');
    
    //lista negra
    Route::get('/smslistanegranumero', 'HomeController@smslistanegranumero')->name('smslistanegranumero');
    Route::get('/smslistanegraarchivo', 'HomeController@smslistanegraarchivo')->name('smslistanegraarchivo');
    Route::get('/smsbuscarlistanegra', 'HomeController@smsbuscarlistanegra')->name('smsbuscarlistanegra');
    Route::post('cargarListaNegra', 'SmsCampanaController@cargarListaNegra')->name('cargarListaNegra');
    Route::post('insertarListaNegra', 'SmsCampanaController@insertarListaNegra')->name('insertarListaNegra');
    Route::post('buscarListaNegra', 'SmsCampanaController@buscarListaNegra')->name('buscarListaNegra');
    Route::get('retirarListaNegra/{id}', 'SmsCampanaController@retirarListaNegra')->name('retirarListaNegra');
    Route::get('validarEnvio/{id}', 'SmsCampanaController@validarEnvio')->name('validarEnvio');
    

// indicadores---------------------------------------------------------------------------------
    //vistas
    Route::get('/indicadores', 'HomeController@indicadores')->name('indicadores');
    Route::get('/indicadoresoperativos', 'HomeController@indicadoresoperativos')->name('indicadoresoperativos');
    Route::get('/estructuracartera', 'HomeController@indestructuracartera')->name('estructuracartera');
    Route::get('/estructuragestor', 'HomeController@indestructuragestor')->name('estructuragestor');
    Route::get('/crearplantrabajo', 'HomeController@indcrearplantrabajo')->name('crearplantrabajo');
    Route::get('/seguimientoplantrabajo', 'HomeController@indseguimientoplantrabajo')->name('seguimientoplantrabajo');
    Route::get('/reporteplantrabajo', 'HomeController@indreporteplantrabajo')->name('reporteplantrabajo');
    
    
    // indicadores operativos ---------------------------------------------------------------------------------------------------------------
    Route::post('reporteEstructuraCartera', 'EstructuraController@reporteEstructuraCartera');
    Route::post('reporteEstructuraGestionCartera', 'EstructuraController@reporteEstructuraGestionCartera');
    Route::post('reporteEstructuraGestor', 'EstructuraController@reporteEstructuraGestor');
    Route::post('reporteEstructuraGestorCartera', 'EstructuraController@reporteEstructuraGestorCartera');
    Route::get('listaGestores/{cartera}', 'EstructuraController@listaGestores');
    Route::get('listaAsignacion', 'IndicadorController@asignacion');
    Route::post('listaEstructuras', 'IndicadorController@estructuras');
    Route::post('reporteIndicadoresOperativos', 'IndicadorController@reporteIndicadoresOperativos');
    Route::get('descargarEstructuraCarteraCartera/{cartera}/{ubicabilidad}/{estructura}/{valor}/{mes}', 'EstructuraController@descargarEstructuraCarteraCartera');
    Route::get('descargarEstructuraCarteraGestion/{cartera}/{tipo}/{estructura}/{valor}/{fecInicio}/{fecFin}', 'EstructuraController@descargarEstructuraCarteraGestion');
    Route::get('descargarEstructuraGestorCartera/{cartera}/{ubicabilidad}/{estructura}/{valor}/{mes}/{gestor}', 'EstructuraController@descargarEstructuraGestorCartera');
    Route::get('descargarEstructuraGestorGestion/{cartera}/{tipo}/{estructura}/{valor}/{fecInicio}/{fecFin}/{gestor}', 'EstructuraController@descargarEstructuraGestorGestion');
    
    //Plan de Trabajo-----------------------------------------------------------------------------------------------------------------------
    Route::post('listaPlanes', 'PlanController@listaPlanes');
    Route::post('resumenPlan', 'PlanController@resumenPlan');
    Route::post('insertarPlan', 'PlanController@insertarPlan');
    Route::get('usuariosPlan/{id}', 'PlanController@usuariosPlan');
    Route::post('resultadosPlan', 'PlanController@resultadosPlan');
    Route::get('/datosPlanUsuario', 'PlanController@datosPlanUsuario');
    Route::get('/reportePlan', 'PlanController@reportePlan');
    Route::post('/reporteListaPlan', 'PlanController@reporteListaPlan')->name('reporteListaPlan');
    Route::post('/reporteCumplimiento', 'PlanController@reporteCumplimiento')->name('reporteCumplimiento');
    Route::post('resumenPlanArchivo', 'PlanController@resumenPlanArchivo');
    Route::post('insertarPlanArchivo', 'PlanController@insertarPlanArchivo');
    Route::post('actualizarFechaPlan', 'PlanController@actualizarFechaPlan');
        
    //Reporte general------------------------------------------------------------------------------------------------------------------------
    Route::get('/reportegeneral', 'HomeController@indreportegeneral')->name('reportegeneral');
    Route::get('reporteGeneralGestiones/{cartera}/{fecInicio}/{fechaFin}/{perfil}', 'ReporteController@reporteGeneralGestiones');
    // Route::post('reporteGeneralGestiones', 'ReporteController@reporteGeneralGestiones');
    
    //Reporte gestión telef por gestor-------------------------------------------------------------------------------------------------------
    Route::get('/reportegestor', 'HomeController@indreportegestor')->name('reportegestor');
    Route::get('asignacionCall', 'ReporteController@asignacionCall');
    Route::post('reporteResumenGestor', 'ReporteController@reporteResumenGestor');
    Route::post('descargarGestionesGestor', 'ReporteController@descargarGestionesGestor');
        
    //Reporte Primer y Ultima gestion--------------------------------------------------------------------------------------------------------
    Route::get('/reporteprimerayultimagestion', 'HomeController@indreporteprimyultgestion')->name('reporteprimerayultimagestion');
    Route::post('primerayultimagestion', 'ReporteController@primerayultimagestion');

    //Reporte cant de gestiones por hora--------------------------------------------------------------------------------------------------------
    Route::get('/reportegestionhora', 'HomeController@indreportegestionhora')->name('reportegestionhora');
    Route::get('cantGestioneHora/{cartera}', 'ReporteController@cantGestioneHora');
    
    // Reporte gestión--------------------------------------------------------------------------------------------------------------------------
    Route::get('/resumengestion', 'HomeController@indresumengestion')->name('resumengestion');
    Route::get('resumenGestionDia/{cartera}', 'ReporteController@resumenGestionDia');
    Route::get('detalleConfirmaciones/{cartera}', 'ReporteController@detalleConfirmaciones');
    
    // Reporte gestion Consolidada
    Route::get('/resumengestionconsolidada', 'HomeController@indresumengestionconsolidada')->name('resumengestionconsolidada');
    Route::get('resumenGestionConsolidada/{fecha}', 'ReporteController@resumenGestionConsolidada');

    //Reporte Comparativo
    Route::get('/comparativocartera', 'HomeController@indcomparativocartera')->name('comparativocartera');
    Route::post('reportecomparativocartera', 'ReporteController@reportecomparativocartera');

    //Reporte estandar
    Route::get('/reporteestandar', 'HomeController@indreporteestandar')->name('reporteestandar');
    Route::post('/reporteEstandarCartera', 'ReporteController@reporteEstandarCartera')->name('reporteEstandarCartera');
    

    // Timing y Proyectado ---------------------------------------------------------------------------------------------------------------------
    Route::get('/timingyproyectado', 'HomeController@timingyproyectado')->name('timingyproyectado');
    Route::get('timingProyectado/{cartera}', 'TimingController@timingProyectado');

    // incidencias------------------------------------------------------------------------------------------------------------------------
    Route::get('/incidencias', 'HomeController@incidencias')->name('incidencias');
    Route::get('/nuevaincidencia', 'HomeController@nuevaincidencia')->name('nuevaincidencia');
    Route::get('/tiposIncidencias', 'IncidenciaController@tiposIncidencias');
    Route::post('/insertIncidencia', 'IncidenciaController@insertIncidencia');
    Route::post('/buscarIncidencias', 'IncidenciaController@buscarIncidencias');
    Route::put('/editarIncidencia', 'IncidenciaController@editarIncidencia');

    // analisis de pdps-------------------------------------------------------------------------------------------------------------------
    Route::get('/estadospdps', 'HomeController@estadospdps')->name('estadospdps');
    Route::get('/estandarpdps', 'HomeController@estandarpdps')->name('estandarpdps');
    Route::get('/pdps', 'HomeController@pdps')->name('pdps');
    Route::get('/listadopdps', 'HomeController@listadopdps')->name('listadopdps');
    Route::post('/reporteEstadosPdps', 'PdpsController@reporteEstadosPdps')->name('reporteEstadosPdps');
    Route::post('/reporteEstandarPdps', 'PdpsController@reporteEstandarPdps')->name('reporteEstandarPdps');
    Route::post('/reportePdps', 'PdpsController@reportePdps')->name('reportePdps');
    Route::post('/listaPdps', 'PdpsController@listaPdps')->name('listaPdps');
    Route::post('/descargarListaPdps', 'PdpsController@descargarListaPdps')->name('descargarListaPdps');
    Route::get('/comparativapagos', 'HomeController@comparativapagos')->name('comparativapagos');
    Route::get('/comparativapdps', 'HomeController@comparativapdps')->name('comparativapdps');
    Route::post('/comparativaPagosFecha', 'PdpsController@comparativaPagosFecha')->name('comparativaPagosFecha');
    Route::post('/comparativaPdpsFecha', 'PdpsController@comparativaPdpsFecha')->name('comparativaPdpsFecha');
    
    //actualizaciones
    Route::get('/listadoactualizaciones', 'HomeController@indlistadoactualizaciones')->name('listadoactualizaciones');
    Route::post('infoactualizacionpagos', 'ActualizacionController@infoactualizacionpagos');
    Route::post('infoactualizacioncarteras', 'ActualizacionController@infoactualizacioncarteras');


//PREDICTIVO-----------------------------------------------------------------------------------------------------------------------------
    Route::get('/predictivo', 'HomeController@predictivo')->name('predictivo');
    Route::get('/crearpredictivo', 'HomeController@crearpredictivo')->name('crearpredictivo');
    Route::get('/registrargestiones', 'HomeController@registrargestiones')->name('registrargestiones');
    Route::get('/campanaspredictivo', 'HomeController@campanaspredictivo')->name('campanaspredictivo');
    Route::get('/condiciones/{cartera}', 'PredictivoController@condiciones')->name('condiciones');
    Route::post('/calcularCampana', 'PredictivoController@calcularCampana')->name('calcularCampana');
    Route::post('/crearCampana', 'PredictivoController@crearCampana')->name('crearCampana');
    Route::get('/descargarPredictivo/{id}', 'PredictivoController@descargar')->name('descargarPredictivo');
    Route::get('/asignar/{id}/{usuario}', 'PredictivoController@asignar')->name('asignar');
    Route::post('/listaCampanas', 'PredictivoController@listaCampanas')->name('listaCampanas');
    Route::get('/eliminarCampana/{id}', 'PredictivoController@eliminarCampana')->name('eliminarCampana');
    Route::post('/devolverAsignacion', 'PredictivoController@devolverAsignacion')->name('devolverAsignacion');
    Route::get('/datosGestiones/{id}', 'PredictivoController@datosGestiones')->name('datosGestiones');
    Route::get('/generarGestiones/{id}/{total}', 'PredictivoController@generarGestiones')->name('generarGestiones');
    Route::post('/actualizarFechaCampana', 'PredictivoController@actualizarFechaCampana')->name('actualizarFechaCampana');
    Route::get('/descargarReportePredictivo/{id}', 'PredictivoController@descargarReporte')->name('descargarReportePredictivo');
    Route::post('/cargarResultadosPredictivo', 'PredictivoController@cargarResultadosPredictivo')->name('cargarResultadosPredictivo');
    Route::get('/crearivr', 'HomeController@crearivr')->name('crearivr');
    Route::get('/campanasivr', 'HomeController@campanasivr')->name('campanasivr');
    
// REPORTE DE CONTROL -------------------------------------------------------------------------------------------------------------------
    
    Route::get('/control', 'HomeController@control')->name('control');
    Route::get('/reporteControl', 'ControlController@reporteControl');

//MANTENIMIENTO -------------------------------------------------------------------------------------------------------------------------
    Route::get('/mantenimiento', 'HomeController@mantenimiento')->name('mantenimiento');
    Route::get('/registrarusuario', 'HomeController@registrarusuario')->name('registrarusuario');
    Route::post('/insertarEmpleado', 'EmpleadoController@insertarEmpleado')->name('insertarEmpleado');
    Route::get('/codigoEmpleado', 'EmpleadoController@codigoEmpleado')->name('codigoEmpleado');    
    Route::get('/listausuarios', 'HomeController@listausuarios')->name('listausuarios');
    Route::post('/listEmpleados', 'EmpleadoController@listEmpleados')->name('listEmpleados');
    Route::post('/updateEmpleado', 'EmpleadoController@updateEmpleado')->name('updateEmpleado');
    Route::post('/updateClave', 'EmpleadoController@updateClave')->name('updateClave');
    Route::get('/registrargestor', 'HomeController@registrargestor')->name('registrargestor');
    Route::get('/listagestores', 'HomeController@listagestores')->name('listagestores');
    Route::post('/insertarGestor', 'EmpleadoController@insertarGestor')->name('insertarGestor');
    Route::post('/listGestores', 'EmpleadoController@listGestores')->name('listGestores');
    Route::post('/updateFirma', 'EmpleadoController@updateFirma')->name('updateFirma');
    Route::post('/updateGestor', 'EmpleadoController@updateGestor')->name('updateGestor');
    Route::get('/listGestoresActivos', 'EmpleadoController@listGestoresActivos')->name('listGestoresActivos');
    Route::get('/historialLaboral/{firma}', 'EmpleadoController@historialLaboral')->name('historialLaboral');
    Route::post('/registroHistorialLaboral', 'EmpleadoController@registroHistorialLaboral')->name('registroHistorialLaboral');
    Route::post('/updateUsuario', 'EmpleadoController@updateUsuario')->name('updateUsuario');
    

//REPORTES SISTEMAS ---------------------------------------------------------------------------------------------------------------------- 
    Route::get('/reportesgenerales', 'HomeController@reportesgenerales')->name('reportesgenerales');
    Route::get('/reporteconfirmaciones', 'HomeController@reporteconfirmaciones')->name('reporteconfirmaciones');
    Route::get('/generarReporteConfirmaciones/{cartera}/{estructura}/{calls}/{tipoFecha}/{fechaInicio}/{fechaFin}/{columnas}', 'ReporteController@generarReporteConfirmaciones')->name('generarReporteConfirmaciones');
    Route::get('/generarReportePdps/{cartera}/{estructura}/{calls}/{tipoFecha}/{fechaInicio}/{fechaFin}/{columnas}', 'ReporteController@generarReportePdps')->name('generarReportePdps');
    Route::get('/reportepdps', 'HomeController@reportepdps')->name('reportepdps');
    Route::get('/reporteranking', 'HomeController@reporteranking')->name('reporteranking');
    Route::get('/generarReporteRanking/{cartera}/{estructura}/{calls}/{fechaInicio}/{fechaFin}', 'ReporteController@generarReporteRanking')->name('generarReporteRanking');
    Route::get('/reporteconfirmacionespagos', 'HomeController@reporteconfirmacionespagos')->name('reporteconfirmacionespagos');
    Route::get('/generarReporteConfirmacionesPagos/{fechaInicio}/{fechaFin}', 'ReporteController@generarReporteConfirmacionesPagos')->name('generarReporteConfirmacionesPagos');
    Route::get('/gestionesficticias', 'HomeController@generarGestionesFicticias')->name('gestionesFicticias');
    Route::get('/generarficticias/{paleta}/{modalidad}/{cantidad}/{fechaInicio}/{fechaFin}/{cartera}', 'ReporteController@generarGestionesFicticias')->name('generarGestionesFicticias');

// ASIGNACION ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Route::get('/asignacion', 'HomeController@asignacion')->name('asignacion');
    Route::get('/asignacionmultiple', 'HomeController@asignacionmultiple')->name('asignacionmultiple');
    Route::get('/intercambio', 'HomeController@intercambio')->name('intercambio');
    Route::post('/consultarIntercambio', 'EmpleadoController@consultarIntercambio')->name('consultarIntercambio');
    Route::post('/updateIntercambio', 'EmpleadoController@updateIntercambio')->name('updateIntercambio');
    Route::post('/importExcelAsignacion', 'EmpleadoController@importExcelAsignacion')->name('importExcelAsignacion');
    Route::post('/generarAsignacionMultiple', 'EmpleadoController@generarAsignacionMultiple')->name('generarAsignacionMultiple');
    Route::get('/generarCodigoAsignacion', 'EmpleadoController@generarCodigoAsignacion')->name('generarCodigoAsignacion');
    Route::get('/bitacoraasignacion', 'HomeController@bitacoraasignacion')->name('bitacoraasignacion');
    Route::post('/listBitacoraAsignacion', 'EmpleadoController@listBitacoraAsignacion')->name('listBitacoraAsignacion');
    Route::get('/updateRegresarAsignacion/{codigo}/{cartera}', 'EmpleadoController@updateRegresarAsignacion')->name('updateRegresarAsignacion');
    Route::get('/descargarBitacoraRepositorio/{codigo}', 'EmpleadoController@descargarBitacoraRepositorio')->name('descargarBitacoraRepositorio');
    Route::get('/asignacionindividual', 'HomeController@asignacionindividual')->name('asignacionindividual');
    Route::post('/generarAsignacionIndividual', 'EmpleadoController@generarAsignacionIndividual')->name('generarAsignacionIndividual');
    

// ELASTIX ------------------------------------------------------------------------------------------------------------------------------
    // control de llamadas - elastix
    Route::get('panelcontrolllamadas', 'ControlLLamadaController@panelcontrolllamadas')->name('panelcontrolllamadas');
    Route::post('controlLLamadas', 'ControlLLamadaController@controlLLamadas')->name('controlLLamadas');
    Route::get('panelcontrolllamadasgestor', 'ControlLLamadaController@panelcontrolllamadasgestor')->name('panelcontrolllamadasgestor');
    Route::post('controlLLamadasGestor', 'ControlLLamadaController@controlLLamadasGestor')->name('controlLLamadasGestor');
    
    // empleado
    Route::get('agentesElastix/{cartera}', 'EmpleadoController@agentesElastix')->name('agentesElastix');    

    
    
// integracion OCM -------------------------------------------------------------------------------
    Route::get('datosCliente/{id}/{tel}', 'ClienteController@orbelite');
    Route::get('datosCliente/datos/listaRespuesta/{ubi}', 'RespuestaController@listaRespuestaUbicabilidad');
    Route::post('datosCliente/insertarGestion', 'GestionController@insertarGestion');
    Route::get('datosCliente/datos/listaPagos/{id}', 'PagoController@listaPagos');
    Route::get('datosCliente/datos/historicoGestiones/{id}', 'ClienteController@historicoGestiones');
    // Route::post('datosCliente/insertarTel', 'TelefonoController@insertarTelefonos');
    Route::get('datosCliente/datos/listaTel/{id}', 'TelefonoController@listaTelefonos');
    Route::put('datosCliente/updateEmail', 'ClienteController@updateEmail');
    Route::get('datosCliente/datos/motivo/listaMotivosNoPago', 'RespuestaController@listaMotivosNoPago');
    

//errors---------------------------------------------------------------------------
    // 403
    Route::get('/403', function(){return view('errors.403');});
    
});

