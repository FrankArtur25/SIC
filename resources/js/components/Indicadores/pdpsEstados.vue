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
                    <small v-if="mensaje" class="text-danger">{{mensaje}}</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-bold">Gestor</label>
                    <select class="form-control" v-model="busqueda.gestor" :disabled="gestores==''">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in gestores" :key="index"  class="option" :value="item.firma">{{item.gestor}}</option>
                    </select>
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
        <div class="table-reponsive" v-if="viewTabla">
            <table class="table table-hover">
                <thead class="bg-blue-3 text-white text-center">
                    <tr>
                        <td>Fecha de PDP</td>
                        <td>% Cumplido</td>
                        <td>Generado</td>
                        <td>Cumplido</td>
                        <td>Caído</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''"  class="text-center">
                        <td colspan="5">No existen registros asignados</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index"> 
                        <td class="text-center px-2">{{item.fecha}}</td>
                        <td class="text-center px-2">{{formatoNumero(Math.round(((item.cumplidos/item.generados)*100)),'C')}}%</td>
                        <td class="text-center px-2">S/. {{formatoNumero(item.generados,'M')}}</td>
                        <td class="text-center px-2">S/. {{formatoNumero(item.cumplidos,'M')}}</td>
                        <td class="text-center px-2">S/. {{formatoNumero(item.caidos,'M')}}</td>
                    </tr>
                </tbody>
            </table>
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
                mensaje:'',
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
                this.mensaje="";
                if(this.busqueda.cartera!=''){
                    this.spinnerBuscar=true;
                    axios.post("reporteEstadosPdps",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.spinnerBuscar=false;
                            this.viewTabla=true;
                        }
                    });
                }else{
                    this.mensaje="Selecciona una cartera";
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
