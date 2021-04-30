<template>
    <div >
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active text-dark" id="nav-cartera-tab" data-toggle="tab" href="#nav-cartera" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-folder pr-1"></i>Cartera</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-cartera" role="tabpanel" aria-labelledby="nav-cartera-tab">
                <div class="row my-3">
                    <div class="col-md-4">
                        <div class="px-2">
                            <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                            <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera">
                                <option selected value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="comparativo" class="font-bold col-form-label text-dark text-righ">Comparativo</label>
                            <select name="comparativo" id="comparativo" class="form-control" v-model="busqueda.comparativo">
                                <option selected value="">Seleccionar</option>
                                <option class="option" value="afecha">A LA FECHA</option>
                                <option class="option" value="ucierre">ÚLTIMOS 3 CIERRES</option>
                            </select>
                        </div>
                        <div class="px-2 py-3">
                            <a href="" @click.prevent="generarReporteCartera()" class="btn btn-outline-blue btn-block waves-effect">Generar Reporte</a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div v-if="loading==true" class="d-flex justify-content-center pt-5">
                            <div class="pt-5 text-center">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-5" v-else-if="metas=='' || pagos==''">
                            <div class="pt-5 text-center">
                                <i class="fas fa-chart-bar fa-2x text-blue"></i>
                                <p>Genera un reporte usando los<br>filtros de la izquierda.</p>
                            </div>
                        </div>
                        <div v-else class="px-0 mx-0">
                            <div class="justify-content-center chart-container-pastel px-0 mx-0" >
                                <BarChart :chart-data="dataGraficaCartera" :options="confGraficaCartera" class="p-0"></BarChart>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-blue text-center text-white">
                                        <tr>
                                            <td>MES</td>
                                            <td>META</td>
                                            <td>RECUPERO</td>
                                            <td>ALCANCE</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in metas" :key="index" class="text-center">
                                            <th>{{item.mes_nombre}}</th>
                                            <td>{{formatoNumero(item.meta,'M')}}</td>
                                            <td>{{formatoNumero(item.recupero,'M')}}</td>
                                            <td>{{Math.round(( parseFloat(item.recupero)/parseFloat(item.meta))*100)+'%'}}</td>
                                        </tr>                   
                                    </tbody>
                                    <tfoot class="text-center font-bold bg-gray">
                                        <tr>
                                            <th>TOTAL</th>
                                            <th>{{formatoNumero(totales.meta,'M')}}</th>
                                            <th>{{formatoNumero(totales.recupero,'M')}}</th>
                                            <th>{{totales.alcance+'%'}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab panel -->
        </div>
    </div>
</template>

<script>
    import BarChart from '../Chart/BarChart.js';
    import Excel from '../../../../public/js/excel.js';
    export default {
        props:['carteras'],
        data() {
            return {
                metas: [],
                pagos: [],
                totales: {meta:0,recupero:0,alcance:0},
                loading : false,
                busqueda:{cartera:'',comparativo:''},
                dataGraficaCartera:[],
                confGraficaCartera:[],
            }
        },
        methods:{
            generarReporteCartera(){
                this.loading=true;
                this.totales = {meta:0,recupero:0,alcance:0};
                this.alcance=0;
                this.metas=[];
                this.pagos=[];
                this.carteraC=this.busqueda.cartera;
                if(this.busqueda.cartera!='' && this.busqueda.comparativo!=''){
                    axios.post("reportecomparativocartera",this.busqueda).then(res=>{
                        if(res.data){
                            var datos=res.data;
                            this.metas=datos.metas;
                            console.log(this.metas);
                            this.pagos=datos.pagos;
                            console.log(this.pagos);
                            this.loading=false;
                            this.metas.forEach(element=>{
                                let aux = this.pagos.find(el=>el.m===element.mes)
                                let recupero=aux? aux.recupero:0.00;
                                element.recupero=recupero;
                            });
                            console.log(this.metas);
                            this.metas.forEach(el=>{
                                this.totales.meta += parseFloat(el.meta);
                                this.totales.recupero  += parseFloat(el.recupero);
                                this.totales.alcance    = Math.round((this.totales.recupero/this.totales.meta)*100);
                            });
                            this.graficaReporteCartera();
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.loading=false;
                        this.datos=[];
                    }, 500);
                }
            },
            graficaReporteCartera(){
                let datos_meta = [];
                let datos_alcance = [];
                let datos_recupero = [];
                let datos_mes = [];

                this.metas.forEach(mes=>{
                    let alcance = Math.round(( parseFloat(mes.recupero)/parseFloat(mes.meta))*100);
                    datos_meta.push(mes.meta);
                    datos_alcance.push(alcance);
                    datos_recupero.push(parseFloat(mes.recupero));
                    datos_mes.push(mes.mes_nombre);
                });

                var MetaData = {
                    label: 'S/. Meta',
                    data: datos_meta,
                    backgroundColor: 'rgb(130, 190, 280)',
                    borderWidth: 0,
                    yAxisID: "y-axis"
                };
                
                var RecuperoData = {
                    label: 'S/. Recupero',
                    data: datos_recupero,
                    backgroundColor: 'rgb(239,153,120)',
                    borderWidth: 0,
                    yAxisID: "y-axis"
                };
                
                var AlcanceData = {
                    label: '% Alcance',
                    data: datos_alcance,
                    backgroundColor: 'rgb(170,215,180)',
                    borderWidth: 0,
                    yAxisID: "y-axis-alcance"
                };
                
                this.dataGraficaCartera = {
                    labels:  datos_mes,
                    datasets: [MetaData, RecuperoData, AlcanceData],
                };

                this.confGraficaCartera = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                        barPercentage: 1,
                        categoryPercentage: 0.5,
                        }],

                        yAxes: [
                        {id: "y-axis",ticks: {beginAtZero:true,
                            callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return 'S/.' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return 'S/.' + value;
                            }
                            }
                        
                        },
                        scaleLabel: {
                            display: true,
                            fontSize:14,
                            fontFamily: 'sans-serif',
                            labelString: 'META / RECUPERO (S/.)'
                        }
                        }, 
                        //{id: "y-axis-recupero",ticks: {beginAtZero:true}}, 
                        {id: "y-axis-alcance",position: 'right',ticks: {max: 220, min: 0, stepSize: 20,
                            callback: function(value, index, values) {
                                return value + '%';
                            }
                            },
                            scaleLabel: {
                            display: true,
                            fontSize:14,
                            fontFamily: 'sans-serif',
                            labelString: 'ALCANCE (%)'
                            }
                        },
                        
                        /*{ticks: {
                        beginAtZero:true
                        }}*/
                    ]
                    },

                    tooltips: {
                        callbacks: {
                        label: function(t,value) {
                            //return tooltipItem.yLabel;
                            if (t.datasetIndex === 0 || t.datasetIndex==1) {
                                //return 'S/.' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var num=t.yLabel;
                                return 'S/.' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }else{
                                return t.yLabel +'%';
                            }
                        },
                        title: () => null,
                        },
                        bodyFontSize:16,
                    }
                };
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
            BarChart,              
            Excel
        }    
    }
</script>
