<template>
    <div >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-bold">Nombre de Cartera</label>
                    <select class="form-control" v-model="busqueda.cartera" @change="listarGestores(busqueda.cartera)">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensajeCartera" class="text-danger">{{mensajeCartera}}</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-bold">Gestor</label>
                    <select class="form-control" v-model="busqueda.gestor" :disabled="gestores==''">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in gestores" :key="index"  class="option" :value="item.firma">{{item.gestor}}</option>
                    </select>
                    <small v-if="mensajeGestor" class="text-danger">{{mensajeGestor}}</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="text-white">-</label><br>
                    <a href="" @click.prevent="buscar()" class="btn btn-outline-blue">
                        <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>
        <div class="row" v-if="viewTabla">
            <div class="col-md-6">
                <div class="table-reponsive" >
                    <table class="table table-hover">
                        <thead class="bg-blue-3 text-white text-center">
                            <tr>
                                <td>Fecha de Generación</td>
                                <td>% Cumplido</td>
                                <td>Generado</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="datos==''"  class="text-center">
                                <td colspan="3">No existen registros asignados</td>
                            </tr>
                            <tr v-else v-for="(item,index) in datos" :key="index"> 
                                <td class="text-center px-2">{{item.fecha}}</td>
                                <td class="text-center px-2">
                                    <a  href="#" id="tooltip" class="text-black">{{formatoNumero(Math.round(((item.monto_cumplidos/item.generados)*100)),'C')}}%<span v-if="item.cant_cumplidos>0" class="tooltiptext tooltiptext2 text-center">Cant: {{item.cant_cumplidos}}<br>Monto: S/.{{item.monto_cumplidos}}</span></a>
                                </td>
                                <td class="text-center px-2" :class="{'text-danger':item.color==1,'text-blue':item.color==0}">S/. {{formatoNumero(item.generados,'M')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2" v-if="datos!=''">
                <table class="table text-center">
                    <tr class="bg-blue-3 text-white">
                        <td>Estándar</td>
                    </tr>
                    <tr>
                        <td>S/.{{formatoNumero(datos[0].estandar,'M')}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras'],
        data() {
            return {
                gestores:[],
                datos:[],
                busqueda:{gestor:'',cartera:''},
                spinnerBuscar:'',
                mensajeCartera:'',
                mensajeGestor:'',
                viewTabla:false
            }
        },
        methods:{
           listarGestores(cartera){
                this.busqueda.gestor="";
                this.gestores=[];
                if(cartera!=''){
                    axios.get("listaGestores/"+cartera).then(res=>{
                        if(res.data){
                            this.gestores=res.data;
                        }
                    });
                }
            },
           buscar(){
                this.viewTabla=false;
                this.mensajeCartera="";
                this.mensajeGestor="";
                if(this.busqueda.cartera!='' && this.busqueda.gestor!=''){
                    this.spinnerBuscar=true;
                    axios.post("reporteEstandarPdps",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.spinnerBuscar=false;
                            this.viewTabla=true;
                        }
                    });
                }else{
                    if(!this.busqueda.cartera){
                        this.mensajeCartera="Selecciona una cartera";
                    }
                    if(!this.busqueda.gestor){
                        this.mensajeGestor="Selecciona un gestor";
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
