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
                            <select name="cartera" id="cartera" class="form-control" v-model="busquedaCartera.cartera"  @change="listarGestoresCartera(busquedaCartera.cartera)">
                                <option selected value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="indicador" class="font-bold col-form-label text-dark text-righ">Gestor</label>
                            <select name="i_gestor" id="i_gestor" class="form-control" v-model="busquedaCartera.gestor" :disabled="gestoresCartera==''">
                                <option value="">Seleccionar</option>
                                <option v-for="(item,index) in gestoresCartera" :key="index"  class="option" :value="item.codigo">{{item.gestor}}</option>                   
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="ubic" class="font-bold col-form-label text-dark text-righ">Ubicabilidad</label>
                            <select name="ubic" id="ubic" class="form-control" v-model="busquedaCartera.ubicabilidad">
                                <option value="">Seleccionar</option>
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
                            <select name="estructura" id="estructura" class="form-control" v-model="busquedaCartera.estructura">
                                <option value="">Seleccionar</option>
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
                            <input type="month" id="mes" name="mes" class="form-control" v-model="busquedaCartera.mes">
                        </div>
                        <div class="px-2 py-3">
                            <a href="" @click.prevent="generarReporteCartera()" class="btn btn-outline-blue btn-block waves-effect">Generar Reporte</a>
                            <a href="" v-if="datosCatera!=''" @click.prevent="descargarExcelCartera()" class="btn btn-outline-blue btn-block waves-effect">
                                <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                                <i class="fa fa-download pr-1"></i>
                                Descargar Reporte
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div v-if="loading2==true" class="d-flex justify-content-center pt-5">
                            <div class="pt-5 text-center">
                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-5" v-else-if="datosCatera==''">
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
                                        <tr v-for="(item,index) in datosCatera" :key="index" class="text-center">
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
                                            <td>{{formatoNumero(totalC('clientes'),'C')}}</td>
                                            <td>{{formatoNumero(totalC('capital'),'M')}}</td>
                                            <td>{{formatoNumero(totalC('deuda'),'M')}}</td>
                                            <td>{{formatoNumero(totalC('importe'),'M')}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- endTab -->
            <div class="tab-pane fade" id="nav-gestion" role="tabpanel" aria-labelledby="nav-gestion-tab">
                <div class="row my-3">
                    <div class="col-md-4">
                        <div class="px-2">
                            <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                            <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera" @change="listarGestores(busqueda.cartera)">
                                <option selected value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="indicador" class="font-bold col-form-label text-dark text-righ">Gestor</label>
                            <select name="i_gestor" id="i_gestor" class="form-control" v-model="busqueda.gestor" :disabled="gestores==''">
                                <option selected value="">Seleccionar</option>
                                <option v-for="(item,index) in gestores" :key="index"  class="option" :value="item.firma">{{item.gestor}}</option>                   
                            </select>
                        </div>
                        <div class="px-2">
                            <label class="font-bold col-form-label text-dark text-righ">Tipo de Análisis</label>
                            <select name="analisis" id="analisis" class="form-control" v-model="busqueda.tipoAnalisis">
                                <option selected value="">Seleccionar</option>
                                <option class="option" value="gestion">GESTIÓN</option>
                                <option class="option" value="pdps">PDPS</option>
                                <option class="option" value="confirmacion">CONFIRMACIÓN</option>
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
                                <option class="option" value="rango_sueldo">RANGO SUELDO</option>
                                <option class="option" value="saldo_deuda">RANGO DE DEUDA</option>
                                <option class="option" value="capital">RANGO CAPITAL</option>
                                <option class="option" value="monto_camp">RANGO IMPORTE CANC.</option>
                            </select>
                        </div>
                        <div class="px-2">
                            <label for="mes" class="font-bold col-form-label text-dark text-righ">Fecha Inicio</label>
                            <input type="date" id="mes" name="mes" class="form-control" v-model="busqueda.fechaInicio">
                        </div>
                        <div class="px-2">
                            <label for="mes" class="font-bold col-form-label text-dark text-righ">Fecha Fin</label>
                            <input type="date" id="mes" name="mes" class="form-control" v-model="busqueda.fechaFin">
                        </div>
                        <div class="px-2 py-3">
                            <a href="" @click.prevent="generarReporte()" class="btn btn-outline-blue btn-block waves-effect">Generar Reporte</a>
                            <a href="" v-if="datos!=''" @click.prevent="descargarExcel()" class="btn btn-outline-blue btn-block waves-effect">
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
                                <PieChart :chart-data="dataGrafica" :options="confGrafica" class="p-0"></PieChart>
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
                                            <td>{{titulo_1}}</td>
                                            <td>{{titulo_2}}</td>
                                            <td v-if="titulo_3!=''">{{titulo_3}}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in datos" :key="index" class="text-center">
                                            <td>{{item.estructura}}</td>
                                            <td><a href="" class="text-black" @click.prevent="descargarCodigosGestion(item.estructura)">{{formatoNumero(item.clientes,'C')}}</a></td>
                                            <td>{{formatoNumero(item.capital,'M')}}</td>
                                            <td>{{formatoNumero(item.deuda,'M')}}</td>
                                            <td>{{formatoNumero(item.importe,'M')}}</td>
                                            <td>{{formatoNumero(item.cantidad,'C')}}</td>
                                            <td>{{formatoNumero(item.total,'M')}}</td>
                                            <td v-if="titulo_3">{{formatoNumero(item.promedio,'M')}}</td>
                                        </tr>                   
                                    </tbody>
                                    <tfoot class="text-center font-bold bg-gray">
                                        <tr>
                                            <td>TOTAL</td>
                                            <td>{{formatoNumero(totall('clientes'),'C')}}</td>
                                            <td>{{formatoNumero(totall('capital'),'M')}}</td>
                                            <td>{{formatoNumero(totall('deuda'),'M')}}</td>
                                            <td>{{formatoNumero(totall('importe'),'M')}}</td>
                                            <td>{{formatoNumero(totall('cantidad'),'C')}}</td>
                                            <td v-if="titulo_1=='Cant. Gestiones'">{{formatoNumero(totall('cantidad')/totall('clientes'),'M')}}</td>
                                            <td v-else>{{formatoNumero(totall('total'),'M')}}</td>
                                            <td v-if="titulo_3!=''">{{formatoNumero(totall('total')/totall('cantidad'),'M')}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PieChart from '../Chart/PieChart.js';
    import Excel from '../../../../public/js/excel.js';
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                datosCatera: [],
                gestores: [],
                gestoresCartera: [],
                loading : false,
                loading2 : false,
                busqueda:{cartera:'',gestor:'',tipoAnalisis:'',estructura:'',fechaInicio:'',fechaFin:''},
                datosDescargabusqueda:{cartera:'',gestor:'',tipoAnalisis:'',estructura:'',fechaInicio:'',fechaFin:''},
                busquedaCartera:{cartera:'',gestor:'',ubicabilidad:'',estructura:'',mes:''},
                datosDescargabusquedaCartera:{cartera:'',gestor:'',ubicabilidad:'',estructura:'',mes:''},
                dataGrafica:[],
                confGrafica:[],
                dataGraficaCartera:[],
                confGraficaCartera:[],
                titulo_1:'',
                titulo_2:'',
                tipo:'',
                carteraC:'',
                carteraG:''
            }
        },
        methods:{
            listarGestores(cartera){
                this.gestores=[];
                if(cartera!=''){
                    axios.get("listaGestores/"+cartera).then(res=>{
                        if(res.data){
                            this.gestores=res.data;
                        }
                    });
                }
            },
            limpiar(){
                this.busqueda.cartera='';
                this.busqueda.gestor='';
                this.busqueda.tipoAnalisis='';
                this.busqueda.estructura='';
                this.busqueda.fechaInicio='';
                this.busqueda.fechaFin='';
            },
            generarReporte(){
                this.loading=true;
                this.datos=[];
                this.carteraG=this.busqueda.cartera;
                this.tipo=this.busqueda.tipoAnalisis;
                this.titulo_3="";
                if(this.busqueda.tipoAnalisis=='pdps'){
                    this.titulo_1="Cant. PDP";
                    this.titulo_2="Monto PDP";
                    this.titulo_3="Ticket PDP";
                }
                if(this.busqueda.tipoAnalisis=='confirmacion'){
                    this.titulo_1="Cant. Conf.";
                    this.titulo_2="Monto Conf.";
                    this.titulo_3="Ticket Conf.";
                }
                if(this.busqueda.tipoAnalisis=='gestion'){
                    this.titulo_1="Cant. Gestiones";
                    this.titulo_2="Intensidad";
                }
                if(this.busqueda.cartera!='' && this.busqueda.gestor!='' && this.busqueda.estructura!='' 
                    && this.busqueda.tipoAnalisis!='' && this.busqueda.fechaInicio!='' && this.busqueda.fechaFin!=''){
                    axios.post("reporteEstructuraGestor",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.loading=false;
                            this.graficaReporte();
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.loading=false;
                        this.datos=[];
                    }, 500);
                }
                this.datosDescargabusqueda.cartera=this.busqueda.cartera;
                this.datosDescargabusqueda.tipo=this.busqueda.tipo;
                this.datosDescargabusqueda.estructura=this.busqueda.estructura;
                this.datosDescargabusqueda.gestor=this.busqueda.gestor;
                this.datosDescargabusqueda.fechaInicio=this.busqueda.fechaInicio;
                this.datosDescargabusqueda.fechaFin=this.busqueda.fechaFin;
            },
            graficaReporte(){
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

                this.dataGrafica = {
                    labels: arrayLabels,
                    datasets: [{
                                label: 'mm',
                                data: arrayDatos,
                                backgroundColor: ['#41afa5','rgb(144, 196, 248)','rgb(119, 194, 234)','rgb(224,153,183)','rgb(239,153,120)','rgb(254,246,163)','rgb(226,230,154)','rgb(170,215,210)','rgb(229,229,229)'],
                                borderColor: ['#41afa5','#ffff'],
                                borderWidth: 8
                            }]                
                };

                this.confGrafica={
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
            totall(base) {
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
            listarGestoresCartera(cartera){
                this.gestoresCartera=[];
                if(cartera!=''){
                    axios.get("listaGestores/"+cartera).then(res=>{
                        if(res.data){
                            this.gestoresCartera=res.data;
                        }
                    });
                }
            },
            generarReporteCartera(){
                this.loading2=true;
                this.datosCatera=[];
                this.carteraC=this.busquedaCartera.cartera;
                if(this.busquedaCartera.cartera!='' && this.busquedaCartera.ubicabilidad!='' && this.busquedaCartera.estructura!='' && this.busquedaCartera.mes!='' && this.busquedaCartera.gestor!=''){
                    axios.post("reporteEstructuraGestorCartera",this.busquedaCartera).then(res=>{
                        if(res.data){
                            this.datosCatera=res.data;
                            this.loading2=false;
                            this.graficaReporteCartera();
                        }
                    })
                }else{
                    setTimeout(() => {
                        this.loading=false;
                        this.datosCatera=[];
                    }, 500);
                }
                this.datosDescargabusquedaCartera.cartera=this.busquedaCartera.cartera;
                this.datosDescargabusquedaCartera.ubicabilidad=this.busquedaCartera.ubicabilidad;
                this.datosDescargabusquedaCartera.estructura=this.busquedaCartera.estructura;
                this.datosDescargabusquedaCartera.mes=this.busquedaCartera.mes;
                this.datosDescargabusquedaCartera.gestor=this.busquedaCartera.gestor;
            },
            graficaReporteCartera(){
                var arrayDatos=[];
                var arrayLabels=[];
                var ultDatos=0;
                for(var i=0;i<this.datosCatera.length;i++){
                    if(i<=6){
                        arrayDatos.push(this.datosCatera[i].clientes);
                        arrayLabels.push(this.datosCatera[i].estructura+" - "+this.formatoNumero(this.datosCatera[i].clientes,'C'));
                    }else{
                        ultDatos+=parseInt(this.datosCatera[i].clientes);
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
            totalC(base) {
                return this.datosCatera.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            descargarExcelCartera(){
                var data=[];
                var totalClientes=0;
                var totalCapital=0;
                var totalDeuda=0;
                var totalIc=0;
                var clientes=0;
                data.push(["Estructura","Clientes","Capital","Deuda","IC"]);
                for(var i=0;i<this.datosCatera.length;i++){
                    // data.push(Object.values(this.datos[i]));
                    data.push([this.datosCatera[i].estructura,
                            this.formatoNumero(parseInt(this.datosCatera[i].clientes),'C'),
                            this.formatoNumero(parseFloat(this.datosCatera[i].capital),'M'),
                            this.formatoNumero(parseFloat(this.datosCatera[i].deuda),'M'),
                            this.formatoNumero(parseFloat(this.datosCatera[i].importe),'M')]);
                    totalClientes+=parseInt(this.datosCatera[i].clientes);
                    totalCapital+=parseFloat(this.datosCatera[i].capital);
                    totalDeuda+=parseFloat(this.datosCatera[i].deuda);
                    totalIc+=parseFloat(this.datosCatera[i].importe);
                }
                data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M')]);
                let hoja="";
                this.carteras.forEach(element => {
                    if(this.carteraC==element.id){
                        hoja=element.cartera;
                    }
                });
                Excel.exportar(data,"Repote_estructura_gestor_cartera",hoja);
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
                var totalGestiones=0;
                if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                    data.push(["Estructura","Clientes","Capital","Deuda","IC",this.titulo_1,this.titulo_2,this.titulo_3]);
                }else{
                    data.push(["Estructura","Clientes","Capital","Deuda","IC",this.titulo_1,this.titulo_2]);
                }
            
                for(var i=0;i<this.datos.length;i++){
                    // data.push(Object.values(this.datosGestion[i]));
                    if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                        data.push([this.datos[i].estructura,
                                    this.formatoNumero(parseInt(this.datos[i].clientes),'C'),
                                    this.formatoNumero(parseFloat(this.datos[i].capital),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].deuda),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].importe),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].cantidad),'C'),
                                    this.formatoNumero(parseFloat(this.datos[i].total),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].promedio),'M')
                                    ]);
                    }else{
                        data.push([this.datos[i].estructura,
                                    this.formatoNumero(parseInt(this.datos[i].clientes),'C'),
                                    this.formatoNumero(parseFloat(this.datos[i].capital),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].deuda),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].importe),'M'),
                                    this.formatoNumero(parseFloat(this.datos[i].cantidad),'C'),
                                    this.formatoNumero(parseFloat(this.datos[i].total),'M')
                                    ]);
                    }

                    totalClientes+=parseInt(this.datos[i].clientes);
                    totalCapital+=parseFloat(this.datos[i].capital);
                    totalDeuda+=parseFloat(this.datos[i].deuda);
                    totalIc+=parseFloat(this.datos[i].importe);
                    totalCantidad+=parseInt(this.datos[i].cantidad);
                    totalTotal+=parseFloat(this.datos[i].total);   
                }

                if(this.tipo=="pdps" || this.tipo=="confirmacion"){
                    data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M'),this.formatoNumero(totalCantidad,'C'),this.formatoNumero(totalTotal,'M'),this.formatoNumero(totalTotal/totalCantidad,'M')]);
                }else{
                    data.push(["Total",this.formatoNumero(totalClientes,'C'),this.formatoNumero(totalCapital,'M'),this.formatoNumero(totalDeuda,'M'),this.formatoNumero(totalIc,'M'),this.formatoNumero(totalCantidad,'C'),this.formatoNumero(totalCantidad/totalClientes,'M')]);
                }
                let hoja="";
                this.carteras.forEach(element => {
                    if(this.carteraG==element.id){
                        hoja=element.cartera;
                    }
                });
                Excel.exportar(data,"Repote_estructura_gestor_gestion",hoja);
            },
            descargarCodigosCartera(valor){
                window.location.href="descargarEstructuraGestorCartera/"+this.datosDescargabusquedaCartera.cartera+"/"+this.datosDescargabusquedaCartera.ubicabilidad+"/"+this.datosDescargabusquedaCartera.estructura+"/"+valor+"/"+this.datosDescargabusquedaCartera.mes+"/"+this.datosDescargabusquedaCartera.gestor;
            },
            descargarCodigosGestion(valor){
                window.location.href="descargarEstructuraGestorGestion/"+this.datosDescargabusqueda.cartera+"/"+this.datosDescargabusqueda.tipo+"/"+this.datosDescargabusqueda.estructura+"/"+valor+"/"+this.datosDescargabusqueda.fechaInicio+"/"+this.datosDescargabusqueda.fechaFin+"/"+this.datosDescargabusqueda.gestor;
            }
        },
        components: {
            PieChart,            
        }    
    }
</script>
