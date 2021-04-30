require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('list-clientes', require('./components/Gestor/Clientes.vue').default);
Vue.component('panel-gestor', require('./components/Gestor/PanelGestor.vue').default);
Vue.component('control-llamadas', require('./components/Admin/ControlLlamadas.vue').default);
Vue.component('control-llamadas-gestor', require('./components/Admin/ControlLlamadasGestor.vue').default);
Vue.component('formRegistrarGestion', require('./components/Gestor/FormRegistrarGestion.vue').default);

// sms
Vue.component('sms-bandeja', require('./components/Sms/bandeja.vue').default);
Vue.component('sms-list-campanas', require('./components/Sms/listCampanas.vue').default);
Vue.component('sms-crear-campana', require('./components/Sms/crearCampana.vue').default);
Vue.component('sms-panel-control', require('./components/Sms/panelControl.vue').default);
Vue.component('sms-lista-negra-numero', require('./components/Sms/listaNegraNumero.vue').default);
Vue.component('sms-lista-negra-archivo', require('./components/Sms/listaNegraArchivo.vue').default);
Vue.component('sms-lista-negra-buscar', require('./components/Sms/listaNegraBuscar.vue').default);

// indicadores
Vue.component('detalle-cliente-pdps', require('./components/Indicadores/detalleCliente.vue').default);
Vue.component('estructura-cartera', require('./components/Indicadores/estructuraCartera.vue').default);
Vue.component('estructura-gestor', require('./components/Indicadores/estructuraGestor.vue').default);
Vue.component('seguimiento-plan', require('./components/Indicadores/seguimientoPlan.vue').default);
Vue.component('form-plan', require('./components/Indicadores/formPlan.vue').default);
Vue.component('reporte-plan', require('./components/Indicadores/reportePlan.vue').default);
Vue.component('indicadores-operativos', require('./components/Indicadores/indicadoresOperativos.vue').default);
Vue.component('reporte-general', require('./components/Indicadores/reporteGeneral.vue').default);
Vue.component('reporte-gestor', require('./components/Indicadores/reporteGestor.vue').default);
Vue.component('reporte-gestion-hora', require('./components/Indicadores/reporteGestionHora.vue').default);
Vue.component('reporte-primyult-gestion', require('./components/Indicadores/reportePrimyUltGestion.vue').default);
Vue.component('reporte-resumen-gestion', require('./components/Indicadores/reporteResumenGestion.vue').default);
Vue.component('reporte-resumen-gestion-consolidada', require('./components/Indicadores/reporteResumenGestionConsolidada.vue').default);
Vue.component('comparativo-cartera', require('./components/Indicadores/comparativoCartera.vue').default);
Vue.component('reporte-estandar', require('./components/Indicadores/reporteEstandar.vue').default);
Vue.component('timing-proyectado', require('./components/Indicadores/timingProyectado.vue').default);
Vue.component('estados-pdps', require('./components/Indicadores/pdpsEstados.vue').default);
Vue.component('estandar-pdps', require('./components/Indicadores/pdpsEstandar.vue').default);
Vue.component('pdps', require('./components/Indicadores/pdps.vue').default);
Vue.component('lista-pdps', require('./components/Indicadores/listaPdps.vue').default);
Vue.component('comparativa-pdps', require('./components/Indicadores/comparativaPdps.vue').default);
Vue.component('comparativa-pagos', require('./components/Indicadores/comparativaPagos.vue').default);
Vue.component('listado-actualizaciones', require('./components/Indicadores/listadoActualizaciones.vue').default);
Vue.component('reporte-control', require('./components/Indicadores/reporteControl.vue').default);

// incidencias
Vue.component('form-incidencia', require('./components/Incidencias/formIncidencia.vue').default);
Vue.component('list-incidencias', require('./components/Incidencias/listIncidencias.vue').default);

// predictivo
Vue.component('form-gestiones', require('./components/Predictivo/formGestiones.vue').default);
Vue.component('form-predictivo', require('./components/Predictivo/formPredictivo.vue').default);
Vue.component('lista-predictivo', require('./components/Predictivo/listaPredictivo.vue').default);
Vue.component('form-ivr', require('./components/Predictivo/formIvr.vue').default);
Vue.component('lista-ivr', require('./components/Predictivo/listaIvr.vue').default);

// mantenimiento
Vue.component('form-empleado', require('./components/Mantenimiento/formEmpleado.vue').default);
Vue.component('form-gestor', require('./components/Mantenimiento/formGestor.vue').default);
Vue.component('lista-empleados', require('./components/Mantenimiento/listaEmpleado.vue').default);
Vue.component('lista-gestores', require('./components/Mantenimiento/listaGestores.vue').default);

// reportes
Vue.component('reporte-confirmaciones', require('./components/Reportes/reportConfirmaciones.vue').default);
Vue.component('reporte-pdps', require('./components/Reportes/reportPdps.vue').default);
Vue.component('reporte-ranking', require('./components/Reportes/reportRanking.vue').default);
Vue.component('reporte-confirmaciones-pagos', require('./components/Reportes/reportConfirmacionesPagos.vue').default);
Vue.component('generar-gestiones-ficticias', require('./components/Reportes/generarGestionesFicticias.vue').default);

Vue.component('cargar-pagos', require('./components/Reportes/cargarPagos.vue').default);

//asignacion
Vue.component('migracion-clientes', require('./components/Asignacion/migracionClientes.vue').default);
Vue.component('asignacion-multiple', require('./components/Asignacion/asignacionMultiple.vue').default);
Vue.component('asignacion-individual', require('./components/Asignacion/asignacionIndividual.vue').default);
Vue.component('bitacora-asignacion', require('./components/Asignacion/bitacoraAsignacion.vue').default);


//integracion ocm
Vue.component('integracion', require('./components/ocm/PanelGestor.vue').default);

const app = new Vue({
    el: '#app',
});
