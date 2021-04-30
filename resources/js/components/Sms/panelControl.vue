<template>
    <div>
        <div class="text-center d-flex justify-content-center pt-5" v-if="loadingIn">
           <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
        <div v-else class="fadeIn form-crear" >
            <div v-if="lista==''" class="text-center pt-5"> 
                <i class="far fa-list-alt fa-3x"></i><br>
                <p style="font-size:12px;">No existen campañas cargadas</p>
            </div>
           <div class="row" v-else>
               <div class="col-md-7">
                   <div class="row">
                        <div class="col-md-6">
                            <div class="card-inf border p-3 d-flex justify-content-between">
                                <div class="pt-2">
                                    <p class="text-num" style="font-size:27px;">{{formatoMil(cargados)}}</p>
                                    <p class="font-12">SMS Cargados</p>
                                </div>
                                <i class="fa fa-envelope fa-3x text-gray"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-inf border p-3 d-flex justify-content-between pb-0">
                                <div class="pt-2">
                                    <p class="text-num text-success" style="font-size:27px;">{{formatoMil(enviados)}}</p>
                                    <p class="font-12">SMS Enviados</p>
                                </div>
                                <i class="fa fa-sms fa-3x text-gray"></i>
                            </div>
                        </div>
                    </div>
                   <div class="card-inf p-3">
                       <div v-if="lista==''" class="text-center py-3">
                            <i class="fas fa-chart-pie fa-3x text-success"></i><br><br>
                            <b> No se encontraron datos</b>
                       </div>
                       <div class="row pb-3" v-else>
                            <div class="col-md-6">
                                <PieChart :chart-data="dataClaro" :options="options1" :height="screen1"></PieChart>
                                <br><br>
                            </div>
                            <div class="col-md-6">
                                <!-- <canvas id="pieOperadoras" width="260px" height="200px"></canvas> -->
                                <PieChart :chart-data="dataOperadoras" :options="options2" :height="screen2"></PieChart>
                                <br><br>
                            </div>
                       </div>
                   </div>
               </div>
               <div class="col-md-5">
                   <div class="card-inf border h-100" style="min-height: 100%;">
                        <div class="overflow-auto">
                            <div class="bg-blue-3 text-white py-2 text-center rounded">
                                <p class="mb-0">Lista de Campañas ({{total}})</p>
                            </div><br>
                            <div class="table-responsive px-3">
                                <paginate name="pagLista" :list="lista" :per="5" class="px-0">
                                    <table class="border-0 w-100" id="table-2">
                                        <thead>
                                            <tr class="text-center">
                                                <td width="50%"></td>
                                                <td class="text-center"><img :src="'img/claro.png'" class="img-fluid" width="50px" height="45px"></td>
                                                <td class="text-center"><img :src="'img/sim.png'" class="img-fluid" width="40px" height="35px"></td>
                                            </tr>
                                        </thead>
                                        <tbody v-if="loading==false && lista==''" class="border-0">
                                            <tr  class="text-center"> 
                                                No existen campañas cargadas
                                            </tr>
                                        </tbody>
                                        <tbody v-else v-for="(item, index) in paginated('pagLista')" :key="index"  class="item " :class="{'bg-green':item.estado=='Enviando','rounded':item.estado=='Enviando'}">
                                            <tr>
                                                <td>
                                                    <a style="line-height:18px;column-gap: 3em;columns: 3;" class="d-flex justify-content-between text-left text-dark" data-toggle="collapse" :href="'#c'+index" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        <div class="">
                                                            <b> {{item.cartera}}</b><br>
                                                            <p class="font-12">{{item.nombre}}</p>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <div v-if="item.estado_claro=='Enviando'" class="spinner-border spinner-border-sm text-success" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <p v-if="item.estado_claro!='Enviando'" class="badge px-3 py-2 " :class="item.color_claro">{{item.estado_claro}}</p>
                                                        <p v-else class="text-center pt-1 ml-1" >{{item.avance_claro}}</p>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <div v-if="item.estado=='Enviando'" class="spinner-border spinner-border-sm text-success" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <p v-if="item.estado!='Enviando'" class="badge px-3 py-2 " :class="item.color">{{item.estado}}</p>
                                                        <p v-else class="text-center pt-1 ml-1" >{{item.avance}}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="collapse" :id="'c'+index" >
                                                        <div class="card card-body" >
                                                            <div class="">
                                                            <p class="mb-2"><b>Cantidad de Clientes:</b> {{formatoMil(item.cant_cli)}}</p>
                                                            <p class="mb-2"><b>Cantidad de SMS:</b> {{formatoMil(item.cant_sms)}}</p>
                                                            <!-- <p class="mb-2 text-danger" v-if="item.estado!='Enviado'"><b>Hora Programada de Envío:</b> {{hora(index)}}</p> -->
                                                            </div>
                                                        </div>
                                                    </div>
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
                                class="px-3"
                            ></paginate-links>
                        </div>
                   </div>
               </div>
            </div>           
        </div>
    </div>
