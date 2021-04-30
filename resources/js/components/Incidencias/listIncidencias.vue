<template>
    <div >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Tipo de Incidencia</label>
                    <select class="form-control" v-model="busqueda.incidencia">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in incidencias" :key="index"  class="option" :value="item.id">{{item.incidencia}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4" v-if="tipoacceso!=1">
                <div class="form-group">
                    <label for="">Supervisor</label>
                    <select class="form-control" v-model="busqueda.supervisor">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in supervisores" :key="index"  class="option" :value="item.codigo">{{item.supervisor}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4" v-if="gestores!=''">
                <div class="form-group">
                    <label for="">Gestor</label>
                    <select class="form-control" v-model="busqueda.gestor">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in gestores" :key="index"  class="option" :value="item.gestor">{{item.gestor}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Estado de Recuperación</label>
                    <select class="form-control" v-model="busqueda.estadoRec">
                        <option value="">Seleccionar</option>
                        <option value="1">Pendiente de Recuperación</option>
                        <option value="2">Recuperado</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Estado - Hora Fin</label>
                    <select class="form-control" v-model="busqueda.estadoHora">
                        <option value="">Seleccionar</option>
                        <option value="0">Hora No definida</option>
                        <option value="1">Hora Definida</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4" >
                <label for="">Fecha Desde-Hasta</label>
                <div class="d-flex" style="gap:2px;">
                    <input type="date" class="form-control w-50" v-model="busqueda.fechaDesde">
                    <input type="date" class="form-control w-50" v-model="busqueda.fechaHasta">
                </div>
            </div>
        </div>
        <div class="pt-2">
            <a href="" @click.prevent="buscar()" class="btn btn-outline-blue mb-4">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Buscar
            </a>
            <a href="" @click.prevent="limpiar()" class="btn btn-outline-blue mb-4">Limpiar</a>
        </div>
        <div v-if="viewTabla">
            <div class="table-responsive" >
                <paginate ref="paginator" name="datos" :list="datos" :per="20" class="px-0">
                    <table class="table table-hover">
                        <thead class="bg-blue-3 text-white text-center">
                            <tr>
                                <td class="align-middle">Usuario</td>
                                <td class="align-middle">Tipo de Usuario</td>
                                <td class="align-middle">Gestor</td>
                                <td class="align-middle">Incidencia<br>({{total}})</td>
                                <td class="align-middle">Fecha</td>
                                <td class="align-middle">Hora<br>Inicio</td>
                                <td class="align-middle">Hora<br>Fin</td>
                                <td class="align-middle">Detalle</td>
                                <td class="align-middle">Estado</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="datos==''" class="text-center">
                                <td colspan="9">No se encontraron registros</td>
                            </tr>
                            <tr v-else v-for="(item,index) in paginated('datos')" :key="index">
                                <td class="px-2">{{item.usuario}}</td>
                                <td class="px-2">{{item.tipo_usuario}}</td>
                                <td class="px-2">{{item.gestor}}</td>
                                <td class="px-2">{{item.incidencia}}</td>
                                <td class="text-center px-2">{{item.fecha}}
                                    <a v-if="item.idIncidencia==4" href="#" id="tooltip"><i class="far fa-calendar text-blue"></i><span class="tooltiptext tooltiptext2 text-center">FR: {{item.fecha_add}}</span></a>
                                </td>
                                <td class="px-2">{{item.horaInicio}}</td>
                                <td class="px-2">{{item.horaFin}}</td>
                                <td class="px-2">{{item.detalle}}</td>
                                <td class="px-2">{{item.estado}}</td>
                                <td class="border-0"><a href="" class="btn btn-sm btn-outline-blue" data-toggle="modal" data-target="#modal-editar" @click.prevent="selectDatos(item.id)">Editar</a></td>
                            </tr>
                        </tbody>
                    </table>
                </paginate>
            </div>
            <paginate-links  for="datos" v-if="datos.length>0" 
                :async="true"                     
                :limit="4"
                :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"
            ></paginate-links>
        </div>


        <!-- modal -->
        <div class="modal fadeInRight px-0" id="modal-editar" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-rigth modal-xl w-4" role="document" style="width:330px;">
                <div class="modal-content">
                    <div class="modal-header bg-blue pt-3 pl-3 pr-4">
                        <div>
                            <p class="p-title mb-0 text-white">{{editar.gestor}}</p>
                            <small class="text-white">{{editar.incidencia}}</small>
                        </div>
                        <a href="" class="close " data-dismiss="modal" aria-label="Close">
                            <span class="fa fa-times text-white"></span>
                        </a>
                    </div>
                    <div class="modal-body bg-white overflow-auto pl-3 pr-4">
                        <div class="d-flex" style="gap:8px">
                            <div class="form-group w-50">
                                <label for="">Hora Inicio</label>
                                <input type="time" class="form-control" v-model="editar.horaInicio">
                                <small v-if="mensaje.hora" class="text-danger">{{mensaje.hora}}</small>
                            </div>
                            <div class="form-group w-50">
                                <label for="">Hora Fin</label>
                                <input type="time" class="form-control" v-model="editar.horaFin">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Detalle de la Incidencia</label>
                            <textarea class="form-control" rows="7" v-model="editar.detalle"></textarea>
                            <small v-if="mensaje.detalle" class="text-danger">{{mensaje.detalle}}</small>
                        </div>
                        <div class="form-group" v-if="tipoacceso==7">
                            <label for="">Estado de Recuperación</label>
                            <select class="form-control" v-model="editar.estadoRec">
                                <option value="">Seleccionar</option>
                                <option value="1">Pendiente de Recuperación</option>
                                <option value="2">Recuperado</option>
                            </select>
                        </div>
                        <div class="form-group" v-else-if="editar.estadoRec!=2">
                            <label for="">Estado de Recuperación</label>
                            <select class="form-control" v-model="editar.estadoRec">
                                <option value="0">No recuperará</option>
                                <option value="1">Pendiente de Recuperación</option>
                            </select>
                        </div>
                        <div class="w-100 pt-3">
                            <a href="" class="btn btn-outline-blue btn-block" @click.prevent="actualizarDatos()">
                                <span v-if="spinnerEditar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Actualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- endmodal -->
    </div>
