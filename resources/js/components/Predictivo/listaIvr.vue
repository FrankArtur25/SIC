<template>
    <div >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="form-control" v-model="busqueda.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label for="">Fecha Inicio de Evento</label>
                <input type="date" class="form-control" v-model="busqueda.fechaInicio">
            </div>
            <div class="col-md-3">
                <label for="">Fecha de Registro</label>
                <input type="date" class="form-control" v-model="busqueda.fecha">
            </div>
            <div class="col-md-2 pb-4">
                <label for="">Usuario</label>
                <input type="text" class="form-control" v-model="busqueda.usuario">
            </div>
        </div>
        <div class="d-flex mb-3" style="gap:8px">
            <a href="" @click.prevent="buscar()" class="btn btn-outline-blue px-3">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                Buscar
            </a>
            <a href="" @click.prevent="limpiar()" class="btn btn-outline-blue px-3">Limpiar</a>
        </div>
        <div class="table-responsive pb-5">
            <table class="table table-hover pb-5">
                <thead class="bg-blue-3 text-white">
                    <tr class="text-center">
                        <td class="align-middle">Campaña</td>
                        <td class="align-middle">Cartera</td>
                        <td class="align-middle">Usuario Predictivo</td>
                        <td class="align-middle">Total</td>
                        <td class="align-middle">Fecha del Evento (Inicio-Fin)</td>
                        <td class="align-middle">Gestiones Generadas</td>
                        <td class="align-middle">Detalle</td>
                        <td class="align-middle"></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="campanas==''">
                        <td colspan="8" class="text-center">No se encontraron registros</td>
                    </tr>
                    <tr v-for="(item,index) in campanas" :key="index" v-else>
                        <td>{{item.campana}}</td>
                        <td>{{item.cartera}}</td>
                        <td class="text-center">{{item.usuario}}</td>
                        <td class="text-center">{{formatoNumero(item.total,'C')}}</td>
                        <td class="text-center">{{item.fecha_evento}}</td>
                        <td class="text-center">{{formatoNumero(item.gestiones,'C')}}</td>
                        <td class="text-center border-0"><a href="" @click.prevent="detalleCampana(item.id)" class="btn btn-outline-green btn-sm" data-toggle="modal" data-target="#modalDetalle"><i class="fa fa-list"></i></a></td>
                        <td class="border-0">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-blue dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Opciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="z-index:9">
                                    <a class="dropdown-item" href=""  @click.prevent="modalAsignacionPredictivo(item.id,item.campana,item.usuario,item.id_usuario)" v-if="item.asignado==0">Asignación Predictivo</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalAsignacion(item.id,item.campana)" v-if="item.asignado==1">Regresar Asignación</a>
                                    <a class="dropdown-item" href=""  @click.prevent="descargarCsv(item.id)" id="btnDescarga">Descargar CSV</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalResultados(item.id,item.campana)">Importar Resultados</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalGestiones(item.id,item.campana,item.total)">Generar Gestiones</a>
                                    <a class="dropdown-item" href=""  @click.prevent="descargarReporte(item.id)">Descargar Reporte</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalFechas(item.id,item.campana,item.fecha_inicio,item.fecha_fin)">Editar Fecha de Evento</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalEliminar(item.id,item.campana)">Eliminar Campaña</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>        

        <!-- modal detalle -->
        <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-blue text-white pt-4 pb-0 px-5 ">
                            <p>Campaña Predictivo</p>      
                            <a href="" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-times text-white"></span>
                            </a>
                        </div>
                        <div class="modal-body py-3">
                            <div class="">
                                <table class="table table-hover" style="table-layout:fixed;width: 100%;overflow-wrap:break-word;">
                                    <tbody>
                                        <tr>
                                            <td class="px-3 bg-gray" style="width:40%">Campaña</td>
                                            <td class="px-3" style="width:60%">{{detalle.campana}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray" style="width:40%">Cartera</td>
                                            <td class="px-3" style="width:60%">{{detalle.cartera}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray" style="width:40%">Usuario Predictivo</td>
                                            <td class="px-3" style="width:60%">{{detalle.usuario}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Fecha de Evento</td>
                                            <td class="px-3">{{detalle.fecha_evento}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Total</td>
                                            <td class="px-3">{{formatoNumero(detalle.total,'C')}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Fecha de Registro</td>
                                            <td class="px-3">{{detalle.fecha_registro}}</td>
                                        </tr>
                                        <tr v-for="(item,index) in detalle.detalleCampana" :key="index">
                                            <td class="px-3 bg-gray">{{item.titulo}}</td>
                                            <td class="px-3">{{item.valor}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
       
        <!-- modal eliminar -->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalEliminar" >
            <div class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewFrmEliminar">
                    <p class="text-white font-20">¿Desea eliminar la campaña<br>"{{detalle.campana}}"?</p>
                    <a href="" v-if="spinnerEliminar==false" class="btn btn-white" @click.prevent="cancelar(5)">No</a>
                    <a href="" class="btn btn-danger" @click.prevent="eliminarCampana()">
                        <span v-if="spinnerEliminar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Sí, eliminar
                    </a>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-check-circle fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Campaña Eliminada con Éxito!</p>
                </div>
            </div>
        </div>

        <!-- modal reasignar -->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalAsignar" >
            <div class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewFrmAsignar">
                    <p class="text-white font-20">¿Desea regresar los clientes a la asignación de los usuarios,<br>antes de la generación de la Campaña<br>"{{detalle.campana}}"?</p>
                    <div class="text-left px-3 pb-4">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="todos" v-model="reasignar">
                            <label class="custom-control-label text-white font-18" for="customRadio1">Sí, deseo regresar a todos</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="pdps" v-model="reasignar">
                            <label class="custom-control-label text-white font-18" for="customRadio2">Sí, excepto las PDP</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input" value="pdpstt" v-model="reasignar">
                            <label class="custom-control-label text-white font-18" for="customRadio3">Sí, excepto las PDP y los Titulares Positivos</label>
                        </div>
                    </div>
                    <a href="" v-if="spinnerAsignar==false" class="btn btn-white" @click.prevent="cancelar(1)">No</a>
                    <a href="" class="btn btn-danger" @click.prevent="asignar()">
                        <span v-if="spinnerAsignar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Asignar
                    </a>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-check-circle fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Se realizó la reasignación con éxito!</p>
                </div>
            </div>
        </div>

        <!-- modal asignar predictivo-->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalAsignarPredictivo" >
            <div class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewFrmAsignarPredictivo">
                    <p class="text-white font-20">¿Desea asignar a los clientes al usuario {{this.detalle.usuario}}<br>de la Campaña "{{detalle.campana}}"?</p>
                    <a href="" v-if="spinnerAsignarPredictivo==false" class="btn btn-white" @click.prevent="cancelar(2)">No</a>
                    <a href="" class="btn btn-danger" @click.prevent="asignarPredictivo()">
                        <span v-if="spinnerAsignarPredictivo" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Sí, asignar
                    </a>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-check-circle fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Se realizó la asignación con éxito!</p>
                </div>
            </div>
        </div>

        <!-- modal generar gestiones -->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalGestion" >
            <div v-if="loadGestiones" class="d-flex justify-content-center align-items-center" style="margin-top:150px;">
                <div>
                    <span class="spinner-border spinner-border-xl text-white ml-3" role="status" aria-hidden="true"></span>
                    <p style="font-20px;" class="text-white">Cargando...</p>
                </div>
            </div>
            <div v-else class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewFrmGestion">
                    <div class="d-flex w-100 justify-content-center" style="gap:15px">
                        <div>
                            <p class="font-bold mb-0 font-25 text-white">{{formatoNumero(cantidadGestionada,'C')}}</p>
                            <p class="text-white">Cant. Gestiones<br>Registradas</p>
                        </div>
                        <div>
                            <p class="font-bold mb-0 font-25 text-danger">{{formatoNumero(cantidadConResultados,'C')}}</p>
                            <p class="text-white">Cant. Gestiones<br>a Registrar</p>
                        </div>
                    </div>
                    <div v-if="cantidadConResultados>0">
                        <p class="text-white font-20">¿Desea generar gestiones con respuesta "Teléfono No Contesta"<br>a los clientes sin gestión de la Campaña<br>"{{detalle.campana}}"?</p>
                        <a href="" v-if="spinnerGestion==false" class="btn btn-white" @click.prevent="cancelar(3)">No</a>
                        <a href="" class="btn btn-danger" @click.prevent="generarGestiones()">
                            <span v-if="spinnerGestion" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Sí, registrar gestiones
                        </a><br><br>
                        <small class="text-white">Tener en cuenta la fecha y hora (Inicio-Fin) de la campaña</small>
                    </div>
                    <div v-else class="pt-2">
                        <a href="" class="btn btn-white" @click.prevent="cancelar(3)">Cancelar</a>
                    </div>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-check-circle fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Se registraron {{cantidadRegistrada}} gestiones con éxito!</p>
                </div>
            </div>
        </div>

        <!-- modal actualizacion de fechas -->
        <div class="modal fade modal-carga" id="modalFechas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{this.detalle.campana}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-3 pt-4 pb-5">
                        <div class="form-group">
                            <label for="">Fecha de Evento - Inicio</label>
                            <input type="datetime-local" class="form-control" v-model="actualizar.fechaInicio">
                        </div>
                        <div class="form-group">
                            <label for="">Fecha de Evento - Fin</label>
                            <input type="datetime-local" class="form-control" v-model="actualizar.fechaFin">
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarFechas()">
                            <span v-if="spinnerFechas" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Actualizar
                        </a>
                        <small class="text-success" v-if="mensaje">{{mensaje}}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- importar resultados -->
        <div class="modal fade modal-carga" id="modalResultados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{this.detalle.campana}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-3 pt-4 pb-5">
                        <div class="form-group pb-3 text-center">
                            <input type="text" disabled class="form-control" v-model="actualizarRes.nombreArchivo">
                            <input type="file" ref="filecsv"  @change="obtenerArchivo" style="display:none;"  accept=".xlsx">
                            <a href="" @click.prevent="$refs.filecsv.click()">Subir Archivo</a>
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="cargarResultados()">
                            <span v-if="spinnerResultados" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Actualizar Resultados
                        </a>
                        <small class="text-success" v-if="mensaje">{{mensaje}}</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Excel from '../../../../public/js/excel.js';
    export default {
        props:['carteras'],
        data() {
            return {
                campanas:[],
                busqueda:{cartera:'',fechaInicio:'',usuario:'',fecha:''},
                spinnerBuscar:false,
                detalle:{campana:'',cartera:'',fecha_evento:'',fecha_registro:'',total:'',usuario:'',detalleCampana:[],idusuario:''},
                spinnerEliminar:false,
                viewFrmEliminar:true,
                spinnerAsignar:false,
                viewFrmAsignar:true,
                spinnerAsignarPredictivo:false,
                viewFrmAsignarPredictivo:true,
                spinnerGestion:false,
                viewFrmGestion:true,
                loadGestiones:true,
                spinnerFechas:false,
                spinnerResultados:false,
                idCampana:'',
                cantidadRegistrada:0,
                cantidadGestionada:0,
                cantidadConResultados:0,
                reasignar:'todos',
                actualizar:{fechaInicio:'',fechaFin:'',idCampana:''},
                actualizarRes:{idCampana:'',archivo:'',nombreArchivo:''},
                mensaje:''
            }
        },
        methods:{
           buscar(){
               this.spinnerBuscar=true;
                this.campanas=[];
                axios.post("listaCampanas",this.busqueda).then(res=>{
                    if(res.data){
                        this.campanas=res.data;
                        this.spinnerBuscar=false;
                    }
                })
            },
            limpiar(){
                this.busqueda.cartera='';
                this.busqueda.fechaInicio='';
                this.busqueda.usuario='';
                this.busqueda.fecha='';
            },
            limpiarDetalle(){
                this.detalle.campana='';
                this.detalle.usuario='';
                this.detalle.total='';
                this.detalle.fecha_evento='';
                this.detalle.fecha_registro='';
                this.detalle.cartera='';
                this.detalle.detalleCampana=[];
            },
            detalleCampana(id){
                let detalles=[];
                this.limpiarDetalle();
                this.campanas.forEach(element => {
                    if(element.id==id){
                        if(element.detalle!=null){
                            console.log(element.detalle);
                            detalles=element.detalle.split(";");
                        }
                        this.detalle.campana=element.campana;
                        this.detalle.usuario=element.usuario;
                        this.detalle.total=element.total;
                        this.detalle.fecha_evento=element.fecha_evento;
                        this.detalle.fecha_registro=element.fecha_registro;
                        this.detalle.cartera=element.cartera;
                    }
                });

                const auxSubCat = {};
                if(detalles.length>0){
                    detalles.forEach(detalle => {
                        const auxCategorias = detalle.split(":");
                        this.detalle.detalleCampana.push({titulo:auxCategorias[0],valor:auxCategorias[1]});
                    });
                }
            },
            descargarCsv(id){
                window.location.href="./descargarPredictivo/"+id;
                // window.load=function() {
                //     alert('OK');
                // }
           },
           modalEliminar(id,nom){
               this.viewFrmEliminar=true;
               this.idCampana=id;
               this.detalle.campana=nom;
               $('#modalEliminar').modal({backdrop: 'static', keyboard: false});
           },
            eliminarCampana(){
                this.spinnerEliminar=true;
                axios.get("eliminarCampana/"+this.idCampana).then(res=>{
                    if(res.data=="ok"){
                        this.buscar();
                        this.spinnerEliminar=false;
                        this.viewFrmEliminar=false;
                        setTimeout(() => {
                            $('#modalEliminar').modal('hide');
                        },1300);
                    }
                })
            },
            modalAsignacion(id,nom){
               this.viewFrmAsignar=true;
               this.idCampana=id;
               this.detalle.campana=nom;
               this.reasignar='todos';
               $('#modalAsignar').modal({backdrop: 'static', keyboard: false});
            },
            asignar(){
                this.spinnerAsignar=true;
                var parametros={idCampana:this.idCampana,opcion:this.reasignar};
                axios.post("devolverAsignacion",parametros).then(res=>{
                    if(res.data=="ok"){
                        this.buscar();
                        this.spinnerAsignar=false;
                        this.viewFrmAsignar=false;
                        setTimeout(() => {
                            $('#modalAsignar').modal('hide');
                        },1300);
                    }
                })
            },
            modalAsignacionPredictivo(id,nom,usuario,idusuario){
               this.viewFrmAsignarPredictivo=true;
               this.idCampana=id;
               this.detalle.usuario=usuario;
               this.detalle.idusuario=idusuario;
               this.detalle.campana=nom;
               $('#modalAsignarPredictivo').modal({backdrop: 'static', keyboard: false});
            },
            asignarPredictivo(){
                this.spinnerAsignarPredictivo=true;
                axios.get("asignar/"+this.idCampana+"/"+this.detalle.idusuario).then(res=>{
                    if(res.data=="ok"){
                        this.buscar();
                        this.spinnerAsignarPredictivo=false;
                        this.viewFrmAsignarPredictivo=false;
                        setTimeout(() => {
                            $('#modalAsignarPredictivo').modal('hide');
                        },1300);
                    }
                })
            },
            modalGestiones(id,nom,total){
               this.loadGestiones=true;
               this.viewFrmGestion=true;
               this.idCampana=id;
               this.detalle.campana=nom;
               $('#modalGestion').modal({backdrop: 'static', keyboard: false});
               axios.get("datosGestiones/"+this.idCampana).then(res=>{
                   if(res.data){
                       var datos=res.data;
                       if(datos['cantGestiones'].length>0){
                           this.cantidadGestionada=datos['cantGestiones'][0].cantidadGestionada;
                       }
                       if(datos['cantResultados'].length>0){
                           this.cantidadConResultados=datos['cantResultados'][0].cantidad;
                       }
                       this.loadGestiones=false;
                   }
               });
            },
            generarGestiones(){
                this.loadGestiones=false;
                this.spinnerGestion=true;
                axios.get("generarGestiones/"+this.idCampana+"/"+this.cantidadRegistrada).then(res=>{
                    if(res.data=="ok"){
                        this.buscar();
                        this.spinnerGestion=false;
                        this.viewFrmGestion=false;
                        setTimeout(() => {
                            $('#modalGestion').modal('hide');
                        },1300);
                    }
                })
            },
            modalFechas(id,nom,inicio,fin){
                this.spinnerFechas=false;
                this.mensaje='';
                this.actualizar.idCampana=id;
                this.detalle.campana=nom;
                this.actualizar.fechaInicio=(inicio).replace(" ","T");
                this.actualizar.fechaFin=(fin).replace(" ","T");
                $('#modalFechas').modal({backdrop: 'static', keyboard: false});
            },
            actualizarFechas(){
                this.mensaje='';
                this.spinnerFechas=true;
                axios.post("actualizarFechaCampana",this.actualizar).then(res=>{
                    if(res.data=="ok"){
                        this.buscar();
                        this.spinnerFechas=false;
                        this.mensaje='Actualización Exitosa!';
                    }
                })
            },
            modalResultados(id,nom){
                this.spinnerResultados=false;
                this.mensaje='';
                this.actualizarRes.idCampana=id;
                this.detalle.campana=nom;
                this.actualizarRes.nombreArchivo='';
                $('#modalResultados').modal({backdrop: 'static', keyboard: false});
            },
            cargarResultados(){
                this.mensaje='';
                this.spinnerResultados=true;
                if(this.actualizarRes.nombreArchivo!=""){
                    let formData= new FormData();
                    formData.append("idCampana",this.actualizarRes.idCampana);
                    formData.append("archivo",this.actualizarRes.archivo);
                    axios.post("cargarResultadosPredictivo",formData).then(res=>{
                        if(res.data=="ok"){
                            this.spinnerResultados=false;
                            this.mensaje='Se cargaron los datos con exito!';
                        }
                    })
                }else{
                    this.mensaje="Cargar archivo";
                }
            },
            obtenerArchivo(e){
                let file=e.target.files;
                this.actualizarRes.archivo=file[0];
                this.actualizarRes.nombreArchivo=file[0].name;
            },
            cancelar(i){
                // asignar
                if(i==1){
                    this.viewFrmAsignar=true;
                    this.spinnerAsignar=false;
                    $('#modalAsignar').modal('hide');
                }
                // eliminar
                if(i==3){
                    this.viewFrmGestion=true;
                    this.spinnerGestion=false;
                    $('#modalGestion').modal('hide');
                }
                // eliminar
                if(i==5){
                    this.viewFrmEliminar=true;
                    this.spinnerEliminar=false;
                    $('#modalEliminar').modal('hide');
                }
                // asignar predictivo
                if(i==2){
                    this.viewFrmAsignar=true;
                    this.spinnerAsignar=false;
                    $('#modalAsignarPredictivo').modal('hide');
                }
                
            },
            descargarReporte(id){
                let data=[];
                let data2=[];
                var totalLlamadas=0;
                var totalClientes=0;
                var totalContactos=0;
                var totalPdps=0;
                var totalMontoPdps=0;
                // window.location.href="./descargarReportePredictivo/"+id;

                axios.get("descargarReportePredictivo/"+id).then(res=>{
                    if(res.data){
                        var datos=res.data;
                        var datosGestor=datos['rep_gestor'];
                        var datosRespuestas=datos['rep_respuestas'];
                        // reporte por gestor
                        data.push(["Gestor","Total Llamadas","Total Clientes","Total de Contactos","Contactabilidad","Total PDP","Monto PDP"]);
                        datosGestor.forEach(d => {
                            data.push([d.gestor,
                                       this.formatoNumero(parseInt(d.total_llamadas),'C'),
                                       this.formatoNumero(parseInt(d.total_clientes),'C'),
                                       this.formatoNumero(parseInt(d.total_contactos),'C'),
                                       this.formatoNumero(d.contactabilidad,'C')+"%",
                                       this.formatoNumero(parseInt(d.cant_pdp),'C'),
                                       this.formatoNumero(parseFloat(d.monto_pdp),'M'),
                                      ]);
                            totalLlamadas+=parseInt(d.total_llamadas);
                            totalClientes+=parseInt(d.total_clientes);
                            totalContactos+=parseInt(d.total_contactos);
                            totalPdps+=parseInt(d.cant_pdp);
                            totalMontoPdps+=parseFloat(d.monto_pdp);
                        });
                        data.push(["Total",this.formatoNumero(totalLlamadas,'C'),this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalContactos,'C'),Math.round((totalContactos/totalClientes)*100)+"%",this.formatoNumero(totalPdps,'C'),this.formatoNumero(totalMontoPdps,'M')]);

                        // reporte por respuestas
                        data2.push(["Ubicabilidad - Respuesta","Clientes","%"]);
                        
                        let totalContacto=0;
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='CONTACTO'){
                                totalContacto+=parseInt(d.clientes);
                            }
                        });
                        data2.push(["CONTACTO",this.formatoNumero(totalContacto),""]);
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='CONTACTO'){
                                data2.push([d.respuesta,this.formatoNumero(d.clientes,'C')])
                            }
                        });

                        let totalNocontacto=0;
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='NO CONTACTO'){
                                totalNocontacto+=parseInt(d.clientes);
                            }
                        });
                        data2.push(["NO CONTACTO",this.formatoNumero(totalNocontacto,'C'),""]);
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='NO CONTACTO'){
                                data2.push([d.respuesta,this.formatoNumero(d.clientes,'C')])
                            }
                        });

                        let totalNodisponible=0;
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='NO DISPONIBLE'){
                                totalNodisponible+=parseInt(d.clientes);
                            }
                        });
                        data2.push(["NO DISPONIBLE",this.formatoNumero(totalNodisponible,'C'),""]);
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='NO DISPONIBLE'){
                                data2.push([d.respuesta,this.formatoNumero(d.clientes,'C')])
                            }
                        });


                        let totalIntentos=0;
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='INTENTOS MARCADOR'){
                                totalIntentos+=parseInt(d.clientes);
                            }
                        });
                        data2.push(["INTENTOS MARCADOR",this.formatoNumero(totalIntentos,'C'),""]);
                        datosRespuestas.forEach(d => {
                            if(d.ubicabilidad=='INTENTOS MARCADOR'){
                                data2.push([d.respuesta,this.formatoNumero(d.clientes,'C')])
                            }
                        });
                        let total=0
                        datosRespuestas.forEach(d => {
                            total+=parseInt(d.clientes);
                        });
                        data2.push(["TOTAL",this.formatoNumero(total,'C'),""]);
                        
                        Excel.exportar(data,"Repote_predictivo","POR GESTOR","",2,data2,"POR RESPUESTAS");
                    }
                })

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
        },
        
        components: {
            // Excel            
        }    
    }
</script>
