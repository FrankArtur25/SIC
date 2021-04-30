<template>
    <div >
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="font-bold">Código</label>
                    <input class="form-control" v-model="buscar.codigo" @keypress.enter="buscarEmpleados()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Nombre</label>
                    <input class="form-control" v-model="buscar.nombre" @keypress.enter="buscarEmpleados()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Perfil</label>
                    <select class="form-control" v-model="buscar.perfil">
                        <option value="">Seleccionar</option>
                        <option value="5">ADMINISTRADOR</option>
                        <option value="1">SUPERVISOR</option>
                        <option value="2">GESTOR TELEFÓNICO</option>
                        <option value="7">RRHH</option>
                        <option value="6">SISTEMAS</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 form-group">
                <label for="" class="text-white">.</label><br>
                <a href="" class="btn btn-outline-blue mb-3" @click.prevent="buscarEmpleados()">
                    <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Buscar
                </a>
            </div>
        </div>
        <div class="table-responsive my-2">
            <paginate ref="paginator" name="lista" :list="lista" :per="20" class="px-0">
                <table class="table table-hover">
                    <thead class="bg-blue-3 text-white text-center">
                        <tr>
                            <td>Código</td>
                            <td>Nombre ({{total}})</td>
                            <td>Meta</td>
                            <td>Perfil</td>
                            <td>Call</td>
                            <td>Local</td>
                            <td>Estado</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="lista==''">
                            <td colspan="8" class="text-center">No hay resultados</td>
                        </tr>
                        <tr v-else v-for="(item,index) in paginated('lista')" :key="index">
                            <td class="text-center">{{item.codigo}}</td>
                            <td class="text-left px-2">{{item.nombre}}</td>
                            <td class="text-center">{{item.meta}}</td>
                            <td class="text-center">{{item.perfil}}</td>
                            <td class="text-center">{{item.calls}}</td>
                            <td class="text-center">{{item.locals}}</td>
                            <td class="text-center"><span class="badge p-2" :class="{'badge-success':item.idestado==0,'badge-danger':item.idestado!=0}">{{item.estado}}</span></td>
                            <td class="text-center">
                                <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="modalEditar(item.id,item.nombre,item.meta_n,item.idcalls,item.idlocals,item.idperfil,item.carteras)"><i class="fa fa-edit fa-sm"></i></a>
                                <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="modalClave(item.id,item.codigo,item.nombre)"><i class="fa fa-key fa-sm"></i></a>
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
                        <p class="modal-title text-white">Datos Generales</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-4 pt-4 pb-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="registro.nombre">
                                </div>
                                <div class="form-group">
                                    <label for="">Meta</label>
                                    <input type="text" class="form-control" v-model="registro.meta">
                                </div>
                                <div class="form-group">
                                    <label for="">Local</label>
                                    <select class="form-control selectpicker" v-model="registro.local" title="Seleccionar">
                                        <option v-for="(item,index) in locales" :key="index" :value="item.idoficina">{{item.local}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Call</label>
                                    <select class="form-control selectpicker" v-model="registro.call" title="Seleccionar">
                                        <option v-for="(item,index) in calls" :key="index" :value="item.id">{{item.calll}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Tipo de Acceso</label>
                                    <select class="form-control selectpicker" v-model="registro.tipo_acceso" @change="validarCarteras(registro.tipo_acceso)" title="Seleccionar">
                                        <option value="5">ADMINISTRADOR</option>
                                        <option value="1">SUPERVISOR</option>
                                        <option value="2">GESTOR TELEFÓNICO</option>
                                        <option value="7">RRHH</option>
                                        <option value="6">SISTEMAS</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="registro.tipo_acceso!=2 && registro.tipo_acceso!=6">
                                    <label for="">Carteras</label>
                                    <select class="form-control selectpicker" multiple v-model="registro.carteras" data-selected-text-format="count" title="Seleccionar">
                                        <option value="0">TODAS LAS CARTERAS</option>
                                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                                    </select>
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
    
        <!-- modal actualizar password -->
        <div class="modal fade modal-carga" id="modalPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <label for="">Código</label>
                            <input type="text" class="form-control" v-model="actualizar.codigo" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">Clave</label>
                            <input type="password" class="form-control" v-model="actualizar.clave" v-on:keyup.enter="actualizarClave()">
                        </div>
                        <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarClave()">
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
        props:['carteras','locales','calls'],
        data() {
            return {
                buscar:{nombre:'',codigo:'',perfil:''},
                spinnerBuscar:'',
                paginate: ['lista'],
                lista:[],
                total:0,
                registro:{nombre:'',meta:'',local:'',call:'',tipo_acceso:'',carteras:[],id:''},
                spinnerActualizar:false,
                actualizar:{nombre:'',codigo:'',clave:'',id:''},
                mensajeClave:'',
                mensaje:''
            }
        },
        methods:{
            buscarEmpleados(){
                if(this.codigo!='' || this.nombre!='' || this.perfil!=''){
                    this.spinnerBuscar=true;
                    axios.post("listEmpleados",this.buscar).then(res=>{
                        if(res.data){
                            this.lista=res.data;
                            this.total=this.lista.length;
                            this.spinnerBuscar=false;
                        }
                    });
                }
            },
            modalEditar(id,nombre,meta,call,local,tipo,carteras){
                this.spinnerActualizar=false;
                this.mensaje='';
                this.registro.id=id;
                this.registro.nombre=nombre;
                this.registro.meta=meta;
                this.registro.call=call;
                this.registro.local=local;
                this.registro.tipo_acceso=tipo;
                this.registro.carteras=[];
                if(carteras!==null){
                    if(carteras.indexOf(',')!== -1){
                        this.registro.carteras=carteras.split(",");;
                    }else{
                        this.registro.carteras.push(carteras);
                    }
                }
                $('#modalEditar').modal({backdrop: 'static', keyboard: false});
            },
            modalClave(id,codigo,nombre){
                this.spinnerActualizar=false;
                this.mensaje='';
                this.mensajeClave='';
                this.actualizar.clave='';
                this.actualizar.id=id;
                this.actualizar.codigo=codigo;
                this.actualizar.nombre=nombre;
                $('#modalPass').modal({backdrop: 'static', keyboard: false});
            },
            actualizarClave(){
                if(this.actualizar.clave!=''){
                    this.spinnerActualizar=true;
                    axios.post("updateClave",this.actualizar).then(res=>{
                        if(res.data="ok"){
                            this.spinnerActualizar=false;
                            this.mensaje="Actualización Exitosa!";
                            this.buscarEmpleados();
                        }
                    });
                }else{
                    this.mensajeClave='Completar campo';
                }
            },
            validarDatos(){
                this.errors=[];
                if(!this.registro.nombre){
                    this.errors.push("Ingresar un nombre");
                }
                if(!this.registro.local){
                    this.errors.push("Selecciona un local");
                }
                if(!this.registro.call){
                    this.errors.push("Selecciona un call");
                }
            },
            actualizarEmpleado(){
                this.errors=[];
                this.validarDatos();
                if(this.errors.length==0){
                    this.spinnerActualizar=true;
                    axios.post("updateEmpleado",this.registro).then(res=>{
                        if(res.data="ok"){
                            this.spinnerActualizar=false;
                            this.mensaje="Actualización Exitosa!";
                            this.buscarEmpleados();
                        }
                    });
                }
            },
            validarCarteras(i){
                if(i==2){
                    this.registro.carteras='';
                }else{
                    this.registro.carteras='0';
                }
            }
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
        components: {
            vuePaginate,
        }    
    }
</script>
