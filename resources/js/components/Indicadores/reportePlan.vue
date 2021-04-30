<template>
    <div >
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active text-dark" id="nav-generados-tab" data-toggle="tab" href="#nav-generados" role="tab" aria-controls="nav-home" aria-selected="true">PT. Generados</a>
                <a class="nav-item nav-link text-dark" id="nav-cumplimiento-tab" data-toggle="tab" href="#nav-cumplimiento" role="tab" aria-controls="nav-profile" aria-selected="false">Cumplimiento</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- tab generados -->
            <div class="tab-pane fade show active" id="nav-generados" role="tabpanel" aria-labelledby="nav-generados-tab">
                <div class="py-4">
                    <a href="" class="btn btn-outline-blue" @click.prevent="generarReporte()">
                        <span v-if="spinnerActualizar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Generar Reporte
                    </a>
                </div>
                <div class="row" v-if="viewTabla">
                    <div class="col-md-6">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td>CARTERA</td>
                                    <td>{{dias.dia1}}</td>
                                    <td>{{dias.dia2}}</td>
                                    <td>{{dias.dia3}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="datos==''">
                                    <td colspan="4">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in datos" :key="index" class="text-center">
                                    <td class="text-left px-3">{{item.cartera}}</td>
                                    <td><a href="" class="text-black" @click.prevent="listaPlan(item.idCartera,item.fec1,item.d1,item.cartera)" :class="{'font-bold':item.d1=='SI'}">{{item.d1}}</a></td>
                                    <td><a href="" class="text-black" @click.prevent="listaPlan(item.idCartera,item.fec2,item.d2,item.cartera)" :class="{'font-bold':item.d2=='SI'}">{{item.d2}}</a></td>
                                    <td><a href="" class="text-black" @click.prevent="listaPlan(item.idCartera,item.fec3,item.d3,item.cartera)" :class="{'font-bold':item.d3=='SI'}">{{item.d3}}</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- tab cumplimiento -->
            <div class="tab-pane fade px-2" id="nav-cumplimiento" role="tabpanel" aria-labelledby="nav-cumplimiento-tab">
                <div class="row py-4">
                    <div class="col-md-3">
                        <label for="">Fecha Inicio</label>
                        <input type="date" class="form-control" v-model="busqueda.fechaInicio">
                        <small class="text-danger" v-if="mensajes.msjInicio">{{mensajes.msjInicio}}</small>
                    </div>
                    <div class="col-md-3">
                        <label for="">Fecha Inicio</label>
                        <input type="date" class="form-control" v-model="busqueda.fechaFin">
                        <small class="text-danger" v-if="mensajes.msjFin">{{mensajes.msjFin}}</small>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white">.</label><br>
                        <a href="" class="btn btn-outline-blue" @click.prevent="generarReporteCumplimiento()">
                            <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Generar Reporte
                        </a>
                    </div>
                </div>
                <div class="row" v-if="viewTablaCump">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td>CARTERA</td>
                                    <td v-for="(item,index) in fechas" :key="index">{{(item).substr(8,2)+"/"+(item).substr(5,2)}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="datosCump==''">
                                    <td colspan="2">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in datosCump" :key="index" class="text-center">
                                    <td class="text-left px-3">{{item.cartera}}</td>

                                    <td v-for="i in cantidad">
                                        <a href="" class="text-black" @click.prevent="listaPlanCump(item.idCartera,item.cartera,i,item['dia_'+i])">{{item["dia_"+i]}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- lista de planes -->
            <div class="modal fade" id="modalLista" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-blue text-white pt-4 pb-0 px-4">
                            <p class="">{{plan.cartera}}</p>
                            <a href="" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-times text-white"></span>
                            </a>
                        </div>
                        <div class="modal-body py-3">
                            <div class="">
                                <div class="d-flex justify-content-center py-5" v-if="load">
                                    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                                </div>
                                <div v-else class="table-responsive">
                                    <table class="table table-hover" style="table-layout:fixed;width: 100%;overflow-wrap:break-word;">
                                        <thead class="bg-blue-3 text-white text-center">
                                            <tr>
                                                <td>Plan de Trabajo</td>
                                                <td>Cant. Clientes</td>
                                                <td v-if="opcion==2">Cobertura</td>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="(item,index) in lista" :key="index">
                                                <td>{{item.plan}}</td>
                                                <td>{{formatoNumero(item.clientes,'C')}}</td>
                                                <td v-if="opcion==2">{{item.cobertura}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- end modal -->
        
    </div>
</template>

<script>
    
    export default {
        data() {
            return {
                datos: [],
                spinnerActualizar:false,
                plan:{idCartera:'',cartera:'',clientes:'',plan:''},
                viewTabla:false,
                viewTablaCump:false,
                dias:{dia1:'',dia2:'',dia3:''},
                lista:[],
                load:true,
                spinnerBuscar:false,
                datosCump:[],
                busqueda:{fechaInicio:'',fechaFin:''},
                mensajes:{msjInicio:'',msjFin:''},
                cantidad:0,
                fechas:[],
                datosPlanes:[],
                i:0,
                opcion:1
            }
        },
        methods:{
            generarReporte(){
                this.viewTabla=false;
                this.datos=[];
                this.spinnerActualizar=true;
                axios.get("reportePlan").then(res=>{
                    if(res.data){
                        this.datos=res.data;
                        if(this.datos.length>0){
                            this.dias.dia1=(this.datos[0].fec1).substr(8,2)+"/"+(this.datos[0].fec1).substr(5,2);
                            this.dias.dia2=(this.datos[0].fec2).substr(8,2)+"/"+(this.datos[0].fec2).substr(5,2);
                            this.dias.dia3=(this.datos[0].fec3).substr(8,2)+"/"+(this.datos[0].fec3).substr(5,2);
                        }
                        this.spinnerActualizar=false;
                        this.viewTabla=true;
                    }
                })
            },
            listaPlan(idCartera,fecha,dia,cartera){
                this.lista=[];
                this.load=true;
                this.opcion=1;
                this.plan.cartera=cartera;
                this.plan.idCartera=idCartera;
                this.plan.fecha=fecha;
                if(dia=="SI"){
                    axios.post("reporteListaPlan",this.plan).then(res=>{
                        if(res.data){
                            this.lista=res.data;
                            this.load=false;
                        }
                    })  
                    $("#modalLista").modal();   
                }
            },
            limpiarCampos(){
                this.plan={idCartera:'',cartera:'',fecha:''};
            },
            generarReporteCumplimiento(){
                this.mensajes.msjInicio="";
                this.mensajes.msjFin="";
                if(this.busqueda.fechaInicio!="" && this.busqueda.fechaFin!=""){
                    this.viewTablaCump=false;
                    this.spinnerBuscar=true;
                    axios.post("reporteCumplimiento",this.busqueda).then(res=>{
                        if(res.data){
                            let resp=res.data;
                            this.datosCump=resp["dataFinal"];
                            this.cantidad=resp["cantidadDias"];
                            this.fechas=resp["dias"];
                            this.datosPlanes=resp["datos"];
                            this.spinnerBuscar=false;
                            this.viewTablaCump=true;
                        }
                    })
                }else{
                    if(!this.busqueda.fechaInicio){
                        this.mensajes.msjInicio="Ingresar Fecha";
                    }
                    if(!this.busqueda.fechaFin){
                        this.mensajes.msjFin="Ingresar Fecha";
                    }
                }
            },
            listaPlanCump(idCartera,cartera,i,dia){
                this.lista=[];
                this.load=false;
                this.opcion=2;
                this.plan.cartera=cartera;
                if(dia!='-'){
                    this.datosPlanes.forEach(dato => {
                        if(dato.idCartera==idCartera && dato['dia_'+(i-1)]!=""){
                            this.lista.push({plan:dato.plan,clientes:dato.clientes,cobertura:dato['dia_'+(i-1)]+"%"});
                        }
                    });
                    $("#modalLista").modal();
                }
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
