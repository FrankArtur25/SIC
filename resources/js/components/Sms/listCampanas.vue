<template>
    <div >
        <div  class="modal" tabindex="-1" role="dialog" id="modalCarga" >
            <div v-if="loadCarga" class="d-flex justify-content-center align-items-center text-center" style="margin-top:150px;">
                <div class="text-center">
                    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                    <p style="font-20px;" class="text-white">Enviando Campaña...</p>
                </div>
            </div>
            <div v-else style="margin-top:150px;" class="d-flex justify-content-center align-items-center">
                <div v-if="viewMensajeValidar" class="text-center fadeIn">
                    <p class="text-white font-15">La campaña "{{nombreCampana}}" ya se encuentra enviada.<br>¿Desea enviar nuevamente?</p>
                    <div>
                        <a href="" @click.prevent="enviar()" class="btn bg-orange px-3">Si</a>
                        <a href="" @click.prevent="salirModal()" class="btn bg-white px-3">No</a>
                    </div>
                </div>
                <div v-else class="text-center fadeIn">
                    <p><i class="fa fa-thumbs-up fa-4x text-white"></i></p>
                    <p style="font-20px;" class="text-white">Campaña enviada correctamente!</p>
                    <small  class="text-white">Visualiza el avance de tu campaña<br>en el Panel de Incio</small>
                </div>
            </div>
        </div>
        <div class="text-center d-flex justify-content-center pt-5" v-if="loadingIn">
           <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
        
        <div class="" v-else>
            <div class="row">
                <div class="col-md-4 pb-2 form-group">
                    <label for="" class="">Cartera</label>
                    <select class="form-control" v-model="busqueda.cartera">
                        <option value="">SELECCIONAR</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
                <div class="col-md-3 pb-2 form-group">
                    <label for="" class="">F. Programación</label> 
                    <input type="date" class="form-control" v-model="busqueda.fecha_programada">
                </div>
                <div class="col-md-3 form-group">
                    <label for="" class="text-white">.</label><br>
                    <a href="" class="btn btn-outline-blue mb-3" @click.prevent="buscar()">
                        <span v-if="btnbuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Buscar
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <paginate name="pagLista" :list="lista" :per="10" class="px-0">
                            <table class="table table-hover">
                                <thead class="text-center bg-blue-3 text-white">
                                    <tr>
                                        <td class="">F. Programación</td>
                                        <td class="align-middle">Campaña ({{total}})</td>
                                        <td class="align-middle">Cartera</td>
                                        <td class="align-middle">Cant. de Clientes</td>
                                        <td class="align-middle">Cant. de SMS</td>
                                        <td class="align-middle"></td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-if="loading==false && lista==''">
                                        <td colspan="6" class="text-center">No se encontraron campañas</td>
                                    </tr>
                                    <tr v-for="(item, index) in paginated('pagLista')" :key="index" v-else>
                                        <td class="text-center">{{item.fecha}}</td>
                                        <td>{{item.nombre}}</td>
                                        <td>{{item.cartera}}</td>
                                        <td class="text-center">{{item.cant_cli}}</td>
                                        <td class="text-center">{{item.cant_sms}}</td>
                                        <td class="text-center">
                                            <a href="" @click.prevent="modalDetalle(item.id)" class="btn btn-outline-green btn-sm" >Detalle</a>
                                            <a v-if="item.enviar==0" href="" @click.prevent="enviarCampana(item.id,item.nombre)" class="btn btn-outline-green btn-sm" >Enviar</a>
                                        </td>
                                    </tr>                                        
                                </tbody>
                            </table>
                        </paginate>
                    </div>
                    <paginate-links for="pagLista" 
                        :async="true"                     
                        :show-step-links="true"
                        :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"
                    ></paginate-links>
                </div>
            </div>
        </div>
            

    <!-- modalDetalle -->
        <div class="modal fade" id="detallemodal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                        <div class="modal-header bg-blue-3 text-white pt-4 pb-0 px-5 ">
                            <div>
                                <p class="p-title"><b>{{detalle.dnombre}}</b> <span class="badge badge-danger py-1 px-2 font-12">{{detalle.cantSms}} SMS</span><span class="badge badge-primary py-1 px-2 font-12 ml-2">{{detalle.cantCli}} CLIENTES</span> </p>
                                <div class="d-flex flex-wrap">  
                                    <p class=""><b>Cartera:</b> {{detalle.cartera}} </p>
                                    <p class="pl-3"><b>Fecha de Creación:</b> {{detalle.fecCreate}}</p>
                                    <p class="pl-3"><b>Fecha de Envío:</b> {{detalle.fecha_prog}}</p>
                                </div>
                            </div>
                            <a href="" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-times text-white"></span>
                            </a>
                        </div>
                        <div class="modal-body py-0 overflow-auto" style="height:400px;">
                            <div v-if="loadingModal" class="d-flex justify-content-center pt-5">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                            <div v-else>
                                <div class="row">
                                    <div class="col-md-3 p-0">
                                        <div class="overflow-auto p-0 criterios">
                                            <p class="tittle-criterios mt-3">Criterios de Campaña</p><hr class="mb-0">
                                            <ul>
                                                <li v-for="(item,index) in condiciones" :key="index" :id="'li'+index">
                                                    <a href="" @click.prevent="detalleCondicion(index)" class="w-100 d-flex"  >{{item.nomCond}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-9 p-0">
                                        <div class="alert alert-info m-0" style="height:450px;">
                                            <div v-if="viewDetalleCondicion" class="fadeIn">
                                                <div class="d-flex">
                                                    <div class="mr-5">
                                                        <b>Cantidad de Clientes</b>
                                                        <p class="mb-0">{{this.detalleCondiciones.cantcli}} </p>
                                                    </div>
                                                    <div class="">
                                                        <b>Cantidad de SMS</b>
                                                        <p class="mb-0">{{this.detalleCondiciones.cantsms}} </p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <b v-if="detalleCondiciones.desc">{{this.detalleCondiciones.nomCond}}</b>
                                                <p v-if="detalleCondiciones.desc">{{this.detalleCondiciones.desc}} </p>
                                                <hr v-if="detalleCondiciones.desc">
                                                <b>Destino</b>
                                                <p class="mb-0">{{this.detalleCondiciones.destino}}</p>
                                                <p>{{this.detalleCondiciones.numero}}</p>
                                                <hr>
                                                <b>{{this.detalleCondiciones.nomSpc}}</b>
                                                <p>{{this.detalleCondiciones.sms}}</p>
                                            </div>
                                            <div v-else class="text-center pt-5">
                                                    <i class="fas fa-list-alt fa-3x"></i><br><br>
                                                    <b> No se encontraron datos</b>
                                                    <p> Porfavor selecione un criterio.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
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
        props:['carteras','rol'],
        data() {
            return {
                lista: [],
                orden: [],
                paginate: ['pagLista'],
                loading : false,
                loadCarga:false,
                loadingIn : false,
                loadingModal : true,
                color:'#3367d6',
                colorModal:'#ec7c48',
                total:0,
                idCamp:0,
                detalle:{dnombre:'',fecha_prog:'',cartera:'',cantSms:'',cantCli:'',fecCreate:''},
                condiciones:[],
                detalleCondiciones:{nomSpc:'',sms:'',desc:'',cantcli:0,cantsms:0,nomCond:''},
                viewDetalleCondicion:false,
                busqueda:{cartera:'',fecha_programada:''},
                btnbuscar:false,
                viewMensajeValidar:false,
                idcampana:0,
                nombreCampana:''
            }
        },
        created(){

        },
        methods:{
            buscar(){
                this.btnbuscar=true;
                axios.post("buscarCampana",this.busqueda).then(res=>{
                    if(res.data){
                        this.lista=res.data;
                        this.total=this.lista.length;
                        this.btnbuscar=false;
                    }
                })
            },
            modalDetalle(id){
                this.loadingModal=true;
                this.viewDetalleCondicion=false;
                this.limpiarCampos();
                for(var i=0;i<this.lista.length;i++){
                    if(this.lista[i].id==id){
                        this.detalle={
                            dnombre:this.lista[i].nombre,
                            fecha_prog:this.lista[i].fecha,
                            cartera:this.lista[i].cartera,
                            cantSms:this.lista[i].cant_sms,
                            cantCli:this.lista[i].cant_cli,
                            fecCreate:this.lista[i].fecha_create
                        }
                        axios.get("condicionCampana/"+id).then(res=>{
                            if(res.data){
                                this.condiciones=res.data;
                                this.loadingModal=false;
                            }
                        })
                        break;
                    }
                }

                $('#detallemodal').modal();
            },
            detalleCondicion(index){
                $('#li'+index).attr("class","bg-celeste");
                for(var i=0;i<this.condiciones.length;i++){
                    if(i!=index){
                        $('#li'+i).attr("class","bg-white");
                    }
                }
                this.viewDetalleCondicion=true;
                this.detalleCondiciones={
                    nomSpc:this.condiciones[index].nomSpc,
                    sms:this.condiciones[index].sms,
                    desc:this.condiciones[index].descripcion,
                    cantcli:this.condiciones[index].cantcli,
                    cantsms:this.condiciones[index].cantsms,
                    nomCond:this.condiciones[index].nomCond,
                    destino:this.condiciones[index].destino,
                    numero:this.condiciones[index].numero
                }
            },
            limpiarCampos(){
                 this.detalleCondiciones.cantcli=0; 
                this.detalleCondiciones.cantsms=0;
                this.detalleCondiciones.nomCond="";
                this.detalleCondiciones.desc="";
                this.detalleCondiciones.nomSpc="";
                this.detalleCondiciones.sms="";
            },
            enviarCampana(id,nombre){
                this.idcampana=id;
                this.nombreCampana=nombre;
                this.loadCarga=true;
                $("#modalCarga").modal({backdrop: 'static', keyboard: false});
                axios.get("validarEnvio/"+id).then(res=>{
                    let validar=res.data;
                    if(validar[0].cantidad==0){
                        this.viewMensajeValidar=false;
                        this.enviar();    
                    }else{
                        this.loadCarga=false;
                        this.viewMensajeValidar=true;
                    }
                });
            },
            enviar(){
                this.loadCarga=true;
                axios.get("enviarCampana/"+this.idcampana).then(res=>{
                    if(res.data=="ok"){
                        this.loadCarga=false;
                        this.viewMensajeValidar=false;
                        setTimeout(() => {
                            $("#modalCarga").modal('hide');
                        }, 1500);
                    }
                })
            },
            salirModal(){
                $("#modalCarga").modal('hide');
            }
        },
        components: {
            vuePaginate
        }    
    }
</script>
