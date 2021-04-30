<template>
    <div class="">
        <div class="d-flex">
            <i class="fa fa-desktop pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Control de Llamadas por Gestor</p>
                <p class="mb-0">Historial de llamadas realizadas</p>
            </div>
    
        </div>
        <div class="py-3 row">
            <div class="col-md-4 form-group">
                <label for="exampleInputPassword1">Cartera</label>
                <select class="form-control" v-model="busqueda.cartera" @change="listaAgentes(busqueda.cartera)">
                    <option value="">SELECCIONAR</option>
                    <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="exampleInputPassword1">Gestor</label>
                <select class="form-control" v-model="busqueda.agente" @change="control()" :disabled="deshabilitar">
                    <option v-for="(item,index) in agentes" :key="index" :value="item.extension" >{{item.agente}}</option>
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
        <!-- panel datos -->
        <div class="d-flex justify-content-center py-3" v-if="loading">
            <div class="spinner-border text-blue" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="py-5 mt-2 text-center" v-else-if="llamadas==''">
            <i class="fa fa-list text-blue fa-2x"></i>
            <p class=""><b>No se encontraron datos!!</b><br>Por favor, seleccionar otro criterio de búsqueda</p>
        </div>
        <div class="row py-2" v-else>
            <div class="col-md-3 pb-4">
                <div class="card border">
                    <div class="card-header px-2 border-top-blue">
                        <p class="font-15 mb-0 text-blue font-bold">{{agente}}</p>
                        <p class="mb-0">Extensión:{{busqueda.agente}}</p>
                    </div>
                    <div class="card-body px-2 pt-4">
                        <div class="row">
                            <div class="col-md-6 text-center pb-4">
                                <div class="d-flex justify-content-center">
                                    <i class="far fa-clock fa-1x pr-1 pt-1 text-gray"></i>
                                    <h5>{{promedio_duracion}}</h5>
                                </div>
                                <p class="mb-0 font-12">Prom. Duración LLamadas</p>
                            </div>
                            <div class="col-md-6 text-center pb-4">
                                <div class="d-flex justify-content-center">
                                    <i class="fa fa-headphones-alt fa-1x pr-1 pt-1 text-gray"></i>
                                    <h5>{{intento_marcaciones}}</h5>
                                </div>
                                <p class="mb-0 font-12">Intento de<br> Marcaciones</p>
                            </div>
                            <div class="col-md-6 text-center pb-4">
                                <div class="d-flex justify-content-center">
                                    <i class="fa fa-phone fa-1x pr-1 pt-1 text-gray"></i>
                                    <h5>{{llamadas_conectadas}}</h5>
                                </div>
                                <p class="mb-0 font-12">Llamadas<br> Conectadas</p>
                            </div>
                            <div class="col-md-6 text-center pb-4">
                                <div class="d-flex justify-content-center">
                                    <i class="fa fa-phone-slash fa-1x pr-1 pt-1 text-gray"></i>
                                    <h5>{{llamadas_no_conectadas}}</h5>
                                </div>
                                <p class="mb-0 font-12">Llamadas No Conectadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                <paginate name="pagLista" :list="llamadas" :per="20" class="px-0">
                    <table class="table table-hover">
                        <thead class="text-center bg-blue text-white">
                            <tr>
                                <td class="align-middle">Fecha</td>
                                <td class="align-middle">Grupo de Timbrado</td>
                                <td class="align-middle">Destino</td>
                                <td class="align-middle">Duración</td>
                                <td class="align-middle">Estado</td>
                                <td class="align-middle">Grabación</td>
                            </tr>
                        </thead>
                        <tbody>
                                <tr class="text-center" v-if="llamadas=='' && loading==false">
                                    <td colspan="6">No se encontraron datos</td>
                                </tr>
                                <tr v-for="(item,index) in paginated('pagLista')" :key="index" v-else-if="loading==false">
                                    <td class="text-center">{{item.fecha}}</td>
                                    <td>{{item.grupo_timbrado}}</td>
                                    <td class="text-center">{{formatoVacio(item.destino)}}</td>
                                    <td class="text-center">{{(item.duracion).substr(0,8)}}</td>
                                    <td class="text-center">{{formatoVacio(item.estado)}}</td>
                                    <td class="text-center">
                                        <a href="" title="reproducir audio"><i class="fa fa-volume-down fa-lg pr-2"></i></a>
                                        <a href="" title="descargar"><i class="fa fa-download fa-lg"></i></a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </paginate>
                    <paginate-links for="pagLista" 
                        :async="true"                     
                        :limit="4"
                        :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"
                    ></paginate-links>

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
                paginate: ['pagLista'],
                llamadas:[],
                agentes:[],
                busqueda:{cartera:'',fecha:'',agente:''},
                loading:false,
                deshabilitar:true,
                agente:'',
                promedio_duracion:'00:00:00',
                intento_marcaciones:0,
                llamadas_conectadas:0,
                llamadas_no_conectadas:0,
            }
        },
        created(){
            this.diaActual();
            // this.control();
        },
        methods:{
            listaAgentes(cartera){
                this.agentes=[];
                this.deshabilitar=true;
                axios.get("agentesElastix/"+cartera).then(res=>{
                    if(res.data){
                        this.agentes=res.data;
                        if(this.agentes.length>0){
                            this.deshabilitar=false;
                        }
                    }
                })
            },
            control(){   
                var ac_duracion=0;
                this.intento_marcaciones=0;
                this.promedio_duracion='00:00:00';
                this.llamadas_conectadas=0;
                this.llamadas_no_conectadas=0;
                this.loading=true;
                if(this.busqueda.agente!="" && this.busqueda.fecha!=""){
                    axios.post("controlLLamadasGestor",this.busqueda).then(res=>{
                        if(res.data){
                            this.llamadas=res.data;
                            this.loading=false;
                            this.intento_marcaciones=this.llamadas.length;
                            for(var i=0;i<this.llamadas.length;i++){
                                if(this.llamadas[i].estado=='ANSWERED'){
                                    this.llamadas_conectadas+=1;
                                }else{
                                    this.llamadas_no_conectadas+=1;
                                }
                                ac_duracion+=parseInt(this.llamadas[i].duracion_segundos);
                                this.promedio_duracion=(ac_duracion/this.llamadas_conectadas).toFixed(0);
                            }
                            this.promedio_duracion=this.secondsToString(this.promedio_duracion);
                            for(var j=0;j<this.agentes.length;j++){
                                if(this.agentes[j].extension==this.busqueda.agente){
                                    this.agente=this.agentes[j].agente;
                                };
                            }
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
            secondsToString(seconds) {
                var hour = Math.floor(seconds / 3600);
                hour = (hour < 10)? '0' + hour : hour;
                var minute = Math.floor((seconds / 60) % 60);
                minute = (minute < 10)? '0' + minute : minute;
                var second = seconds % 60;
                second = (second < 10)? '0' + second : second;
                return hour + ':' + minute + ':' + second;
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
           vuePaginate
        }
    }
</script>
