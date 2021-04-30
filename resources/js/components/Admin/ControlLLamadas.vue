<template>
    <div class="">
        <div class="d-flex">
            <i class="fa fa-desktop pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Panel de Control de Llamadas</p>
                <p class="mb-0">Monitoreo en tiempo real</p>
            </div>
    
        </div>
        <div class="py-3 row">
            <div class="col-md-4 form-group">
                <label for="exampleInputPassword1">Cartera</label>
                <select class="form-control" v-model="busqueda.cartera" @change="control()">
                    <option value="">SELECCIONAR</option>
                    <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <label >Fecha</label>
                <input type="date" class="form-control" v-model="busqueda.fecha" @change="control()">
            </div>
            <div class="col-md-2"></div>
            <!-- <div class="col-md-4 alert alert-green">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <i class="fa fa-headphones-alt pr-2 fa-2x pt-2"></i>
                        <p class="mb-0"><b>1001 - Daniel Mendoza Uribe</b><br>00:10:00</p>
                    </div>
                    <a href=""><i class="fa fa-times"></i></a>
                </div>
            </div> -->
        </div>
        <div class="pb-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="text-center bg-blue text-white">
                        <tr>
                            <td class="align-middle">Extensión</td>
                            <td class="align-middle">Agente</td>
                            <td class="align-middle">Tiempo de<br>Logeo</td>
                            <td class="align-middle">Intento<br>Marcaciones</td>
                            <td class="align-middle">Llamadas<br>Conectadas</td>
                            <td class="align-middle">Llamadas<br>No Conectadas</td>
                            <td class="align-middle">Prom. Duración<br>de Llamadas</td>
                            <td class="align-middle">Intervalo de<br>Duración Llam.</td>
                            <td class="align-middle">Número en<br>LLamada</td>
                            <td class="align-middle">Duración de<br>Llamada</td>
                            <td class="align-middle">Escuchar<br>Audio</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center" v-if="llamadas=='' && loading==false">
                            <td colspan="12">No se encontraron datos</td>
                        </tr>
                        <tr v-for="(item,index) in llamadas" :key="index" v-else-if="loading==false">
                            <td class="text-center">{{item.extension}}</td>
                            <td>{{item.agente}}</td>
                            <td class="text-center">{{formatoVacio(item.tiempo_logeo)}}</td>
                            <td class="text-center">{{formatoVacio(item.intento_llamadas)}}</td>
                            <td class="text-center">{{formatoVacio(item.llamadas_conectadas)}}</td>
                            <td class="text-center">{{formatoVacio(item.llamadas_noconectadas)}}</td>
                            <td class="text-center">{{formatoVacio(item.promedio_duracion)}}</td>
                            <td class="text-center">00:02:00</td>
                            <td>925624122</td>
                            <td class="text-center">00:00:25</td>
                            <td class="text-center"><a href=""><i class="fa fa-volume-down fa-lg"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center py-3" v-if="loading">
                <div class="spinner-border text-blue" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vuePaginate from '../../../../node_modules/vue-paginate';

    export default {
        props:["carteras"],
        data() {
            return {
                llamadas:[],
                busqueda:{cartera:'',fecha:''},
                loading:false
            }
        },
        created(){
            this.diaActual();
            // this.control();
        },
        methods:{
            control(){   
                this.loading=true;
                if(this.busqueda.cartera!="" && this.busqueda.fecha!=""){
                    axios.post("controlLLamadas",this.busqueda).then(res=>{
                        if(res.data){
                            this.llamadas=res.data;
                            this.loading=false;
                        }
                    })
                } 
            },
             diaActual(){
                var n=new Date();
                var hoy=n.getFullYear()+"-"+this.addZero(n.getMonth()+1)+"-"+this.addZero(n.getDate());
                this.busqueda.fecha=hoy;
            },
            addZero(i) {
                if (i < 10) {
                    i = '0' + i;
                }
                return i;
            },
            formatoVacio(item){
                if(item==null || item=="" ){
                    return "-";
                }else{
                    return item;
                }
            }
        },
        components:{
           
        }
    }
</script>
