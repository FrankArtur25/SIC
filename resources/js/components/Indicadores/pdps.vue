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
                        <td class="align-middle">Fecha de Generación</td>
                        <td class="align-middle">Día 0<br>(Mismo día)</td>
                        <td class="align-middle">Día 1</td>
                        <td class="align-middle">Día 2</td>
                        <td class="align-middle">Día 3</td>
                        <td class="align-middle">Día 4</td>
                        <td class="align-middle">Día 5</td>
                        <td class="align-middle">Día 6</td>
                        <td class="align-middle">Día 7</td>
                        <td class="align-middle">Total Monto</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''"  class="text-center">
                        <td colspan="10">No existen registros relacionados a la búsqueda</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index"> 
                        <td class="text-center px-2">{{item.fecha}}</td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato0}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato0!=0">Cant: {{formatoNumero(item.cant0,'C')}}<br>Monto: S/.{{formatoNumero(item.dia0,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato1}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato1!=0">Cant: {{formatoNumero(item.cant1,'C')}}<br>Monto: S/.{{formatoNumero(item.dia1,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato2}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato2!=0">Cant: {{formatoNumero(item.cant2,'C')}}<br>Monto: S/.{{formatoNumero(item.dia2,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato3}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato3!=0">Cant: {{formatoNumero(item.cant3,'C')}}<br>Monto: S/.{{formatoNumero(item.dia3,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato4}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato4!=0">Cant: {{formatoNumero(item.cant4,'C')}}<br>Monto: S/.{{formatoNumero(item.dia4,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato5}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato5!=0">Cant: {{formatoNumero(item.cant5,'C')}}<br>Monto: S/.{{formatoNumero(item.dia5,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato6}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato6!=0">Cant: {{formatoNumero(item.cant6,'C')}}<br>Monto: S/.{{formatoNumero(item.dia6,'M')}}</span></a></td>
                        <td class="text-center px-2"><a href="#" id="tooltip" class="text-black">{{item.dato7}}%<span class="tooltiptext tooltiptext2 text-center" v-if="item.dato7!=0">Cant: {{formatoNumero(item.cant7,'C')}}<br>Monto: S/.{{formatoNumero(item.dia7,'M')}}</span></a></td>
                        <td class="text-center px-2">S/. {{formatoNumero(item.generados,'M')}}</td>
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
                    axios.post("reportePdps",this.busqueda).then(res=>{
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
