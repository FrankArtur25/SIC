<template>
    <div >
         <div class="row mt-3 mb-1">
            <div class="col-md-4 col-xl-4">
                <div class="">
                    <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                    <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera">
                        <option selected value="">Seleccionar</option>
                        <option value="0">TODAS LAS CARTERAS</option>
                        <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensajeCartera" class="text-danger">{{mensajeCartera}}</small>
                </div>
            </div>
            <div class="col-md-3 col-xl-2">
                <div class=""> 
                    <label for="indicador" class="font-bold col-form-label text-dark text-righ">Call</label>
                    <select name="indicador" id="indicador" class="form-control" v-model="busqueda.call">
                        <option selected value="">Seleccionar</option>
                        <option v-for="(item,index) in calls" :key="index" class="option" :value="item.id">{{item.valor}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-xl-2">
                <label class="font-bold col-form-label text-dark text-righ">Fecha de Gestión</label>
                <div class="">
                    <input type="date" class="form-control" v-model="busqueda.fecha">
                    <small v-if="mensajeFecha" class="text-danger">{{mensajeFecha}}</small>
                </div>
            </div>
            <div class="col-md-3 col-xl-3">
                <label class="font-bold col-form-label text-dark text-righ">Hora(Desde-Hasta)</label>
                <div class="d-flex w-100">
                    <div class="w-50">
                        <input type="time" class="form-control" v-model="busqueda.horaInicio">
                    </div>
                    <div class="w-50">
                        <input type="time" class="form-control" v-model="busqueda.horaFin">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class=" py-3">
                    <a href="" @click.prevent="generarReporte()" class="btn btn-outline-blue btn-block waves-effect">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive"  v-if="viewTable" >
            <table class="table table-hover">
                <thead class="bg-blue text-white">
                    <tr class="text-center">
                        <td class="align-middle">Nombre</td>
                        <td class="align-middle">Cartera</td>
                        <td class="align-middle">Perfil</td>
                        <td class="align-middle">Call</td>
                        <td class="align-middle">Clientes<br>Asignados</td>
                        <td class="align-middle">Deuda<br>S/.</td>
                        <td class="align-middle">Primera<br>Gestión</td>
                        <td class="align-middle">Última<br>Gestión</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''">
                        <td colspan="8" class="text-center">No existen datos relacionados</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index">
                        <td>{{item.gestor}}</td>
                        <td>{{item.cartera}}</td>
                        <td>{{item.perfil}}</td>
                        <td>{{item.callg}}</td>
                        <td class="text-center">{{formatoNumero(item.clientes,'C')}}</td>
                        <td class="text-right">{{formatoNumero(item.deuda,'M')}}</td>
                        <td class="text-center">{{item.primerages}}</td>
                        <td class="text-center">{{item.ultimages}}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-2 font-bold" v-if="datos!=''">
                    <tr>
                        <td colspan="4" class="text-center">TOTAL ({{totalRegistro}})</td>
                        <td class="text-center">{{formatoNumero(total('clientes'),'C')}}</td>
                        <td class="text-right">{{formatoNumero(total('deuda'),'M')}}</td>
                        <td colspan="2" class="text-center"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                calls:[],
                loading : false,
                busqueda:{cartera:'',call:'',fecha:'',horaInicio:'',horaFin:''},
                mensajeFecha:'',
                mensajeCartera:'',
                viewTable:false,
                totalRegistro:0
            }
        },
        created(){
            this.listarCall();
        },
        methods:{
            listarCall(){
                axios.get("asignacionCall",this.busqueda).then(res=>{
                    if(res.data){
                        this.calls=res.data;
                    }
                });
            },
            generarReporte(){
                this.viewTable=false;
                this.loading=true;
                this.datos=[];
                this.mensajeCartera='';
                this.mensajeFecha='';
                if(this.busqueda.cartera!='' && this.busqueda.fecha!=''){
                    axios.post("primerayultimagestion",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.totalRegistro=this.datos.length;
                            this.loading=false;
                            this.viewTable=true;
                        }
                    })
                }else{
                    this.datos=[];
                    this.loading=false;
                    
                    if(!this.busqueda.cartera){
                        this.mensajeCartera="Selecciona una cartera";
                    }
                    if(!this.busqueda.fecha){
                        this.mensajeFecha="Selecciona una fecha";
                    }
                }
            },
            total(base) {
                return this.datos.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
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
