<template>
    <div >
        <div  class="modal" tabindex="-1" role="dialog" id="modalCarga" >
            <div v-if="loadingModal" class="d-flex justify-content-center align-items-center" style="margin-top:150px;">
                <div>
                    <span class="spinner-border spinner-border-xl text-white ml-3" role="status" aria-hidden="true"></span>
                    <p style="font-20px;" class="text-white">Cargando...</p>
                </div>
            </div>
            <!-- <div v-else class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center">
                    <p><i class="fa fa-thumbs-up fa-4x text-white"></i></p>
                    <p style="font-20px;" class="text-white">Plan de Trabajo Registrado!</p>
                </div>
            </div> -->
        </div>
        <div v-if="viewForm" class="form-crear">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-bold">Nombre de Cartera</label>
                            <select class="form-control" v-model="datos.cartera" @change="llenarScore(datos.cartera)">
                                <option value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-bold">Nombre de Plan de Trabajo</label>
                            <input type="text" class="form-control" v-model="datos.nombre">
                        </div>
                    </div>
            </div>
            <nav class="mt-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a @click="seleccionTab(1)" class="nav-item nav-link active text-dark" id="nav-criterios-tab" data-toggle="tab" href="#nav-criterios" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-filter pr-1"></i>Selección de Criterios</a>
                    <a @click="seleccionTab(2)" class="nav-item nav-link text-dark" id="nav-codigos-tab" data-toggle="tab" href="#nav-codigos" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fa fa-file-alt pr-1"></i>Cargar Archivo</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active py-3" id="nav-criterios" role="tabpanel" aria-labelledby="nav-cartera-tab">            
                    <div class="row mb-4">
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="tramo" class="col-form-label text-dark text-righ"><b>Tramo</b></label>
                            <div class="form-check" v-for="(item,index) in tramos" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="tramos" type="checkbox" :value="item" checked v-model="arrayTramos">{{item}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <label for="dep" class="col-form-label text-dark text-center"><b>Departamento</b></label>
                            <div class="row px-0">
                                <div class="col-xs-6 col-md-6 col-lg-6 mr-0 pr-0">
                                    <div class="form-check" v-for="(item,index) in departamentos" :key="index" v-if="index<=4">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="deps" type="checkbox" :value="item.valor" v-model="arrayDepartamentos">{{item.nombre}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-6 col-lg-6 mr-0 pr-0">
                                    <div class="form-check" v-for="(item,index) in departamentos" :key="index" v-if="index>4">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="deps" type="checkbox" :value="item.valor" v-model="arrayDepartamentos">{{item.nombre}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="pri" class="col-form-label text-dark text-righ"><b>Prioridad</b></label>
                            <div class="form-check" v-for="(item,index) in prioridad" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="prioridades" type="checkbox" :value="item.valor" v-model="arrayPrioridad">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="situacion" class="col-form-label text-dark text-righ"><b>Situación Laboral</b></label>
                            <div class="form-check" v-for="(item,index) in situacion_laboral" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input " name="situaciones" type="checkbox" :value="item.valor" v-model="arraySituacion">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="call" class="col-form-label text-dark text-righ"><b>Call</b></label>
                            <div class="form-check" v-for="(item,index) in call" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="calls" type="checkbox" :value="item.valor" v-model="arrayCall">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="sueldo" class="col-form-label text-dark text-righ"><b>Rango Sueldo</b></label>
                            <div class="form-check" v-for="(item,index) in sueldos" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="sueldos" type="checkbox" :value="item.valor" v-model="arraySueldos">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="capital" class="col-form-label text-dark text-center"><b>Rango Capital</b></label>
                            <div class="form-check" v-for="(item,index) in capital" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="capitales" type="checkbox" :value="item.valor" v-model="arrayCapital">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="deuda" class="col-form-label text-dark text-righ"><b>Rango Deuda</b></label>
                            <div class="form-check" v-for="(item,index) in deuda" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="deudas" type="checkbox" :value="item.valor" v-model="arrayDeuda">{{item.nombre}}
                                </label>
                            </div>   
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="importe" class="col-form-label text-dark text-righ"><b>Rango IC</b></label>
                            <div class="form-check" v-for="(item,index) in importe" :key="index">
                                <label class="form-check-label" >
                                    <input class="form-check-input" name="importes" type="checkbox" :value="item.valor" v-model="arrayImporte">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label class="col-form-label text-dark text-righ"><b>Entidades</b></label>
                            <div class="form-check" v-for="(item,index) in entidades" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="entidades" type="checkbox" :value="item" v-model="arrayEntidades">{{item}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2" v-if="score!=''">
                            <label class="col-form-label text-dark text-righ"><b>Score</b></label>
                            <div class="form-check" v-for="(item,index) in score" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="score" type="checkbox" :value="item.valor" v-model="arrayScore">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label class="col-form-label text-dark text-righ"><b>Tipo Cliente</b></label>
                            <div class="form-check" v-for="(item,index) in tipoCliente" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="clientes" type="checkbox" :value="item.valor" v-model="arrayTipo">{{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="ubic" class="col-form-label text-dark text-righ"><b>Ubicabilidad</b></label>
                            <div class="form-check" v-for="(item,index) in ubicabilidad" :key="index">
                                <input class="form-check-input" name="ubics" type="checkbox" :value="item.valor" v-model="arrayUbicabilidad" @click="llenarRespuestas(item.valor);cantidadClick++" :id="'ubic'+index">
                                <label class="form-check-label" :for="'ubic'+index">{{item.nombre}}</label>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8 col-xl-8">
                            <label for="ubic" class="col-form-label text-dark text-righ"><b>Respuestas</b></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check" v-for="(item,index) in respuestas" :key="index">
                                        <label class="form-check-label" v-if="index<=6" >
                                            <input class="form-check-input" name="rptas" type="checkbox" :value="item.valor" v-model="arrayRespuestas" :disabled="item.bloqueo">{{item.nombre}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check" v-for="(item,index) in respuestas" :key="index">
                                        <label class="form-check-label" v-if="index>6 && index<=12">
                                            <input class="form-check-input" name="rptas" type="checkbox" :value="item.valor" v-model="arrayRespuestas" :disabled="item.bloqueo">{{item.nombre}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check" v-for="(item,index) in respuestas" :key="index" >
                                        <label class="form-check-label" v-if="index>12">
                                            <input class="form-check-input" name="rptas" type="checkbox" :value="item.valor" v-model="arrayRespuestas" :disabled="item.bloqueo">{{item.nombre}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2">
                            <label for="ubic" class="col-form-label text-dark text-righ"><b>Gestiones Mes</b></label>
                            <div class="form-check" v-for="(item,index) in gestiones" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="ubics" type="checkbox" :value="item.valor" v-model="arrayGestiones" :disabled="item.bloqueo">
                                    {{item.nombre}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <label for="ubic" class="col-form-label text-dark text-righ"><b>Última Gestión</b></label>
                            <div class="form-check" v-for="(item,index) in fechas" :key="index">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="ubics" type="checkbox" :value="item.valor" v-model="arrayFechas">
                                    {{item.nombre}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade pb-5" id="nav-codigos" role="tabpanel" aria-labelledby="nav-cartera-tab">
                    <input type="file" class="form-control" accept=".xlsx" @change="obtenerArchivo">
                </div>
            </div>
            <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-bold">Horario de Atención Desde</label>
                            <input type="datetime-local" class="form-control" v-model="datos.fechaInicio">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-bold">Horario de Atención Hasta</label>
                            <input type="datetime-local" class="form-control" v-model="datos.fechaFin">
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-bold">Escribir un speech</label>
                        <textarea name="" id="" class="form-control" rows="3" v-model="datos.speech"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="" @click.prevent="verResumen()" class="btn btn-outline-blue px-5 mt-2 mb-5">
                        <span v-if="spinnerbuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Continuar
                    </a>
                </div>
            </div>
        </div>
        <div v-else class="form-resumen">
            <div>
                <div class="row fadeInDown">
                    <div class="col-md-5">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="font-bold text-center bg-gray">RESUMEN DE PLAN DE TRABAJO</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Cartera</td>
                                    <td class="px-3">{{datos.nombreCartera}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Plan de Trabajo</td>
                                    <td class="px-3">{{datos.nombre}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Cant. Clientes</td>
                                    <td class="px-3">{{formatoNumero(datos.total,'C')}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Fecha</td>
                                    <td class="px-3">{{datos.fechaInicio}} - {{datos.fechaFin}}</td>
                                </tr>
                                <!-- <tr>
                                    <td class="px-3 bg-gray">Tramo</td>
                                    <td class="px-3">{{detalleCondiciones.tramo}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Departamento</td>
                                    <td class="px-3">{{detalleCondiciones.departamento}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Prioridad</td>
                                    <td class="px-3">{{detalleCondiciones.prioridad}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Situación Laboral</td>
                                    <td class="px-3">{{detalleCondiciones.situacion}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Call</td>
                                    <td class="px-3">{{detalleCondiciones.call}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Rango Sueldo</td>
                                    <td class="px-3">{{detalleCondiciones.sueldo}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Rango Capital</td>
                                    <td class="px-3">{{detalleCondiciones.capital}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Rango Deuda</td>
                                    <td class="px-3">{{detalleCondiciones.deuda}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Rango Importe</td>
                                    <td class="px-3">{{detalleCondiciones.importe}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Ubicabilidad</td>
                                    <td class="px-3">{{detalleCondiciones.ubicabilidad}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Entidad</td>
                                    <td class="px-3">{{detalleCondiciones.entidad}}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 bg-gray">Tipo de Clientes</td>
                                    <td class="px-3">{{detalleCondiciones.tipo}}</td>
                                </tr> -->
                                <tr>
                                    <td class="px-3 bg-gray">Speech</td>
                                    <td class="px-3">{{datos.speech}}</td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <!-- seleccion -->
                        <div class="row" v-if="opcionTab==1">
                            <div class="col-md-10">
                                <div class="d-flex" style="gap:10px;">
                                    <div class="form-group" style="width:40%">
                                        <label for="">Cant. Max. Clientes</label>
                                        <input type="text" class="form-control" @keypress="soloNumeros" @keyup="total()" v-model="ajustes.cantidad" >
                                    </div>
                                    <div class="form-group" style="width:60%">
                                        <label for="">Ordenar por</label>
                                        <select class="form-control" v-model="ajustes.orden">
                                            <option value="">Seleccionar</option>
                                            <optgroup label="Ascendente">
                                                <option value="4">Capital</option>
                                                <option value="5">Deuda</option>
                                                <option value="6">IC</option>
                                            </optgroup>
                                            <optgroup label="Descendente">   
                                                <option value="1">Capital</option>
                                                <option value="2">Deuda</option>
                                                <option value="3">IC</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tabla de datos -->
                        <table class="table" id="tablaUsuarios">
                            <tbody>
                                <tr class="text-center bg-gray font-bold">
                                    <td>Código</td>
                                    <td>Gestor</td>
                                    <td>Cant. Clientes</td>
                                    <td class="border-0 bg-white"></td>
                                </tr>
                                <tr v-for="(item,index) in usuarios" :key="index">
                                    <td class="text-center">{{item.usuario}}</td>
                                    <td class="px-3">{{item.gestor}}</td>
                                    <td class="text-center" id="colcant" v-if="ajustes.cantidad==''">{{formatoNumero(item.cantidad,'C')}}</td>
                                    <td class="text-center" id="colcant" v-else>{{ajustes.cantidad>=item.cantidad?formatoNumero(item.cantidad,'C'):formatoNumero(ajustes.cantidad,'C')}}</td>
                                    <td class="text-center border-0 bg-white">
                                        <div class="form-check" v-if="opcionTab==1">
                                            <input class="form-check-input" name="clientes" type="checkbox" :id="'check'+index" checked @change="seleccionCheck(index)">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="">
                                <tr class="text-center bg-gray font-bold">
                                    <td colspan="2">Total clientes seleccionados</td>
                                    <td>{{formatoNumero(totalSelecionado,'C')}}</td>
                                    <td class="border-0 bg-white"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-2 mb-5">
                        <a href="" class="btn btn-outline-blue" @click.prevent="regresar()">Regresar</a>
                        <a href="" class="btn btn-blue" v-if="usuarios.length>0" @click.prevent="generarPlan()">Generar Plan de Trabajo</a>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras','rpta'],
        data() {
            return {
                spinnerbuscar:false,
                viewForm:true,
                loadingModal:false,
                datos:{cartera:'',fechaInicio:'',fechaFin:'',nombre:'',speech:'',detalle:'',total:0,nombreCartera:''},
                score:[],
                respuestas:[],
                tramos:[2016,2017,2018,2019,2020],
                departamentos:[{valor:"'Lambayeque'",nombre:'Lambayeque'},{valor:"'Libertad'",nombre:'Trujillo'},{valor:"'Piura'",nombre:'Piura'},{valor:"'Ancash'",nombre:'Ancash'},{valor:"'Lima'",nombre:'Lima'},{valor:"'Arequipa'",nombre:'Arequipa'},{valor:"'Ica'",nombre:'Ica'},{valor:"'Cajamarca'",nombre:'Cajamarca'},{valor:"'Callao'",nombre:'Callao'},{valor:"'Otros'",nombre:'Otros'}],
                prioridad:[{valor:"'Sin Dato'",nombre:'Sin Dato'},{valor:"'P1'",nombre:'P1'},{valor:"'P2'",nombre:'P2'},{valor:"'P3'",nombre:'P3'}],
                situacion_laboral:[{valor:"'Dependiente'",nombre:'Dependiente'},{valor:"'Independiente'",nombre:'Independiente'},{valor:"'Informal'",nombre:'Informal'},{valor:"'Mixto'",nombre:'Mixto'},{valor:"'Sin Dato'",nombre:'Sin Dato'}],
                call:[{valor:"'Call 01'",nombre:'Call 01'},{valor:"'Call 02'",nombre:'Call 02'},{valor:"'Call 03'",nombre:'Call 03'},{valor:"'Sin Call'",nombre:'Sin Call'}],
                sueldos:[{valor:"'AA'",nombre:'0-500'},{valor:"'BB'",nombre:'500-100'},{valor:"'CC'",nombre:'1000-3000'},{valor:"'DD'",nombre:'3000 +'},{valor:"'Sin Dato'",nombre:'Sin Dato'}],
                capital:[{valor:"'AA'",nombre:'0-500'},{valor:"'BB'",nombre:'500-100'},{valor:"'CC'",nombre:'1000-3000'},{valor:"'DD'",nombre:'3000 +'}],
                deuda:[{valor:"'AA'",nombre:'0-500'},{valor:"'BB'",nombre:'500-100'},{valor:"'CC'",nombre:'1000-3000'},{valor:"'DD'",nombre:'3000 +'}],
                importe:[{valor:"'AA'",nombre:'0-500'},{valor:"'BB'",nombre:'500-100'},{valor:"'CC'",nombre:'1000-3000'},{valor:"'DD'",nombre:'3000 +'}],
                ubicabilidad:[{valor:"'C-F-R-N'",nombre:'C-F-R-N'},{valor:"'Contacto'",nombre:'Contacto'},{valor:"'No Disponible'",nombre:'No Disponible'},{valor:"'No Contacto'",nombre:'No Contacto'},{valor:"'Ilocalizado'",nombre:'Ilocalizado'},{valor:"'Sin Gestión'",nombre:'Sin Gestión'}],
                entidades:[1,2,3,4],
                tipoCliente:[{valor:"'Nuevos/Nuevos Castigo'",nombre:'Nuevos/Nuevos Castigo'},{valor:"'Otros'",nombre:'Otros'}],
                fechas:[{valor:"'Hoy'",nombre:'Hoy'},{valor:"'Hace 1 día'",nombre:'Hace 1 día'},{valor:"'Hace 2 días'",nombre:'Hace 2 días'},{valor:"'Hace 3 días'",nombre:'Hace 3 días'},{valor:"'Hace más de 3 días'",nombre:'Hace más de 3 días'}],
                gestiones:[{valor:"'0'",nombre:'0',bloqueo:false},{valor:"'1'",nombre:'1',bloqueo:false},{valor:"'2'",nombre:'2',bloqueo:false},{valor:"'3'",nombre:'3',bloqueo:false},{valor:"'4+'",nombre:'4+',bloqueo:false}],
                arrayTramos:[],
                arrayDepartamentos:[],
                arrayPrioridad:[],
                arraySituacion:[], 
                arrayCall:[],
                arraySueldos:[],
                arrayCapital:[],
                arrayDeuda:[],
                arrayImporte:[],
                arrayUbicabilidad:[],
                arrayEntidades:[],
                arrayTipo:[],
                arrayUsuarios:[],
                arrayScore:[],
                arrayRespuestas:[],
                arrayRespuestasNombres:[],
                arrayFechas:[],
                arrayGestiones:[],
                usuarios:[],
                totalSelecionado:0,
                detalleCondiciones:{tramo:'',departamento:'',prioridad:'',situacion:'',call:'',sueldo:'',capital:'',deuda:'',importe:'',ubicabilidad:'',entidad:'',tipo:'',score:'',respuesta:'',respuestaNombres:'',fechas:'',gestiones:''},
                totales:{tramo:'',departamento:'',prioridad:'',situacion:'',call:'',sueldo:'',capital:'',deuda:'',importe:'',ubicabilidad:'',entidad:'',tipo:'',score:'',respuesta:'',respuestaNombres:'',fechas:'',gestiones:''},
                ajustes:{cantidad:'',orden:''},
                cantidadClick:0,
                opcionTab:1,
                archivo:''
            }
        },
        created(){
            this.SeleccionarTodo();
            this.llenarRespuestas('');
        },
        methods:{
            SeleccionarTodo(){
                this.arrayTramos=[];
                this.arrayDepartamentos=[];
                this.arrayPrioridad=[];
                this.arraySituacion=[];
                this.arrayCall=[];
                this.arraySueldos=[];
                this.arrayCapital=[];
                this.arrayDeuda=[];
                this.arrayImporte=[];
                this.arrayUbicabilidad=[];
                this.arrayEntidades=[];
                this.arrayTipo=[];
                this.arrayUsuarios=[];
                this.arrayFechas=[];
                this.arrayGestiones=[];

                this.tramos.forEach(element => {
                    this.arrayTramos.push(element);
                });
                this.departamentos.forEach(element => {
                    this.arrayDepartamentos.push(element.valor);
                });
                this.prioridad.forEach(element => {
                    this.arrayPrioridad.push(element.valor);
                });
                this.situacion_laboral.forEach(element => {
                    this.arraySituacion.push(element.valor);
                });
                this.call.forEach(element => {
                    this.arrayCall.push(element.valor);
                });
                this.sueldos.forEach(element => {
                    this.arraySueldos.push(element.valor);
                });
                this.capital.forEach(element => {
                    this.arrayCapital.push(element.valor);
                });
                this.deuda.forEach(element => {
                    this.arrayDeuda.push(element.valor);
                });
                this.importe.forEach(element => {
                    this.arrayImporte.push(element.valor);
                });
                this.ubicabilidad.forEach(element => {
                    this.arrayUbicabilidad.push(element.valor);
                });
                this.entidades.forEach(element => {
                    this.arrayEntidades.push(element);
                });
                this.tipoCliente.forEach(element => {
                    this.arrayTipo.push(element.valor);
                });
                this.fechas.forEach(element => {
                    this.arrayFechas.push(element.valor);
                });
                this.gestiones.forEach(element => {
                    this.arrayGestiones.push(element.valor);
                });
                this.totales.tramo=this.tramos.length;
                this.totales.departamento=this.departamentos.length;
                this.totales.prioridad=this.prioridad.length;
                this.totales.situacion=this.situacion_laboral.length;
                this.totales.call=this.call.length;
                this.totales.sueldo=this.sueldos.length;
                this.totales.capital=this.capital.length;
                this.totales.deuda=this.deuda.length;
                this.totales.importe=this.importe.length;
                this.totales.ubicabilidad=this.ubicabilidad.length;
                this.totales.entidad=this.entidades.length;
                this.totales.tipo=this.tipoCliente.length;
                this.totales.fechas=this.fechas.length;
                this.totales.gestiones=this.gestiones.length;
            },
            verResumen(){
                if(this.datos.cartera!='' && this.datos.nombre!='' && this.datos.fechaInicio!='' && this.datos.fechaFin!=''){
                    if(this.opcionTab==1){
                        this.totalSelecionado=0;
                        this.datos.total=0;
                        this.arrayUsuarios=[];
                        this.usuarios=[];
                        var parametros={
                            cartera:this.datos.cartera,
                            tramo:this.arrayTramos.length==this.totales.tramo?["'TODOS'"]:this.arrayTramos,
                            departamento:this.arrayDepartamentos.length==this.totales.departamento?["'TODOS'"]:this.arrayDepartamentos,
                            prioridad:this.arrayPrioridad.length==this.totales.prioridad?["'TODOS'"]:this.arrayPrioridad,
                            situacion:this.arraySituacion.length==this.totales.situacion?["'TODOS'"]:this.arraySituacion,
                            call:this.arrayCall.length==this.totales.call?["'TODOS'"]:this.arrayCall,
                            sueldo:this.arraySueldos.length==this.totales.sueldo?["'TODOS'"]:this.arraySueldos,
                            deuda:this.arrayDeuda.length==this.totales.deuda?["'TODOS'"]:this.arrayDeuda,
                            capital:this.arrayCapital.length==this.totales.capital?["'TODOS'"]:this.arrayCapital,
                            importe:this.arrayImporte.length==this.totales.importe?["'TODOS'"]:this.arrayImporte,
                            ubicabilidad:this.arrayUbicabilidad.length==this.totales.ubicabilidad?["'TODOS'"]:this.arrayUbicabilidad,
                            entidad:this.arrayEntidades.length==this.totales.entidad?["'TODOS'"]:this.arrayEntidades,
                            tipoCliente:this.arrayTipo.length==this.totales.tipo?["'TODOS'"]:this.arrayTipo,
                            score:this.arrayScore.length==this.totales.score?["'TODOS'"]:this.arrayScore,
                            respuestas:this.arrayRespuestas.length==this.totales.respuesta?["'TODOS'"]:this.arrayRespuestas,
                            fechas:this.arrayFechas.length==this.totales.fechas?["'TODOS'"]:this.arrayFechas,
                            gestiones:this.arrayGestiones.length==this.totales.gestiones?["'TODOS'"]:this.arrayGestiones
                        };
                        
                        this.spinnerbuscar=true;
                        axios.post("resumenPlan",parametros).then(res=>{
                            if(res.data){
                                this.usuarios=res.data;
                                this.spinnerbuscar=false;
                                for(var i=0;i<this.carteras.length;i++){
                                    if(this.datos.cartera==this.carteras[i].id){
                                        this.datos.nombreCartera=this.carteras[i].cartera;
                                    }
                                }
                                for(var i=0;i<this.usuarios.length;i++){
                                    this.datos.total+=parseInt(this.usuarios[i].cantidad);
                                    this.arrayUsuarios.push("'"+this.usuarios[i].usuario+"'");
                                }
                                this.totalSelecionado=this.datos.total;
                                this.viewForm=false;
                            }
                        })       
                    }

                    if(this.opcionTab==2){
                        if(this.archivo!=""){
                            this.spinnerbuscar=true;
                            let formData= new FormData();
                            formData.append("cartera",this.datos.cartera);
                            formData.append("archivo",this.archivo);
                            axios.post("resumenPlanArchivo",formData).then(res=>{
                                if(res.data){
                                    this.usuarios=res.data;
                                    this.spinnerbuscar=false;
                                    for(var i=0;i<this.carteras.length;i++){
                                        if(this.datos.cartera==this.carteras[i].id){
                                            this.datos.nombreCartera=this.carteras[i].cartera;
                                        }
                                    }
                                    for(var i=0;i<this.usuarios.length;i++){
                                        this.datos.total+=parseInt(this.usuarios[i].cantidad);
                                        this.arrayUsuarios.push("'"+this.usuarios[i].usuario+"'");
                                    }
                                    this.totalSelecionado=this.datos.total;
                                    this.viewForm=false;
                                }
                            });
                        }
                    }
                }
            },
            seleccionCheck(index){
                const filas=document.querySelectorAll("#tablaUsuarios tbody tr #colcant");
                if( $('#check'+index).prop('checked') ) {
                    this.arrayUsuarios.push("'"+this.usuarios[index].usuario+"'");
                    this.totalSelecionado+=parseInt((filas[index].innerHTML).replace(",", ""));
                }else{
                    this.totalSelecionado-=parseInt((filas[index].innerHTML).replace(",", ""));
                    for(var i=0;i<this.arrayUsuarios.length;i++){
                        if(this.arrayUsuarios[i]=="'"+this.usuarios[index].usuario+"'"){
                            this.arrayUsuarios.splice(i,1);
                        }
                    }
                }
            },
            regresar(){
                this.viewForm=true;
            },
            total() {
                this.totalSelecionado=0;
                const filas=document.querySelectorAll("#tablaUsuarios tbody tr #colcant");
                let i=0;
                filas.forEach((fila) => {
                    if( $('#check'+i).prop('checked') ) {
                        this.totalSelecionado+=parseInt((fila.innerHTML).replace(",", ""));
                    }
                    i++;
                });
            },
            generarPlan(){
                this.loadingModal=true;
                $('#modalCarga').modal({backdrop: 'static', keyboard: false});
                if(this.opcionTab==1){
                    this.arrayRespuestasNombres=[];
                    this.arrayRespuestas.forEach(el => {
                        this.rpta.forEach(r => {
                            if(r.res_id==el){
                                this.arrayRespuestasNombres.push(r.res_des);
                            }
                        });
                    });
                    var parametros={
                        cartera:this.datos.cartera,
                        plan:this.datos.nombre,
                        nombreCartera:this.datos.nombreCartera,
                        total:this.totalSelecionado,
                        speech:this.datos.speech,
                        fechaInicio:this.datos.fechaInicio,
                        fechaFin:this.datos.fechaFin,
                        usuarios:this.arrayUsuarios,
                        tramo:this.arrayTramos.length==this.totales.tramo?["'TODOS'"]:this.arrayTramos,
                        departamento:this.arrayDepartamentos.length==this.totales.departamento?["'TODOS'"]:this.arrayDepartamentos,
                        prioridad:this.arrayPrioridad.length==this.totales.prioridad?["'TODOS'"]:this.arrayPrioridad,
                        situacion:this.arraySituacion.length==this.totales.situacion?["'TODOS'"]:this.arraySituacion,
                        call:this.arrayCall.length==this.totales.call?["'TODOS'"]:this.arrayCall,
                        sueldo:this.arraySueldos.length==this.totales.sueldo?["'TODOS'"]:this.arraySueldos,
                        deuda:this.arrayDeuda.length==this.totales.deuda?["'TODOS'"]:this.arrayDeuda,
                        capital:this.arrayCapital.length==this.totales.capital?["'TODOS'"]:this.arrayCapital,
                        importe:this.arrayImporte.length==this.totales.importe?["'TODOS'"]:this.arrayImporte,
                        ubicabilidad:this.arrayUbicabilidad.length==this.totales.ubicabilidad?["'TODOS'"]:this.arrayUbicabilidad,
                        entidad:this.arrayEntidades.length==this.totales.entidad?["'TODOS'"]:this.arrayEntidades,
                        tipoCliente:this.arrayTipo.length==this.totales.tipo?["'TODOS'"]:this.arrayTipo,
                        score:this.arrayScore.length==this.totales.score?["'TODOS'"]:this.arrayScore,
                        respuestas:this.arrayRespuestas.length==this.totales.respuesta?["'TODOS'"]:this.arrayRespuestas,
                        respuestasNombres:this.arrayRespuestasNombres.length==this.totales.respuesta?["'TODOS'"]:this.arrayRespuestasNombres,
                        fechas:this.arrayFechas.length==this.totales.fechas?["'TODOS'"]:this.arrayFechas,
                        gestiones:this.arrayGestiones.length==this.totales.gestiones?["'TODOS'"]:this.arrayGestiones,
                        orden:this.ajustes.orden,
                        cantidad:this.ajustes.cantidad
                    };
                    axios.post("insertarPlan",parametros).then(res=>{
                        if(res.data=="ok"){
                            this.loadingModal=false;
                            this.limpiarCampos();
                            this.viewForm=true;
                            setTimeout(() => $('#modalCarga').modal('hide'), 400);
                            toastr.info('Plan de trabajo registrado con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                        }
                    });
                }
                if(this.opcionTab==2){
                    let formData= new FormData();
                    formData.append("cartera",this.datos.cartera);
                    formData.append("archivo",this.archivo);
                    formData.append("plan",this.datos.nombre);
                    formData.append("nombreCartera",this.datos.nombreCartera);
                    formData.append("total",this.totalSelecionado);
                    formData.append("speech",this.datos.speech);
                    formData.append("fechaInicio",this.datos.fechaInicio);
                    formData.append("fechaFin",this.datos.fechaFin);
                    formData.append("usuarios[]",this.arrayUsuarios);
                    axios.post("insertarPlanArchivo",formData).then(res=>{
                        if(res.data=="ok"){
                            this.loadingModal=false;
                            this.limpiarCampos();
                            this.archivo='';
                            this.viewForm=true;
                            setTimeout(() => $('#modalCarga').modal('hide'), 400);
                            toastr.info('Plan de trabajo registrado con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                        }
                    });
                }
            },
            limpiarCampos(){
                this.datos.cartera='';
                this.datos.nombre='';
                this.datos.speech='';
                this.datos.detalle='';
                this.datos.fechaInicio='';
                this.datos.fechaFin='';
                this.totalSelecionado=0;
                this.datos.total=0;
                this.datos.nombreCartera='';
                this.SeleccionarTodo();
                this.ajustes.orden='';
                this.ajustes.cantidad='';
                this.score=[];
                this.arrayScore=[];
                this.totales.score='';
                this.respuestas=[];
                this.totales.respuesta='';
                this.llenarRespuestas('');
            },
            llenarRespuestas(ubic){
                if(ubic==''){
                    this.rpta.forEach(r => {
                        this.respuestas.push({valor:r.res_id,nombre:r.res_des,ubicabilidad:r.ubicabilidad,bloqueo:false});
                    });
                }else{                    
                    if(this.arrayUbicabilidad.indexOf(ubic)==-1){
                        for(let i=0;i<this.respuestas.length;i++){
                            if("'"+this.respuestas[i].ubicabilidad+"'"===ubic){
                                // this.respuestas.splice(i,1);
                                this.respuestas[i].bloqueo=false;
                            }
                        }
                        if(ubic=="'Sin Gestión'" && this.arrayUbicabilidad.length==0){      
                            this.gestiones[1].bloqueo=true;
                            this.gestiones[2].bloqueo=true;
                            this.gestiones[3].bloqueo=true;
                            this.gestiones[4].bloqueo=true;
                        }else{
                            this.gestiones[1].bloqueo=false;
                            this.gestiones[2].bloqueo=false;
                            this.gestiones[3].bloqueo=false;
                            this.gestiones[4].bloqueo=false;
                        }
                    }else{
                        for(let i=0;i<this.respuestas.length;i++){
                            if("'"+this.respuestas[i].ubicabilidad+"'"===ubic){
                                this.respuestas[i].bloqueo=true;
                            }
                        }                   
        
                        if(this.arrayUbicabilidad.length==2){
                            if(this.arrayUbicabilidad.indexOf("'Sin Gestión'")>-1){
                                this.gestiones[1].bloqueo=true;
                                this.gestiones[2].bloqueo=true;
                                this.gestiones[3].bloqueo=true;
                                this.gestiones[4].bloqueo=true;
                            }
                        }

                        if(ubic=="'Sin Gestión'"){      
                            this.gestiones[1].bloqueo=false;
                            this.gestiones[2].bloqueo=false;
                            this.gestiones[3].bloqueo=false;
                            this.gestiones[4].bloqueo=false;
                        }
                        
                    }
                }
                // respuestas
                this.arrayRespuestas=[];
                this.totales.respuesta='';
                this.respuestas.forEach(element => {
                    if(element.bloqueo==false){
                        this.arrayRespuestas.push(element.valor);
                    }
                });
                this.totales.respuesta=this.respuestas.length;
                // gestiones
                this.arrayGestiones=[];
                this.totales.gestiones='';
                this.gestiones.forEach(element => {
                    if(element.bloqueo==false){
                        this.arrayGestiones.push(element.valor);
                    }
                });
                this.totales.gestiones=this.gestiones.length;
            },
            llenarScore(cartera){
                this.score=[];
                this.arrayScore=[];
                this.totales.score='';
                axios.get("listaScore/"+cartera).then(res=>{
                    if(res.data){
                        var datos=res.data;
                        datos.forEach(d => {
                            this.score.push({valor:"'"+d.id+"'",nombre:d.valor});
                        });
                        this.score.forEach(element => {
                            this.arrayScore.push(element.valor);
                        });
                        this.totales.score=this.score.length;
                    }
                });
            },
            seleccionTab(i){
                this.opcionTab=i;
            },
            obtenerArchivo(e){
                let file=e.target.files;
                this.archivo=file[0];
            },
            formatoNumero(num,tipo){
                if(tipo=='M'){
                    var nStr=parseFloat(num).toFixed(2);
                }else{
                    var nStr=num;
                }
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2
            },
            soloNumeros(e){
                var key = window.event ? e.which : e.keyCode;
                if (key < 48 || key > 57) {
                    e.preventDefault();
                }
            },   
        },
        components: {
            
        }    
    }
</script>
