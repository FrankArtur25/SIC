<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                    <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                    <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera">
                        <option selected value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensajes.cartera" class="text-danger">{{mensajes.cartera}}</small>
            </div>
            <div class="col-md-6">
                <label class="font-bold">Fecha Gestión (Inicio - Fin)</label>
                <div class="d-flex w-100 pt-1" style="gap:8px">
                    <div class="w-50">
                        <input type="datetime-local" class="form-control" v-model="busqueda.fechaInicio">
                    </div>
                    <div class="w-50">
                        <input type="datetime-local" class="form-control" v-model="busqueda.fechaFin">
                    </div>
                </div>
                <small v-if="mensajes.fecha" class="text-danger">{{mensajes.fecha}}</small>
            </div>
            <div class="col-md-3 py-3">
                <a href="" @click.prevent="generarReporte()" class="btn btn-outline-blue">
                    <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Generar Reporte
                </a>
            </div>
        </div>
        <div v-if="viewTabla" v-for="(item,index) in datos" :key="index">
            <p class="font-bold mb-0" v-if="item.cartera">{{item.cartera}}</p>
            <div class="row py-3">
                <div class="col-md-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td colspan="2" class="bg-blue-3 text-white text-center">CONTACTOS</td>
                            </tr>
                            <tr>
                                <td class="bg-gray px-2">{{item.gestores}} Gestores</td>
                                <td class="text-center">{{formatoNumero(item.estandar_contacto,'C')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Resultados</td>
                                <td class="text-center">{{formatoNumero(item.contacto,'C')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Desfase</td>
                                <td class="text-center">{{formatoNumero(item.estandar_contacto-item.contacto,'C')}}</td>
                            </tr>  
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td colspan="2" class="bg-blue-3 text-white text-center">PDPS</td>
                            </tr>
                            <tr>
                                <td class="bg-gray px-2">{{item.gestores}} Gestores</td>
                                <td class="text-center">{{formatoNumero(item.estandar_pdp,'M')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Resultados</td>
                                <td class="text-center">{{formatoNumero(item.pdps,'M')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Desfase</td>
                                <td class="text-center">{{formatoNumero(item.estandar_pdp-item.pdps,'M')}}</td>
                            </tr>  
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td colspan="2" class="bg-blue-3 text-white text-center">CONFIRMACIONES</td>
                            </tr>
                            <tr>
                                <td class="bg-gray px-2">{{item.gestores}} Gestores</td>
                                <td class="text-center">{{formatoNumero(item.estandar_conf?item.estandar_conf:0,'M')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Resultados</td>
                                <td class="text-center">{{formatoNumero(item.conf,'M')}}</td>
                            </tr>  
                            <tr>
                                <td class="bg-gray px-2">Desfase</td>
                                <td class="text-center">{{formatoNumero(item.estandar_conf-item.conf,'M')}}</td>
                            </tr>  
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:["carteras"],
        data() {
            return {
                datos: [],
                busqueda:{cartera:'',fechaInicio:'',fechaFin:''},
                viewTabla:false,
                mensajes:{cartera:'',fecha:''},
                spinnerBuscar:false,
                // resultados:{gestores:0,cantidad:0,estandar:0,gestoresPdps:0,pdps:0,estandarPdps:0,gestoresConf:0,conf:0,estandarConf:0}
            }
        },
        methods:{
            generarReporte(){
                this.viewTabla=false;
                this.datos=[];
                if(this.busqueda.cartera!='' && this.busqueda.fechaInicio!='' && this.busqueda.fechaFin!=''){
                    this.spinnerBuscar=true;
                    axios.post("reporteEstandarCartera",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.spinnerBuscar=false;
                            this.viewTabla=true;
                        }
                    })
                }else{
                    if(!this.busqueda.cartera){
                        this.mensajes.cartera="Completar el campo";
                    }
                    if(!this.busqueda.fechaInicio && !this.busqueda.fechaFin){
                        this.mensajes.fecha="Completar el campo";
                    }
                    
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