</template>

<script>
    import PieChart from '../Chart/PieChart.js';
    // import conf from './Chart/confBarHorizontal.js';
    export default {
        props:['id'],
        data() {
            return {
                loadingIn : true,
                loading:false,
                color:'#41afa5',
                dataClaro:[],
                dataOperadoras:[],
                options1:[],
                options2:[],
                screen1:0,
                screen2:0,
                lista: [],
                total:0,
                cargados:0,
                enviados:0,
                dataEnviados:{enviados:0,cargados:0},
                dataEnviadosClaro:{enviados:0,cargados:0},
                paginate: ['pagLista'],
                nomCampana:''
            }
        },
        created(){
            axios.get("listCampanasDia").then(res=>{
                if(res.data){
                    this.lista=res.data;
                    this.total=this.lista.length;
                    for(var i=0;i<this.total;i++){
                        this.cargados+=parseInt(this.lista[i].cant_sms_opr)+parseInt(this.lista[i].cant_sms_claro);
                        this.enviados+=parseInt(this.lista[i].enviados)+parseInt(this.lista[i].enviados_claro);
                        this.dataEnviadosClaro.enviados+=parseInt(this.lista[i].enviados_claro);
                        this.dataEnviadosClaro.cargados+=parseInt(this.lista[i].cant_sms_claro);
                        this.dataEnviados.enviados+=parseInt(this.lista[i].enviados);
                        this.dataEnviados.cargados+=parseInt(this.lista[i].cant_sms_opr);
                    }

                    this.graficasCampanaClaro();
                    this.graficasCampanaOperadoras();
                    this.loadingIn=false;
                    
                }
            })
            
        },
        methods:{
            graficasCampanaOperadoras(){
                if(screen.width<=768){
                    this.screen2=350;
                }else{
                    this.screen2=320;
                }
                var sms_enviados=this.dataEnviados.cargados-this.dataEnviados.enviados;
                var arrayLabels=[this.formatoMil(this.dataEnviados.cargados)+' SMS Cargados',this.formatoMil(this.dataEnviados.enviados)+' SMS Enviados'];
                var arrayData=[];
                var backgrounds=[];

                if(this.dataEnviados.cargados!=0 ){
                    arrayData=[sms_enviados,this.dataEnviados.enviados];
                    backgrounds=['#f0edee','#41afa5'];
                }else{
                    arrayData=[1];
                    backgrounds=['#f0edee'];
                }

                // var canvas = document.getElementById("pieOperadoras");
                // var ctx = canvas.getContext('2d');

                this.dataOperadoras = {
                    labels: arrayLabels,
                    datasets: [{
                                label: 'mm',
                                data: arrayData,
                                backgroundColor: backgrounds,
                                borderColor: ['rgb(255, 255, 255)','#41afa5'],
                                borderWidth: 6,
                                lineTension: 0,
                                fill:false
                            }]
                    
                };


                this.options2={
                    title: {
                        display: true,
                        text: 'CAMPAÑAS - OPERADORAS'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }

                // var pieChart = new Chart(ctx, {
                //     type: 'pie',
                //     data: this.dataOperadoras,
                //     options: this.options2
                // });

            },
            graficasCampanaClaro(){
                if(screen.width<=768){
                    this.screen1=350;
                }else{
                    this.screen1=320;
                }
                var sms_enviados=this.dataEnviadosClaro.cargados-this.dataEnviadosClaro.enviados;
                var arrayLabels=[this.formatoMil(this.dataEnviadosClaro.cargados)+' SMS Cargados',this.formatoMil(this.dataEnviadosClaro.enviados)+' SMS Enviados'];
                var arrayData=[];
                var backgrounds=[];

                if(this.dataEnviadosClaro.cargados!=0 ){
                    arrayData=[sms_enviados,this.dataEnviadosClaro.enviados];
                    backgrounds=['#f0edee','#41afa5'];
                }else{
                    arrayData=[1];
                    backgrounds=['#f0edee'];
                }

                this.dataClaro = {
                    labels: arrayLabels,
                    datasets: [{
                                label: 'mm',
                                data: arrayData,
                                backgroundColor: backgrounds,
                                borderColor: ['rgb(255, 255, 255)','#41afa5'],
                                borderWidth: 6,
                                lineTension: 0,
                                fill:false
                            }]
                    
                };


                this.options1={
                    title: {
                        display: true,
                        text: 'CAMPAÑAS - CLARO'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            },
            crear(){
                 window.location.href = "crearCampana";
            },
            formatoMil(nStr){
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
            PieChart
        }
   
    }
</script>
