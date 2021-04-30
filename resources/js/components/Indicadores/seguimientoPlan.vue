<template>
    <div >
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <label class="font-bold">Cartera</label>
                        <select class="form-control" v-model="busqueda.cartera">
                            <option value="">Seleccionar</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="">
                        <label class="font-bold">Desde</label>
                        <input type="date" class="form-control" v-model="busqueda.fechaInicio">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="">
                        <label class="font-bold">Hasta</label>
                        <input type="date" class="form-control" v-model="busqueda.fechaFin">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="text-white">.</label><br>
                        <a href="" class="btn btn-blue" @click.prevent="generarReporte()">
                            <span v-if="spinnerbuscar" class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>
                            Buscar
                        </a>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead class="bg-blue text-white text-center">
                            <tr>
                                <td>Fecha</td>
                                <td>Cartera</td>
                                <td>Nombre</td>
                                <td>Cant. Clientes</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" v-if="datos==''">
                                <td colspan="5">No se encontraron resultados</td>
                            </tr>
                            <tr v-else v-for="(item,index) in datos" :key="index" class="text-center">
                                <td>{{item.fecha}}</td>
                                <td>{{item.cartera}}</td>
                                <td>{{item.nombre}}</td>
                                <td>{{formatoNumero(item.clientes,'C')}}</td>
                                <td>
                                    <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="detallePlan(index)" role="button" data-toggle="modal" data-target="#modalDetalle">Detalle</a>
                                    <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="resultadosPlan(index)" role="button" data-toggle="modal" data-target="#modalResultados">Resultados</a>
                                    <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="modalFechas(item.id,item.nombre,item.fecha,item.fechaFin)" role="button" data-toggle="modal" data-target="#modalEditar"><i class="fa fa-edit fa-sm"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-blue text-white pt-4 pb-0 px-5 ">
                            <p>Detalle Plan de Trabajo</p>      
                            <a href="" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-times text-white"></span>
                            </a>
                        </div>
                        <div class="modal-body py-3">
                            <div class="">
                                <table class="table table-hover" style="table-layout:fixed;width: 100%;overflow-wrap:break-word;">
                                    <tbody>
                                        <tr>
                                            <td class="px-3 bg-gray" style="width:40%">Cartera</td>
                                            <td class="px-3" style="width:60%">{{detalle.cartera}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Fecha</td>
                                            <td class="px-3">{{detalle.fecha}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Cant. Clientes</td>
                                            <td class="px-3">{{detalle.clientes}}</td>
                                        </tr>
                                        <tr v-for="(item,index) in detalle.detalle" :key="index">
                                            <td class="px-3 bg-gray">{{item.titulo}}</td>
                                            <td class="px-3">{{item.valor}}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 bg-gray">Speech</td>
                                            <td class="px-3">{{detalle.speech}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- end modal -->
        <!-- modalResultados -->
            <div class="modal fade" id="modalResultados" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-blue text-white px-3 pb-0">
                            <p>{{resultados.campana}}</p>      
                            <a href="" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-times text-white"></span>
                            </a>
                        </div>
                        <div class="modal-body py-4 px-4 overflow-auto" style="height:400px;">
                            <div class="text-center d-flex justify-content-center py-5" v-if="loadingIn">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                            <div class="row" v-else>
                                <div class="col-md-6">
                                    <div style="height:280px;" class="overflow-auto">
                                        <a href="" @click.prevent="verResultadosUsuarios(0,resultados.total,'Todos los Usuarios')" class="btn btn-outline-blue btn-sm btn-block  panel-list-option">Todos los Usuarios</a>
                                        <div v-if="usuarios!=''" class="py-2">
                                            <a href="#" v-for="(item,index) in usuarios" :key="index" @click.prevent="verResultadosUsuarios(item.id,item.cantidad,item.usuario)" :id="'b'+item.id" class="btn btn-outline-blue btn-sm btn-block panel-list-option">{{item.usuario}} ({{item.cantidad}})</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td class="px-3 bg-gray font-bold text-center" colspan="2">{{gestor}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">Cobertura</td>
                                                <td class="px-3">{{resultados.cobertura}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">Contactabilidad</td>
                                                <td class="px-3">{{resultados.contactabilidad}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">PDPS</td>
                                                <td class="px-3">{{resultados.pdps}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">Confirmaciones</td>
                                                <td class="px-3">{{resultados.confirmaciones}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">Intensidad</td>
                                                <td class="px-3">{{resultados.intensidad}}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 bg-gray">En negociación</td>
                                                <td class="px-3">{{resultados.negociacion}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modal actualizacion de fechas -->
        <div class="modal fade modal-carga" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-blue-3 text-white">
                        <p class="modal-title text-white">{{actualizar.plan}}</p>
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
    </div>
</template>

<script>
    
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                usuarios: [],
                loadingIn : false,
                spinnerbuscar:false,
                busqueda:{cartera:'',fechaInicio:'',fechaFin:''},
                detalle:{cartera:'',clientes:'',fecha:'',detalle:[],speech:''},
                resultados:{cobertura:'',contactabilidad:'',pdps:'',confirmaciones:'',intensidad:'',negociacion:'',campana:'',total:''},
                idcampana:0,
                gestor:'',
                actualizar:{fechaInicio:'',fechaFin:'',idCampana:'',plan:''},
                spinnerFechas:false,
                mensaje:''
            }
        },
        methods:{
            generarReporte(){
                this.datos=[];
                this.spinnerbuscar=true;
                if(this.busqueda.cartera!='' && this.busqueda.fechaInicio!='' && this.busqueda.fechaFin!=''){
                    axios.post("listaPlanes",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.spinnerbuscar=false;
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.spinnerbuscar=false;
                        this.datos=[];
                    }, 500);
                }
            },
            detallePlan(index){
                this.detalle.cartera=this.datos[index].cartera;
                this.detalle.clientes=this.datos[index].clientes;
                this.detalle.speech=this.datos[index].speech;
                this.detalle.fecha=this.datos[index].fecha+' - '+this.datos[index].fechaFin;
                const detalles=this.datos[index].detalle.split(";");
                this.detalle.detalle=[];
                // for(var i=0;i<detalle.length;i++){
                //     this.detalle.detalle.push({valor:detalle[i]});
                // }
                const auxSubCat = {};
                detalles.forEach(detalle => {
                    const auxCategorias = detalle.split(":");
                    // auxSubCat[auxCategorias[0]] = auxCategorias[1].split(",");
                    this.detalle.detalle.push({titulo:auxCategorias[0],valor:auxCategorias[1]});
                });
            },
            resultadosPlan(index){
                this.loadingIn=true;
                this.usuarios=[];
                this.idcampana=this.datos[index].id;
                this.resultados.campana=this.datos[index].nombre;
                this.resultados.total=this.datos[index].clientes;
                axios.get("usuariosPlan/"+this.idcampana).then(res=>{
                    if(res.data){
                        this.usuarios=res.data;
                        this.verResultadosUsuarios(0,this.resultados.total,'Todos los Usuarios');
                        this.loadingIn=false;
                    }
                })
            },
            verResultadosUsuarios(usuario,cantidad,gestor){
                this.limpiarResultados();
                var datos={idPlan:this.idcampana,idEmpleado:usuario};
                this.gestor=gestor;
                $("#b"+usuario).addClass('btn-active');
                $("#b"+usuario).removeClass('btn-white');
                $(".panel-list-option").not('#b'+usuario).addClass("btn-white");
                axios.post("resultadosPlan",datos).then(res=>{
                    if(res.data){
                        res=res.data;
                        if(res.length>0){
                            this.resultados.cobertura=res[0].can_clientes?this.formatoNumero((res[0].can_clientes/cantidad)*100,'M')+"%":0 +"%";
                            this.resultados.contactabilidad= (res[0].can_contacto/cantidad)*100?this.formatoNumero((res[0].can_contacto/cantidad)*100,'M')+"%":0 +"%";
                            this.resultados.pdps= (res[0].can_pdp || res[0].can_pdp==null ?res[0].can_pdp:0) +"; S/."+(res[0].monto_pdp || res[0].monto_pdp==null?res[0].monto_pdp:0);
                            this.resultados.confirmaciones= (res[0].can_conf || res[0].monto_conf==null?res[0].can_conf:0) +"; S/."+(res[0].monto_conf || res[0].monto_conf==null ?res[0].monto_conf:0);
                            this.resultados.intensidad= res[0].intensidad?res[0].intensidad:0;
                            this.resultados.negociacion= (res[0].can_mot_np || res[0].can_mot_np==null?res[0].can_mot_np:0) +"; "+ (res[0].can_mot_np || res[0].can_mot_np==null?this.formatoNumero((res[0].can_mot_np/cantidad)*100,'M'):0)+"%";
                        }
                    }
                })
            },
            limpiarResultados(){
                this.resultados.cobertura='0%';
                this.resultados.contactabilidad='0%';
                this.resultados.pdps='0; S/.0.00';
                this.resultados.confirmaciones='0; S/.0.00';
                this.resultados.intensidad='0';
                this.resultados.negociacion='0; 0%';
            },
            modalFechas(id,nom,inicio,fin){
                this.spinnerFechas=false;
                this.mensaje='';
                this.actualizar.idCampana=id;
                this.actualizar.plan=nom;
                this.actualizar.fechaInicio=(inicio).replace(" ","T");
                this.actualizar.fechaFin=(fin).replace(" ","T");
                $('#modalFechas').modal({backdrop: 'static', keyboard: false});
            },
            actualizarFechas(){
                this.mensaje='';
                this.spinnerFechas=true;
                axios.post("actualizarFechaPlan",this.actualizar).then(res=>{
                    if(res.data=="ok"){
                        this.generarReporte();
                        this.spinnerFechas=false;
                        this.mensaje='Actualización Exitosa!';
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
            
        }    
    }
</script>