</template>

<script>
    import vuePaginate from '../../../../node_modules/vue-paginate';
    export default {
        props:['bdatos','tipoacceso'],
        data() {
            return {
                incidencias:[],
                busqueda:{incidencia:'',fechaDesde:'',fechaHasta:'',supervisor:'',gestor:'',estadoRec:'',estadoHora:''},
                datos:[],
                paginate: ['datos'],
                spinnerBuscar:false,
                viewTabla:false,
                total:0,
                supervisores:[],
                gestores:[],
                editar:{horaInicio:'',horaFin:'',detalle:'',id:'',gestor:'',incidencia:'',estadoRec:''},
                spinnerEditar:false,
                mensaje:{hora:'',detalle:''}
            }
        },
        created(){
            this.listarDatos();
        },
        methods:{
            listarDatos(){
                this.incidencias=this.bdatos[2];
                this.supervisores=this.bdatos[0];
                this.gestores=this.bdatos[1];
            },
            buscar(){
                this.spinnerBuscar=true;
                this.viewTabla=false;
                axios.post("buscarIncidencias",this.busqueda).then(res=>{
                    if(res.data){
                        this.datos=res.data;
                        this.total=this.datos.length;
                        this.spinnerBuscar=false;
                        this.viewTabla=true;
                    }
                });
            },
            selectDatos(id){
                this.mensaje.hora='';
                this.mensaje.detalle='';
                for(let i=0;i<this.datos.length;i++){
                    if(this.datos[i].id==id){
                        this.editar.horaInicio=this.datos[i].horaInicio;
                        this.editar.horaFin=this.datos[i].horaFin;
                        this.editar.detalle=this.datos[i].detalle;
                        this.editar.incidencia=this.datos[i].incidencia;
                        this.editar.gestor=this.datos[i].gestor;
                        this.editar.estadoRec=this.datos[i].idestado;
                        this.editar.id=this.datos[i].id;
                    }
                }
            },
            actualizarDatos(){
                this.mensaje.hora='';
                this.mensaje.detalle='';
                if(this.editar.horaInicio!=null && this.editar.horaInicio!=''  && this.editar.detalle!=''){
                    this.spinnerEditar=true;
                    axios.put("editarIncidencia",this.editar).then(res=>{
                        if(res.data=="ok"){
                            axios.post("buscarIncidencias",this.busqueda).then(res=>{
                                if(res.data){
                                    this.datos=res.data;
                                    this.total=this.datos.length;
                                    $("#modal-editar").modal('hide');
                                    toastr.info('Incidencia actualizada con éxito', 'Actualización Exitosa!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                                    this.spinnerEditar=false;
                                }
                            });
                        }
                    });
                }else{
                    if(!this.editar.horaInicio){
                        this.mensaje.hora="Completar campo";
                    }
                    if(!this.editar.detalle){
                        this.mensaje.detalle="Completar campo";
                    }
                }
            },
            limpiar(){
                this.busqueda.incidencia='';
                this.busqueda.supervisor='';
                this.busqueda.gestor='';
                this.busqueda.estadoRec='';
                this.busqueda.estadoHora='';
                this.busqueda.fechaDesde='';
                this.busqueda.fechaHasta='';
            }
        },
        components:{
            vuePaginate
        }
    }
</script>
