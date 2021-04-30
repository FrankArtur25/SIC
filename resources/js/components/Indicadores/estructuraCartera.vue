<template>
    <div >
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active text-dark" id="nav-cartera-tab" data-toggle="tab" href="#nav-cartera" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-folder pr-1"></i>Cartera</a>
                <a class="nav-item nav-link text-dark" id="nav-gestion-tab" data-toggle="tab" href="#nav-gestion" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fa fa-headset pr-1"></i>Gestión</a>
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
                            <label for="ubic" class="font-bold col-form-label text-dark text-righ">Ubicabilidad</label>
                            <select name="ubic" id="ubic" class="form-control" v-model="busqueda.ubicabilidad">
                                <option selected value="">Seleccionar</option>
                                <option class="option" value="todos">TODOS</option>
                                <option class="option" value="cfrn">C-F-R-N</option>
                                <option class="option" value="contacto">CONTACTO</option>
                                <option class="option" value="nocontacto">NO CONTACTO</option>
                                <option class="option" value="nodisponible">NO DISPONIBLE</option>
                                <option class="option" value="inubicable">INUBICABLE</option>
                                <option class="option" value="singestion">SIN GESTIÓN</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="estructura" class="font-bold col-form-label text-dark text-righ">Estructura</label>
                            <select name="estructura" id="estructura" class="form-control" v-model="busqueda.estructura">
                                <option selected value="">Seleccionar</option>
                                <option class="option" value="tramo">TRAMO</option>
                                <option class="option" value="score">SCORE</option>
                                <option class="option" value="dep">DEPARTAMENTO</option>
                                <option class="option" value="entidades">ENTIDADES</option>
                                <option class="option" value="dep_ind">DEP. E IND.</option>
                                <option class="option" value="prioridad">PRIORIDAD</option>
                                <option class="option" value="saldo_deuda">RANGO DE DEUDA</option>
                                <option class="option" value="capital">RANGO CAPITAL</option>
                                <option class="option" value="monto_camp">RANGO IMPORTE CANC.</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="mes" class="font-bold col-form-label text-dark text-righ">Mes</label>
                            <input type="month" id="mes" name="mes" class="form-control" v-model="busqueda.mes">
                        </div>
                        <div class="px-2 py-3">
                            <a href="" @click.prevent="generarReporteCartera()" class="btn btn-outline-blue btn-block waves-effect">Generar Reporte</a>
                            <a href="" v-if="datos!=''" @click.prevent="descargarExcelCartera()" class="btn btn-outline-blue btn-block waves-effect">
                                <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                                <i class="fa fa-download pr-1"></i>
                                Descargar Reporte
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div v-if="loading==true" class="d-flex justify-content-center pt-5">
                            <div class="pt-5 text-center">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-5" v-else-if="datos==''">
                            <div class="pt-5 text-center">
                                <i class="fa fa-chart-pie fa-2x text-blue"></i>
                                <p>Genera un reporte usando los<br>filtros de la izquierda.</p>
                            </div>
                        </div>
                        <div v-else class="">
                            <div class="d-flex justify-content-center chart-container-pastel " >
                                <PieChart :chart-data="dataGraficaCartera" :options="confGraficaCartera" class="p-0"></PieChart>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-blue text-center text-white">
                                        <tr>
                                            <td>Estructura</td>
                                            <td>Clientes</td>
                                            <td>Capital</td>
                                            <td>Deuda</td>
                                            <td>IC</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in datos" :key="index" class="text-center">
                                            <td>{{item.estructura}}</td>
                                            <td><a href="" class="text-black" @click.prevent="descargarCodigosCartera(item.estructura)">{{formatoNumero(item.clientes,'C')}}</a></td>
                                            <td>{{formatoNumero(item.capital,'M')}}</td>
                                            <td>{{formatoNumero(item.deuda,'M')}}</td>
                                            <td>{{formatoNumero(item.importe,'M')}}</td>
                                        </tr>                   
                                    </tbody>
                                    <tfoot class="text-center font-bold bg-gray">
                                        <tr>
                                            <td>TOTAL</td>
                                            <td>{{formatoNumero(total('clientes'),'C')}}</td>
                                            <td>{{formatoNumero(total('capital'),'M')}}</td>
                                            <td>{{formatoNumero(total('deuda'),'M')}}</td>
                                            <td>{{formatoNumero(total('importe'),'M')}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab panel -->
            <div class="tab-pane fade" id="nav-gestion" role="tabpanel" aria-labelledby="nav-gestion-tab">
                <div class="row my-3">
                    <div class="col-md-4">
                        <div class="px-2">
                            <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                            <select name="cartera" id="cartera" class="form-control" v-model="busquedaGestion.cartera">
                                <option selected value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="ubic" class="font-bold col-form-label text-dark text-righ">Tipo de Gestion</label>
                            <select  class="form-control" v-model="busquedaGestion.tipo">
                                <option value="">Seleccionar</option>
                                <option class="option" value="gestion">GESTION</option>
                                <option class="option" value="pdps">PDP</option>
                                <option class="option" value="confirmacion">CONFIRMACION</option>
                                <option class="option" value="pagos">PAGOS</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="estructura" class="font-bold col-form-label text-dark text-righ">Estructura</label>
                            <select name="estructura" id="estructura" class="form-control" v-model="busquedaGestion.estructura">
                                <option selected value="">Seleccionar</option>
                                <option class="option" value="tramo">TRAMO</option>
                                <option class="option" value="score">SCORE</option>
                                <option class="option" value="dep">DEPARTAMENTO</option>
                                <option class="option" value="entidades">ENTIDADES</option>
                                <option class="option" value="dep_ind">DEP. E IND.</option>
                                <option class="option" value="prioridad">PRIORIDAD</option>
                                <option class="option" value="ubic">UBICABILIDAD</option>
                                <option class="option" value="rango_sueldo">RANGO SUELDO</option>
                                <option class="option" value="saldo_deuda">RANGO DEUDA</option>
                                <option class="option" value="capital">RANGO CAPITAL</option>
                                <option class="option" value="monto_camp">RANGO IMPORTE CANC.</option>
                                <option class="option" value="rango_pago" v-if="busquedaGestion.tipo=='pagos'">RANGO PAGO</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label class="font-bold col-form-label text-dark text-righ">Fecha Inicio</label>
                            <input type="date" class="form-control" v-model="busquedaGestion.fechaInicio">
                        </div>
                        <div class="px-2">
                            <label class="font-bold col-form-label text-dark text-righ">Fecha Fin</label>
                            <input type="date" class="form-control" v-model="busquedaGestion.fechaFin">
                        </div>
                        <div class="px-2 py-3">
                            <a href="" @click.prevent="generarReporteGestion()" class="btn btn-outline-blue btn-block waves-effect">Generar Reporte</a>
                            <a href="" v-if="datosGestion!=''" @click.prevent="descargarExcel()" class="btn btn-outline-blue btn-block waves-effect">
                                <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                                <i class="fa fa-download pr-1"></i>
                                Descargar Reporte
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div v-if="loadingG==true" class="d-flex justify-content-center pt-5">
                            <div class="pt-5 text-center">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-5" v-else-if="datosGestion==''">
                            <div class="pt-5 text-center">
                                <i class="fa fa-chart-pie fa-2x text-blue"></i>
                                <p>Genera un reporte usando los<br>filtros de la izquierda.</p>
                            </div>
                        </div>
                        <div v-else class="">
                            <div class="d-flex justify-content-center chart-container-pastel " >
                                <PieChartG :chart-data="dataGraficaGestion" :options="confGraficaGestion" class="p-0"></PieChartG>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-blue text-center text-white">
                                        <tr>
                                            <td class="align-middle">Estructura</td>
                                            <td class="align-middle">Clientes</td>
                                            <td class="align-middle">Capital</td>
                                            <td class="align-middle">Deuda</td>
                                            <td class="align-middle">IC</td>
                                            <td class="align-middle" v-if="titulo_1!=''">{{titulo_1}}</td>
                                            <td class="align-middle" v-if="titulo_2!=''">{{titulo_2}}</td>
                                            <td class="align-middle" v-if="titulo_3!=''">{{titulo_3}}</td>
                                            <td class="align-middle" v-if="viewEstrPago">Clientes<br>C/ Pago</td>
                                            <td class="align-middle" v-if="viewEstrPago">Capital</td>
                                            <td class="align-middle" v-if="viewEstrPago">IC</td>
                                            <td class="align-middle" v-if="viewEstrPago">Monto Pago</td>
                                            <td class="align-middle" v-if="viewEstrPago">%<br>Clientes</td>
                                            <td class="align-middle" v-if="viewEstrPago">%<br>Recupero</td>
                                            <td class="align-middle" v-if="viewEstrPago">Ticket Pago</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in datosGestion" :key="index" class="text-center">
                                            <td>{{item.estructura}}</td>
                                            <td><a href="" class="text-black" @click.prevent="descargarCodigosGestion(item.estructura)">{{formatoNumero(item.clientes,'C')}}</a></td>
                                            <td>{{formatoNumero(item.capital,'M')}}</td>
                                            <td>{{formatoNumero(item.deuda,'M')}}</td>
                                            <td>{{formatoNumero(item.importe,'M')}}</td>
                                            <td v-if="titulo_1!=''">{{formatoNumero(item.cantidad,'C')}}</td>
                                            <td v-if="titulo_1!=''">{{formatoNumero(item.total,'M')}}</td>
                                            <td v-if="titulo_3!=''">{{formatoNumero(item.promedio,'M')}}</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{formatoNumero(item.clientes_pagos,'C')}}</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{formatoNumero(item.capital_pagos,'M')}}</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{formatoNumero(item.importe_pagos,'M')}}</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{formatoNumero(item.monto_pagos,'M')}}</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{item.cobertura?formatoNumero(item.cobertura,'M'):0}}%</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{item.recupero?formatoNumero(item.recupero,'M'):0}}%</td>
                                            <td v-if="viewEstrPago" class="bg-green-light-2">{{formatoNumero(item.promedio,'M')}}</td>
                                        </tr>                   
                                    </tbody>
                                    <tfoot class="text-center font-bold bg-gray">
                                        <tr>
                                            <td>TOTAL</td>
                                            <td>{{formatoNumero(totalG('clientes'),'C')}}</td>
                                            <td>{{formatoNumero(totalG('capital'),'M')}}</td>
                                            <td>{{formatoNumero(totalG('deuda'),'M')}}</td>
                                            <td>{{formatoNumero(totalG('importe'),'M')}}</td>
                                            <td v-if="titulo_1!=''">{{formatoNumero(totalG('cantidad'),'C')}}</td>
                                            <td v-if="titulo_1!=''">{{titulo_1=='Cant. Gestiones'?formatoNumero(totalG('cantidad')/totalG('clientes'),'M'):formatoNumero(totalG('total'),'M')}}</td>
                                            <td v-if="titulo_3!=''">{{formatoNumero((totalG('total')/totalG('cantidad')),'M')}}</td>
                                            <td v-if="viewEstrPago">{{formatoNumero(totalG('clientes_pagos'),'C')}}</td>
                                            <td v-if="viewEstrPago">{{formatoNumero(totalG('capital_pagos'),'M')}}</td>
                                            <td v-if="viewEstrPago">{{formatoNumero(totalG('importe_pagos'),'M')}}</td>
                                            <td v-if="viewEstrPago">{{formatoNumero(totalG('monto_pagos'),'M')}}</td>
                                            <td v-if="viewEstrPago">{{totalG('clientes')?formatoNumero((totalG('clientes_pagos')/totalG('clientes'))*100,'M'):0}}%</td>
                                            <td v-if="viewEstrPago">{{totalG('importe')?formatoNumero((totalG('monto_pagos')/totalG('importe'))*100,'M'):0}}%</td>
                                            <td v-if="viewEstrPago">{{formatoNumero(totalG('monto_pagos')/totalG('clientes_pagos'),'M')}}</td>
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
    import PieChart from '../Chart/PieChart.js';
    import PieChartG from '../Chart/PieChart.js';
    import Excel from '../../../../public/js/excel.js';
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                datosGestion: [],
                loading : false,
                loadingG : false,
                busqueda:{cartera:'',ubicabilidad:'',estructura:'',mes:''},
                busquedaGestion:{cartera:'',tipo:'',estructura:'',fechaInicio:'',fechaFin:''},
                dataGraficaCartera:[],
                confGraficaCartera:[],
                dataGraficaGestion:[],
                confGraficaGestion:[],
                viewEstrPago:false,
                titulo_1:'',
                titulo_2:'',
                titulo_3:'',
                tipo:'',
                carteraC:'',
                carteraG:'',
                datosDescargabusqueda:{cartera:'',ubicabilidad:'',estructura:'',mes:''},
                datosDescargabusquedaGestion:{cartera:'',tipo:'',estructura:'',fechaInicio:'',fechaFin:''},
            }
        },
        methods:{
            generarReporteCartera(){
                this.loading=true;
                this.datos=[];
                this.carteraC=this.busqueda.cartera;
                if(this.busqueda.cartera!='' && this.busqueda.ubicabilidad!='' && this.busqueda.estructura!='' && this.busqueda.mes!=''){
                    axios.post("reporteEstructuraCartera",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.loading=false;
                            this.graficaReporteCartera();
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.loading=false;
                        this.datos=[];
                    }, 500);
                }
                this.datosDescargabusqueda.cartera=this.busqueda.cartera;
                this.datosDescargabusqueda.ubicabilidad=this.busqueda.ubicabilidad;
                this.datosDescargabusqueda.estructura=this.busqueda.estructura;
                this.datosDescargabusqueda.mes=this.busqueda.mes;
            },
            graficaReporteCartera(){
                var arrayDatos=[];
                var arrayLabels=[];
                var ultDatos=0;
                for(var i=0;i<this.datos.length;i++){
                    if(i<=6){
                        arrayDatos.push(this.datos[i].clientes);
                        arrayLabels.push(this.datos[i].estructura+" - "+this.formatoNumero(this.datos[i].clientes,'C'));
                    }else{
                        ultDatos+=parseInt(this.datos[i].clientes);
                    }
                }
                if(ultDatos!=0){
                    arrayDatos.push(ultDatos);
                    arrayLabels.push("OTROS - "+this.formatoNumero(ultDatos,'C'));
                }

                this.dataGraficaCartera = {
                    labels: arrayLabels,
                    datasets: [{
                                label: 'mm',
                                data: arrayDatos,
                                backgroundColor: ['#41afa5','rgb(144, 196, 248)','rgb(119, 194, 234)','rgb(224,153,183)','rgb(239,153,120)','rgb(254,246,163)','rgb(226,230,154)','rgb(170,215,210)','rgb(229,229,229)'],
                                borderColor: ['#41afa5','#ffff'],
                                borderWidth: 8
                            }]                
                };

                this.confGraficaCartera={
                    title: {
                        display: true,
                        text: 'RESULTADOS - REPORTE ESTRUCTURA'
                    },
                    // showDatapoints: true,
                    responsive:true,
                    legend: {
                        position: 'right',
                    },
                    maintainAspectRatio: false ,
                    animation: {
                    duration: 0,
                    onComplete: function () {
                      var self = this,
                          chartInstance = this.chart,
                          ctx = chartInstance.ctx;
               
                      ctx.font = '12px Arial';
                      ctx.textAlign = "center";
                      ctx.fillStyle = "#17202A";
               
                      Chart.helpers.each(self.data.datasets.forEach(function (dataset, datasetIndex) {
                          var meta = self.getDatasetMeta(datasetIndex),
                              total = 0, //total values to compute fraction
                              labelxy = [],
                              offset = Math.PI / 2, //start sector from top
                              radius,
                              centerx,
                              centery, 
                              lastend = 0; //prev arc's end line: starting with 0
               
                          for (var val of dataset.data) { total += val; } 
               
                          Chart.helpers.each(meta.data.forEach( function (element, index) {
                              radius = 0.9 * element._model.outerRadius - element._model.innerRadius;
                              centerx = element._model.x;
                              centery = element._model.y;
                              var thispart = dataset.data[index],
                                  arcsector = Math.PI * (2 * thispart / total);
                              if (element.hasValue() && dataset.data[index] > 0) {
                                labelxy.push(lastend + arcsector / 2 + Math.PI + offset);
                              }
                              else {
                                labelxy.push(-1);
                              }
                              lastend += arcsector;
                          }), self)
               
                          var lradius = radius * 3 / 4;
                          for (var idx in labelxy) {
                            if (labelxy[idx] === -1) continue;
                            if(dataset.data[idx] >= 1){//para que muestre en la torta los mayores a 1%
                                var langle = labelxy[idx],
                                    dx = centerx + lradius * Math.cos(langle),
                                    dy = centery + lradius * Math.sin(langle),
                                    val = Math.round(dataset.data[idx] / total * 100);
                                    console.log(val);
                                ctx.fillText(val + '%', dx, dy);
                            }
                          }
               
                      }), self);
                    }
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
            generarReporteGestion(){
                this.loadingG=true;
                this.datosGestion=[];
                this.titulo_1="";
                this.titulo_2="";
                this.titulo_3="";
                this.tipo=this.busquedaGestion.tipo;
                this.carteraG=this.busquedaGestion.cartera;
                if(this.busquedaGestion.tipo=='pdps'){
                    this.titulo_1="PDP";
                    this.titulo_2="Monto PDP";
                    this.titulo_3="Ticket PDP";
                }
                if(this.busquedaGestion.tipo=='confirmacion'){
                    this.titulo_1="Conf.";
                    this.titulo_2="Monto Conf.";
                    this.titulo_3="Ticket Conf.";
                }
                if(this.busquedaGestion.tipo=='gestion'){
                    this.titulo_1="Cant. Gestiones";
                    this.titulo_2="Intensidad";
                }
                if(this.busquedaGestion.tipo=='pagos'){
                    this.viewEstrPago=true;
                }else{
                    this.viewEstrPago=false;
                }
                if(this.busquedaGestion.cartera!='' && this.busquedaGestion.tipo!='' && this.busquedaGestion.estructura!='' && this.busquedaGestion.fechaInicio!='' && this.busquedaGestion.fechaFin!=''){
                    axios.post("reporteEstructuraGestionCartera",this.busquedaGestion).then(res=>{
                        if(res.data){
                            this.datosGestion=res.data;
                            this.loadingG=false;
                            this.graficaReporteGestion();
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.loadingG=false;
                        this.datosGestion=[];
                    }, 500);
                }
                this.datosDescargabusquedaGestion.cartera=this.busquedaGestion.cartera;
                this.datosDescargabusquedaGestion.tipo=this.busquedaGestion.tipo;
                this.datosDescargabusquedaGestion.estructura=this.busquedaGestion.estructura;
                this.datosDescargabusquedaGestion.fechaInicio=this.busquedaGestion.fechaInicio;
                this.datosDescargabusquedaGestion.fechaFin=this.busquedaGestion.fechaFin;
            },
            graficaReporteGestion(){
                var arrayDatos=[];
                var arrayLabels=[];
                var ultDatos=0;
                for(var i=0;i<this.datosGestion.length;i++){
                    if(i<=6){
                        arrayDatos.push(this.datosGestion[i].clientes);
                        arrayLabels.push(this.datosGestion[i].estructura+" - "+this.formatoNumero(this.datosGestion[i].clientes,'C'));
                    }else{
                        ultDatos+=parseInt(this.datosGestion[i].clientes);
                    }
                }
                if(ultDatos!=0){
                    arrayDatos.push(ultDatos);
                    arrayLabels.push("OTROS - "+this.formatoNumero(ultDatos,'C'));
                }

                this.dataGraficaGestion = {
                    labels: arrayLabels,
                    datasets: [{
                                label: 'mm',
                                data: arrayDatos,
                                backgroundColor: ['#41afa5','rgb(144, 196, 248)','rgb(119, 194, 234)','rgb(224,153,183)','rgb(239,153,120)','rgb(254,246,163)','rgb(226,230,154)','rgb(170,215,210)','rgb(229,229,229)'],
                                borderColor: ['#41afa5','#ffff'],
                                borderWidth: 8
                            }]                
                };

                this.confGraficaGestion={
                    title: {
                        display: true,
                        text: 'RESULTADOS - REPORTE ESTRUCTURA'
                    },
                    // showDatapoints: true,
                    responsive:true,
                    legend: {
                        position: 'right',
                    },
                    maintainAspectRatio: false ,
                    animation: {
                    duration: 0,
                    onComplete: function () {
                      var self = this,
                          chartInstance = this.chart,
                          ctx = chartInstance.ctx;
               
                      ctx.font = '12px Arial';
                      ctx.textAlign = "center";
                      ctx.fillStyle = "#17202A";
               
                      Chart.helpers.each(self.data.datasets.forEach(function (dataset, datasetIndex) {
                          var meta = self.getDatasetMeta(datasetIndex),
                              total = 0, //total values to compute fraction
                              labelxy = [],
                              offset = Math.PI / 2, //start sector from top
                              radius,
                              centerx,
                              centery, 
                              lastend = 0; //prev arc's end line: starting with 0
               
                          for (var val of dataset.data) { total += val; } 
               
                          Chart.helpers.each(meta.data.forEach( function (element, index) {
                              radius = 0.9 * element._model.outerRadius - element._model.innerRadius;
                              centerx = element._model.x;
                              centery = element._model.y;
                              var thispart = dataset.data[index],
                                  arcsector = Math.PI * (2 * thispart / total);
                              if (element.hasValue() && dataset.data[index] > 0) {
                                labelxy.push(lastend + arcsector / 2 + Math.PI + offset);
                              }
                              else {
                                labelxy.push(-1);
                              }
                              lastend += arcsector;
                          }), self)
               
                          var lradius = radius * 3 / 4;
                          for (var idx in labelxy) {
                            if (labelxy[idx] === -1) continue;
                            if(dataset.data[idx] >= 1){//para que muestre en la torta los mayores a 1%
                                var langle = labelxy[idx],
                                    dx = centerx + lradius * Math.cos(langle),
                                    dy = centery + lradius * Math.sin(langle),
                                    val = Math.round(dataset.data[idx] / total * 100);
                                    console.log(val);
                                ctx.fillText(val + '%', dx, dy);
                            }
                          }
               
                      }), self);
                    }
               }
                }
            },
            totalG(base) {
                return this.datosGestion.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            descargarExcelCartera(){
                var data=[];
                var totalClientes=0;
                var totalCapital=0;
                var totalDeuda=0;
                var totalIc=0;
                var clientes=0;
                data.push(["Estructura","Clientes","Capital","Deuda","IC"]);
                for(var i=0;i<this.datos.length;i++){
                    // data.push(Object.values(this.datos[i]));
                    data.push([this.datos[i].estructura,
                            this.formatoNumero(parseInt(this.datos[i].clientes),'C'),
                            this.formatoNumero(parseFloat(this.datos[i].capital),'M'),
                            this.formatoNumero(parseFloat(this.datos[i].deuda),'M'),
                            this.formatoNumero(parseFloat(this.datos[i].importe),'M')]);
                    totalClientes+=parseInt(this.datos[i].clientes);
                    totalCapital+=parseFloat(this.datos[i].capital);
                    totalDeuda+=parseFloat(this.datos[i].deuda);
                    totalIc+=parseFloat(this.datos[i].importe);
                }
                data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M')]);
                let hoja="";
                this.carteras.forEach(element => {
                    if(this.carteraC==element.id){
                        hoja=element.cartera;
                    }
                });
                Excel.exportar(data,"Repote_estructura_cartera",hoja);
            },
            descargarExcel(){
                var data=[];
                var totalClientes=0;
                var totalCapital=0;
                var totalDeuda=0;
                var totalIc=0;
                var clientes=0;
                var totalCantidad=0;
                var totalTotal=0;
                var totalClientesPagos=0;
                var totalCapitalPagos=0;
                var totalICPagos=0;
                var totalMontoPagos=0;
                var totalMontoPagos=0;
                if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                    data.push(["Estructura","Clientes","Capital","Deuda","IC",this.titulo_1,this.titulo_2,this.titulo_3]);
                }else if(this.tipo=="pagos"){
                    data.push(["Estructura","Clientes","Capital","Deuda","IC","Clientes C/ Pago","Capital","IC","Monto Pago","% Clientes","% Recupero","Ticket Pago"]);
                }else{
                    data.push(["Estructura","Clientes","Capital","Deuda","IC",this.titulo_1,this.titulo_2]);
                }

                for(var i=0;i<this.datosGestion.length;i++){
                    // data.push(Object.values(this.datosGestion[i]));
                    if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                        data.push([this.datosGestion[i].estructura,
                                  this.formatoNumero(parseInt(this.datosGestion[i].clientes),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].capital),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].deuda),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].importe),'M'),
                                  this.formatoNumero(parseInt(this.datosGestion[i].cantidad),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].total),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].promedio),'M')
                                  ]);
                        totalCantidad+=parseInt(this.datosGestion[i].cantidad);
                        totalTotal+=parseFloat(this.datosGestion[i].total);
                       
                    }else if(this.tipo=="pagos"){
                        data.push([this.datosGestion[i].estructura,
                                  this.formatoNumero(parseInt(this.datosGestion[i].clientes),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].capital),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].deuda),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].importe),'M'),
                                  this.formatoNumero(parseInt(this.datosGestion[i].clientes_pagos),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].capital_pagos),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].importe_pagos),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].monto_pagos),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].cobertura),'M')+"%",
                                  this.formatoNumero(parseFloat(this.datosGestion[i].recupero),'M')+"%",
                                  this.formatoNumero(parseFloat(this.datosGestion[i].promedio),'M')
                                ]);
                        totalClientesPagos+=parseInt(this.datosGestion[i].clientes_pagos);
                        totalCapitalPagos+=parseFloat(this.datosGestion[i].capital_pagos);
                        totalICPagos+=parseFloat(this.datosGestion[i].importe_pagos);
                        totalMontoPagos+=parseFloat(this.datosGestion[i].monto_pagos);
                        
                    }else{
                        data.push([this.datosGestion[i].estructura,
                                  this.formatoNumero(parseInt(this.datosGestion[i].clientes),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].capital),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].deuda),'M'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].importe),'M'),
                                  this.formatoNumero(parseInt(this.datosGestion[i].cantidad),'C'),
                                  this.formatoNumero(parseFloat(this.datosGestion[i].total),'M')
                                  ]);                        
                        totalCantidad+=parseInt(this.datosGestion[i].cantidad);
                        totalTotal+=parseFloat(this.datosGestion[i].total);
                    }

                    totalClientes+=parseInt(this.datosGestion[i].clientes);
                    totalCapital+=parseFloat(this.datosGestion[i].capital);
                    totalDeuda+=parseFloat(this.datosGestion[i].deuda);
                    totalIc+=parseFloat(this.datosGestion[i].importe);
                }

                if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                    data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M'),this.formatoNumero(totalCantidad,'C'),this.formatoNumero(totalTotal,'M'),this.formatoNumero((totalTotal/totalCantidad),'M')]);
                }else if(this.tipo=="pagos"){
                    data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M'),this.formatoNumero(totalClientesPagos,'C'),this.formatoNumero(totalCapitalPagos,'M'),this.formatoNumero(totalICPagos,'M'),this.formatoNumero(totalMontoPagos,'M'),this.formatoNumero((totalClientesPagos/totalClientes)*100,'M')+"%",this.formatoNumero((totalMontoPagos/totalIc)*100,'M')+"%",this.formatoNumero((totalMontoPagos/totalClientesPagos),'M')]);
                }else{
                    data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M'),this.formatoNumero(totalCantidad,'C'),this.formatoNumero(totalCantidad/totalClientes,'M')]);
                }
                let hoja="";
                this.carteras.forEach(element => {
                    if(this.carteraG==element.id){
                        hoja=element.cartera;
                    }
                });
                Excel.exportar(data,"Repote_estructura_cartera_gestion",hoja);
            },
            descargarCodigosCartera(valor){
                window.location.href="descargarEstructuraCarteraCartera/"+this.datosDescargabusqueda.cartera+"/"+this.datosDescargabusqueda.ubicabilidad+"/"+this.datosDescargabusqueda.estructura+"/"+valor+"/"+this.datosDescargabusqueda.mes;
            },
            descargarCodigosGestion(valor){
                window.location.href="descargarEstructuraCarteraGestion/"+this.datosDescargabusquedaGestion.cartera+"/"+this.datosDescargabusquedaGestion.tipo+"/"+this.datosDescargabusquedaGestion.estructura+"/"+valor+"/"+this.datosDescargabusquedaGestion.fechaInicio+"/"+this.datosDescargabusquedaGestion.fechaFin;
            }
        },
        components: {
            PieChart,            
            PieChartG,      
            Excel
        }    
    }
</script>
