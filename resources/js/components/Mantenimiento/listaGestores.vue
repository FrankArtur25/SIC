<template>
    <div >
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="font-bold">Firma</label>
                    <input type="text" class="form-control" v-model="buscar.firma" @keypress.enter="buscarEmpleados()">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="font-bold">Usuario SIC</label>
                    <input type="text" class="form-control" v-model="buscar.codigo" @keypress.enter="buscarEmpleados()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Nombre</label>
                    <input type="text" class="form-control" v-model="buscar.nombre" @keypress.enter="buscarEmpleados()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Modalidad</label>
                    <select class="form-control" v-model="buscar.modalidad">
                        <option value="">Seleccionar</option>
                        <option value="TC">Tiempo Completo</option>
                        <option value="MM">Medio Tiempo Mañana</option>
                        <option value="MT">Medio Tiempo Tarde</option>
                        <option value="PRC">Practicante</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-bold">Cartera</label>
                    <select class="form-control"  v-model="buscar.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="font-bold">Estado</label>
                    <select class="form-control"  v-model="buscar.estado">
                        <option value="">Seleccionar</option>
                        <option value="0">Activo</option>
                        <option value="1">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <a href="" class="btn btn-outline-blue" @click.prevent="buscarEmpleados()">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Buscar
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        </div>
        <div class="table-responsive my-2">
            <paginate ref="paginator" name="lista" :list="lista" :per="20" class="px-0">
                <table class="table table-hover pb-3">
                    <thead class="bg-blue-3 text-white text-center">
                        <tr>
                            <td>Nombre ({{total}})</td>
                            <td>Firma</td>
                            <td>Modalidad</td>
                            <td>Cartera</td>
                            <td>Usuario SIC</td>
                            <td>Estado</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="lista==''">
                            <td colspan="7" class="text-center">No hay resultados</td>
                        </tr>
                        <tr v-else v-for="(item,index) in paginated('lista')" :key="index">
                            <td class="text-left px-2">{{item.nombre}}</td>
                            <td class="text-center">{{item.firma}}</td>
                            <td class="text-center">{{item.modalidad}}</td>
                            <td class="text-left px-2">{{item.cartera}}</td>
                            <td class="text-left px-2">{{item.usuario}}</td>
                            <td class="text-center"><span class="badge p-2" :class="{'badge-success':item.idestado==0,'badge-danger':item.idestado!=0}">{{item.estado}}</span></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-blue dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Opciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="z-index:9">
                                    <a class="dropdown-item" href=""  @click.prevent="modalFirma(item.id,item.firma,item.nombre)">Asignar Firma</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalAsignar(item.id,item.nombre,item.codigousuario)">Asignar Usuario</a>
                                    <a class="dropdown-item" href=""  @click.prevent="modalEditar(item.id,item.nombre,item.modalidad,item.idcartera,item.firma)">Editar Datos</a>
                                    <!-- <a class="dropdown-item" href=""  @click.prevent="modalLaboral(item.firma,item.nombre)" v-if="item.firma!=''">Historial Laboral</a> -->
                                </div>
                            </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </paginate>
        </div>
        <paginate-links  for="lista" v-if="lista.length>0" 
            :async="true"                     
            :limit="4"
            :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"
        ></paginate-links>

        <!-- modal actualizar datos -->
        <div class="modal fade modal-carga" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white px-3">
                        <p class="modal-title text-white">Actualización de Datos</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-4 pt-4 pb-5">
                        <p class="font-bold text-blue mb-0">Datos Personales</p>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="">Nombre y Apellidos</label>
                                    <input type="text" class="form-control" v-model="registro.nombre">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="font-bold text-blue mb-0">Datos Laborales</p>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Modalidad</label>
                                        <select class="form-control selectpicker" v-model="registro.modalidad" title="Seleccionar">
                                            <option value="TC">Tiempo Completo</option>
                                            <option value="MM">Medio Tiempo Mañana</option>
                                            <option value="MT">Medio Tiempo Tarde</option>
                                            <option value="PRC">Practicante</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="">Cartera a trabajar</label>
                                        <select class="form-control selectpicker"  v-model="registro.cartera"  title="Seleccionar">
                                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Firma</label>
                                        <input type="text" class="form-control" v-model="registro.firma">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="errors!=''">
                            <div class="col-md-12">
                                <div class="alert alert-warning border-left" >
                                    <p class="mb-0">Corriga el(los) siguiente(s) error(es):</p>
                                    <ul class="text-left"><li v-for="(error,index) in errors" :key="index" >{{ error }}</li></ul>
                                </div>
                            </div>
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarEmpleado()">
                            <span v-if="spinnerActualizar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Actualizar Datos
                        </a>
                        <div class="text-center">
                            <small v-if="mensaje!=''" class="text-center py-2 text-success" >{{mensaje}}</small>
                        </div>
                    </div>
                            
                    </div>
                </div>
        </div>
    
        <!-- modal actualizar firma -->
        <div class="modal fade modal-carga" id="formFirma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{actualizar.nombre}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-3 pt-4 pb-5">
                        <div class="form-group">
                            <label for="">Firma</label>
                            <input type="text" class="form-control" v-model="actualizar.firma" v-on:keyup.enter="actualizarFirma()">
                            <small class="text-danger" v-if="mensajeFirma">{{mensajeFirma}}</small>
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarFirma()">
                            <span v-if="spinnerActualizar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Actualizar
                        </a>
                        <div class="text-center">
                            <small class="text-success" v-if="mensaje">{{mensaje}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal registro laboral -->
        <div class="modal fade modal-carga" id="modalLaboral" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{registroLaboral.nombre}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-4 pt-4 pb-5">
                        <div class="row">
                            <div class="col-md-4 pb-3">
                                <p class="text-blue font-bold mb-0">Formulario</p><hr class="mt-0">
                                <div class="form-group">
                                    <label class="font-bold">Cartera</label>
                                    <select class="selectpicker form-control" v-model="registroLaboral.cartera" title="Seleccionar">
                                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                                    </select>
                                    <small class="text-danger" v-if="mensajes.cartera">{{mensajes.cartera}}</small>
                                </div>
                                <div class="form-group">
                                    <label class="font-bold">Fecha Ingreso</label>
                                    <input type="date" class="form-control" v-model="registroLaboral.fechaIngreso">
                                    <small class="text-danger" v-if="mensajes.fechaIngreso">{{mensajes.fechaIngreso}}</small>
                                </div>
                                <div class="form-group">
                                    <label class="font-bold">Fecha Salida</label>
                                    <input type="date" class="form-control" v-model="registroLaboral.fechaSalida">
                                </div>
                                <div class="form-group">
                                    <label class="font-bold">Reemplazo de</label>
                                    <select class="selectpicker form-control show-tick" data-size="6" data-live-search="true" title="Seleccionar" v-model="registroLaboral.reemplazo">
                                        <option value="">Seleccionar</option>
                                        <option  v-for="(item,index) in gestores" :key="index" :value="item.firma">{{item.nombre}}</option>
                                    </select>
                                </div>
                                <a href="" class="btn btn-outline-blue btn-block" @click.prevent="registrarHistorialLaboral()">
                                    <span v-if="spinnerRegistrar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    {{tituloBtn}}
                                </a>
                                <small class="text-success" v-if="mensaje">{{mensaje}}</small>
                            </div>
                            <div class="col-md-8">
                                <p class="text-blue font-bold mb-0">Historial Laboral</p><hr class="mt-0">
                                <div class="table-responsive w-100" style="height:420px">
                                    <table class="table table-hover">
                                        <thead class="bg-blue-3 text-white text-center">
                                            <tr>
                                                <td>Cartera</td>
                                                <td>Fecha de Ingreso</td>
                                                <td>Fecha de Salida</td>
                                                <td>Reemplazo de</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="dataLaboral==''" class="text-center">
                                                <td colspan="5">No existen datos</td>
                                            </tr>
                                            <tr v-for="(item,index) in dataLaboral" :key="index" v-else>
                                                <td class="px-2">{{item.cartera}}</td>
                                                <td class="text-center">{{item.fechaIngreso}}</td>
                                                <td class="text-center">{{item.fechaSalida}}</td>
                                                <td class="px-2">{{item.reemplazo}}</td>
                                                <td class="text-center">
                                                    <a href="" class="btn btn-sm btn-outline-blue" @click.prevent="editarHistorial(item.id,item.idcartera,item.firmaReemplazo,item.fechaIngreso,item.fechaSalida)"><i class="fa fa-edit fa-sm"></i></a>
                                                    <!-- <a href="" class="btn btn-sm btn-outline-blue" @click.prevent="eliminarHistorial(item.id)"><i class="fa fa-trash fa-sm"></i></a> -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal de asignacion -->
        <div class="modal fade modal-carga" id="modalAsignacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{actualizarUsuario.nombre}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-3 pt-4 pb-5">
                        <div class="form-group">
                            <label for="">Usuario SIC</label>
                            <select class="form-control selectpicker" v-model="actualizarUsuario.usuario" data-size="6" data-live-search="true" title="Seleccionar">
                                <option v-for="(item,index) in usuarios" :key="index" :value="item.codigo">{{item.nombre}}</option>
                            </select>
                            <small class="text-danger" v-if="mensajeFirma">{{mensajeFirma}}</small>
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarDatoUsuario()">
                            <span v-if="spinnerActualizar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                            Actualizar
                        </a>
                        <div class="text-center">
                            <small class="text-success" v-if="mensaje">{{mensaje}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vuePaginate from '../../../../node_modules/vue-paginate';
    export default {
        props:['carteras','gestores','usuarios'],
        data() {
            return {
                buscar:{nombre:'',codigo:'',firma:'',modalidad:'',cartera:'',estado:'0'},
                spinnerBuscar:'',
                paginate: ['lista'],
                lista:[],
                registro:{nombre:'',firma:'',modalidad:'',cartera:''},
                total:0,
                spinnerActualizar:false,
                actualizar:{nombre:'',firma:'',id:''},
                actualizarUsuario:{nombre:'',usuario:'',id:''},
                mensajeFirma:'',
                mensaje:'',
                errors:[],
                dataLaboral:[],
                registroLaboral:{dni:'',nombre:'',cartera:'',fechaIngreso:'',fechaSalida:'',reemplazo:'',firma:'',tipoBtn:1,id:''},
                spinnerRegistrar:false,
                tituloBtn:'Registrar',
                mensajes:{cartera:'',fechaIngreso:''}
            }
        },
        methods:{
            buscarEmpleados(){
                this.spinnerBuscar=true;
                axios.post("listGestores",this.buscar).then(res=>{
                    if(res.data){
                        this.lista=res.data;
                        this.total=this.lista.length;
                        this.spinnerBuscar=false;
                    }
                });
            },
            modalFirma(id,firma,nombre){
                this.spinnerActualizar=false;
                this.mensaje='';
                this.mensajeFirma='';
                this.actualizar.firma=firma;
                this.actualizar.id=id;
                this.actualizar.nombre=nombre;
                $('#formFirma').modal({backdrop: 'static', keyboard: false});
            },
            actualizarFirma(){
                if(this.actualizar.firma!=''){
                    this.spinnerActualizar=true;
                    axios.post("updateFirma",this.actualizar).then(res=>{
                        if(res.data="ok"){
                            this.spinnerActualizar=false;
                            this.mensaje="Actualización Exitosa!";
                            this.buscarEmpleados();
                        }
                    });
                }else{
                    this.mensajeFirma='Completar campo';
                }
            },
            modalLaboral(firma,nombre){
                this.tituloBtn='Registrar';
                this.tipoBtn=1;
                this.spinnerRegistrar=false;
                this.mensaje='';
                this.registroLaboral.firma=firma;
                this.registroLaboral.nombre=nombre;
                this.limpiarDatosHistorial();
                this.dataLaboral=[];
                this.mensajes.fechaIngreso='';
                this.mensajes.cartera='';
                $('#modalLaboral').modal({backdrop: 'static', keyboard: false});
                axios.get("historialLaboral/"+firma).then(res=>{
                    if(res.data){
                        this.dataLaboral=res.data;
                    }
                });
            },
            registrarHistorialLaboral(){
                this.mensajes.fechaIngreso='';
                this.mensajes.cartera='';
                if(this.registroLaboral.fechaIngreso!='' && this.registroLaboral.cartera!=''){
                    this.spinnerRegistrar=true;
                    axios.post("registroHistorialLaboral",this.registroLaboral).then(res=>{
                        if(res.data="ok"){
                            this.spinnerRegistrar=false;
                            if(this.registroLaboral.tipoBtn==1){
                                this.mensaje="Registro Exitoso!";
                                setTimeout(() => {
                                    this.mensaje='';
                                }, 1200);
                            }
                            if(this.registroLaboral.tipoBtn==2){
                                this.mensaje="Actualización Exitosa!";
                                setTimeout(() => {
                                    this.mensaje='';
                                }, 1200);
                            }
                            this.dataLaboral=[];
                            axios.get("historialLaboral/"+this.registroLaboral.firma).then(res=>{
                                if(res.data){
                                    this.dataLaboral=res.data;
                                }
                            });
                            this.limpiarDatosHistorial();
                            this.tituloBtn='Registrar';
                            this.registroLaboral.tipoBtn=1;
                        }
                    });
                }else{
                    if(!this.registroLaboral.fechaIngreso){
                        this.mensajes.fechaIngreso='Completar campo';
                    }
                    if(!this.registroLaboral.cartera){
                        this.mensajes.cartera='Completar campo';
                    }
                }
            },
            editarHistorial(id,idcartera,firmaReemplazo,fechaIngreso,fechaSalida){
                this.registroLaboral.cartera=idcartera;
                this.registroLaboral.reemplazo=firmaReemplazo;
                this.registroLaboral.fechaIngreso=fechaIngreso;
                this.registroLaboral.fechaSalida=fechaSalida;
                this.registroLaboral.id=id;
                this.tituloBtn='Actualizar';
                this.registroLaboral.tipoBtn=2;
                this.mensajes.cartera='';
                this.mensajes.fechaIngreso='';
            },
            limpiarDatosHistorial(){
                this.registroLaboral.fechaIngreso='';
                this.registroLaboral.fechaSalida='';
                this.registroLaboral.cartera='';
                this.registroLaboral.reemplazo='';
            },
            modalEditar(id,nombre,modalidad,cartera,firma){
                this.spinnerActualizar=false;
                this.mensaje='';
                this.registro.id=id;
                this.registro.nombre=nombre;
                this.registro.modalidad=modalidad;
                this.registro.cartera=cartera;
                this.registro.firma=firma;
                this.errors=[];
                $('#modalEditar').modal({backdrop: 'static', keyboard: false});
            },
            validarDatos(){
                this.errors=[];
                if(!this.registro.nombre){
                    this.errors.push("Ingresar un nombre");
                }
                if(!this.registro.modalidad){
                    this.errors.push("Seleccionar una modalidad");
                }
                if(!this.registro.cartera){
                    this.errors.push("Seleccionar una cartera");
                }
            },
            actualizarEmpleado(){
                this.errors=[];
                this.validarDatos();
                if(this.errors.length==0){
                    this.spinnerActualizar=true;
                    axios.post("updateGestor",this.registro).then(res=>{
                        if(res.data="ok"){
                            this.spinnerActualizar=false;
                            this.mensaje="Actualización Exitosa!";
                            this.buscarEmpleados();
                        }
                    });
                }
            },
            limpiar(){
                this.buscar.codigo='';
                this.buscar.modalidad='';
                this.buscar.firma='';
                this.buscar.nombre='';
                this.buscar.cartera='';
                this.buscar.estado='0';
            },
            modalAsignar(id,nombre,usuario){
                this.spinnerActualizar=false;
                this.mensaje='';
                this.mensajeFirma='';
                this.actualizarUsuario.id=id;
                this.actualizarUsuario.nombre=nombre;
                this.actualizarUsuario.usuario=usuario;
                $('#modalAsignacion').modal({backdrop: 'static', keyboard: false});
            },
            actualizarDatoUsuario(){
                if(this.actualizarUsuario.usuario!=''){
                    this.spinnerActualizar=true;
                    axios.post("updateUsuario",this.actualizarUsuario).then(res=>{
                        if(res.data="ok"){
                            this.spinnerActualizar=false;
                            this.mensaje="Actualización Exitosa!";
                            this.buscarEmpleados();
                        }
                    });
                }else{
                    this.mensajeFirma='Completar campo';
                }
            },
            // eliminarHistorial(id){
            //     this.acta
            //     axios.get("deleteUsuario/"+id).then(res=>{
            //         if(res.data="ok"){
            //             this.buscarEmpleados();
            //         }
            //     });
            // }
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
        components: {
            vuePaginate,
        }    
    }
</script>
