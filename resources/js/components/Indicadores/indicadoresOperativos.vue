<template>
    <div >
         <div class="row my-3">
            <div class="col-md-4">
                <div class="px-2">
                    <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                    <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera" @change="listaAsignaciones(busqueda.cartera)">
                        <option selected value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
                <div class="px-2">
                    <label for="indicador" class="font-bold col-form-label text-dark text-righ">Indicador</label>
                    <select name="indicador" id="indicador" class="form-control" v-model="busqueda.indicador">
                        <option selected value="">Seleccionar</option>
                        <option class="option" value="cobertura">COBERTURA</option>
                        <option class="option" value="contactabilidad">CONTACTABILIDAD</option>
                        <option class="option" value="efectiva">CONTACTABILIDAD EFECTIVA</option>
                        <option class="option" value="intensidad">INTENSIDAD</option>
                        <option class="option" value="directa">INTENSIDAD DIRECTA</option>
                        <option class="option" value="tasa">TASA CIERRE</option>
                        <option class="option" value="promesas">EFECTIVIDAD PROMESAS</option>
                    </select>
                </div>
                <div class="px-2">
                    <label class="font-bold col-form-label text-dark text-righ">Asignación</label>
                    <select name="asignacion" id="asignacion" class="form-control" v-model="busqueda.asignacion" :disabled="asignaciones==''">
                        <option selected value="-1">TODA LA CARTERA</option>
                        <option v-for="(item,index) in asignaciones" :key="index" :value="item.id">{{item.valor}}</option>
                    </select>
                </div>
                <div class="px-2">
                    <label for="estructura" class="font-bold col-form-label text-dark text-righ">Estructura</label>
                    <select name="estructura" id="estructura" class="form-control" v-model="busqueda.estructura" @change="listaEstructuras(busqueda.estructura)">
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
                    <select name="item" id="item" class="form-control mt-1" v-model="busqueda.valorEstructura" :disabled="estructuras==''">
                        <option selected value="">Seleccionar</option>
                        <option v-for="(item,index) in estructuras" :key="index" :value="item.id">{{item.valor}}</option>
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
                <div class="d-flex justify-content-center pt-5" v-else-if="datos==''">
                    <div class="pt-5 text-center">
                        <i class="fa fa-chart-pie fa-2x text-blue"></i>
                        <p>Genera un reporte usando los<br>filtros de la izquierda.</p>
                    </div>
                </div>
                <div v-else class="">
                    <div class="" style="height:210px;width: content-box;">
                        <LineChart :chart-data="dataGrafica" :options="confGrafica"  class="p-0" style="height:410px;width: content-box;"></LineChart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LineChart from '../Chart/LineChart.js';
    import conf from '../Chart/conf.js';
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                loading : false,
                busqueda:{cartera:'',indicador:'',asignacion:'',estructura:'',valorEstructura:''},
                dataGrafica:[],
                confGrafica:[],
                asignaciones:[],
                estructuras:[]
            }
        },
        methods:{
            generarReporteCartera(){
                this.loading=true;
                this.datos=[];
                if(this.busqueda.cartera!='' && this.busqueda.asignacion!='' && this.busqueda.indicador!=''){
                    axios.post("reporteIndicadoresOperativos",this.busqueda).then(res=>{
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
            },
            listaAsignaciones(valor){
                this.asignaciones=[];
                this.limpiar();
                axios.get("listaAsignacion").then(res=>{
                    if(res.data){
                        this.asignaciones=res.data;
                        if(valor==34){
                            this.asignaciones.push({id:'0',valor:'NUEVOS CASTIGO'})
                        }else{
                            this.asignaciones.push({id:'0',valor:'NUEVOS'})
                        }
                    }
                })
            },
            listaEstructuras(valor){
                this.estructuras=[];
                this.busqueda.valorEstructura='';
                if(valor=='monto_camp' || valor=='saldo_deuda' || valor=='capital'){
                    this.estructuras=[{id:1,valor:'A: [0-500>'},
                                      {id:2,valor:'B: [500-1000>'},
                                      {id:3,valor:'C: [1000-3000>'},
                                      {id:4,valor:'D: [3000-+>'}]
                }else{
                    var parametros={cartera:this.busqueda.cartera,estructura:this.busqueda.estructura};
                    this.estructuras=[];
                    axios.post("listaEstructuras",parametros).then(res=>{
                        if(res.data){
                            this.estructuras=res.data;
                        }
                    })
                }
            },
            graficaReporteCartera(){
                var arrayDatasets=[];
                var arrayLabels=[];
                var arrayData=[];
                var arrayMeses=[];
                var arrayMesActual=[];
                var arrayMesMenos1=[];
                var arrayMesMenos2=[];
                var arrayBackground=['#FF0000','#41afa5','#032a51'];
                const indicador=this.busqueda.indicador;
                var acumuladoCantidadMesActual=0;
                var acumuladoCantidadMesMenos1=0;
                var acumuladoCantidadMesMenos2=0;
                var acumuladoTotalMesActual=0;
                var acumuladoTotalMesMenos1=0;
                var acumuladoTotalMesMenos2=0;
                for(var i=0;i<31;i++){
                    arrayLabels.push(i+1);
                }

                //mes actual
                // console.log(this.datos[0][0]);
                if(this.datos[0][0]!=""){
                    for(var j=0;j<this.datos[1].length;j++){
                        acumuladoCantidadMesActual+=parseInt(this.datos[1][j].cantidad);
                        if(this.busqueda.indicador=='intensidad'){
                            arrayMesActual[this.datos[1][j].dia-1]=Math.round10((acumuladoCantidadMesActual/this.datos[0][0].total),-2);
                        }else if(this.busqueda.indicador=='directa' || this.busqueda.indicador=='tasa' || this.busqueda.indicador=='promesas'){
                            if(this.datos[0][0].length>0){
                                acumuladoTotalMesActual=0;
                                for(var i=0;i<this.datos[0][0].length;i++){
                                    if(this.datos[1][j].dia>=this.datos[0][0][i].dia){
                                        acumuladoTotalMesActual+=parseInt(this.datos[0][0][i].total);
                                        // console.log("diaDato:"+this.datos[1][j].dia+"  diaTotal:" +this.datos[0][2][i].dia+" total:"+this.datos[0][0][i].total);
                                    }
                                }
                                if(this.busqueda.indicador=='directa'){
                                    arrayMesActual[this.datos[1][j].dia-1]=Math.round10((acumuladoCantidadMesActual/acumuladoTotalMesActual),-2);
                                }else{
                                    arrayMesActual[this.datos[1][j].dia-1]=Math.round10((acumuladoCantidadMesActual/acumuladoTotalMesActual)*100,-1);
                                    // console.log(acumuladoCantidadMesActual+" / "+acumuladoTotalMesActual);
                                }
                            }
                        }else{
                            arrayMesActual[this.datos[1][j].dia-1]=Math.round((acumuladoCantidadMesActual/this.datos[0][0].total)*100);
                        }
                        
                    }
                }
                // 1 mes atras
                if(this.datos[0][1]!=""){
                    for(var j=0;j<this.datos[2].length;j++){
                        acumuladoCantidadMesMenos1+=parseInt(this.datos[2][j].cantidad);
                        if(this.busqueda.indicador=='intensidad'){
                            arrayMesMenos1[this.datos[2][j].dia-1]=Math.round10((acumuladoCantidadMesMenos1/this.datos[0][1].total),-2);
                        }else if(this.busqueda.indicador=='directa' || this.busqueda.indicador=='tasa' || this.busqueda.indicador=='promesas'){
                            if(this.datos[0][1].length>0){
                                acumuladoTotalMesMenos1=0;
                                for(var i=0;i<this.datos[0][1].length;i++){
                                    if(this.datos[2][j].dia>=this.datos[0][1][i].dia){
                                        acumuladoTotalMesMenos1+=parseInt(this.datos[0][1][i].total);
                                    }
                                }
                                if(this.busqueda.indicador=='directa'){
                                    arrayMesMenos1[this.datos[2][j].dia-1]=Math.round10((acumuladoCantidadMesMenos1/acumuladoTotalMesMenos1),-2);
                                }else{
                                    arrayMesMenos1[this.datos[2][j].dia-1]=Math.round10((acumuladoCantidadMesMenos1/acumuladoTotalMesMenos1)*100,-1);
                                }
                            }
                        }else{
                            arrayMesMenos1[this.datos[2][j].dia-1]=Math.round((acumuladoCantidadMesMenos1/this.datos[0][1].total)*100);
                        }

                    }
                }
                // 2 meses atras
                if(this.datos[0][2]!=""){
                    for(var j=0;j<this.datos[3].length;j++){
                        acumuladoCantidadMesMenos2+=parseInt(this.datos[3][j].cantidad);
                        if(this.busqueda.indicador=='intensidad'){
                            arrayMesMenos2[this.datos[3][j].dia-1]=Math.round10((acumuladoCantidadMesMenos2/this.datos[0][2].total),-2);
                        }else if(this.busqueda.indicador=='directa' || this.busqueda.indicador=='tasa' || this.busqueda.indicador=='promesas'){
                            if(this.datos[0][2].length>0){
                                acumuladoTotalMesMenos2=0;
                                for(var i=0;i<this.datos[0][2].length;i++){
                                    if(this.datos[3][j].dia>=this.datos[0][2][i].dia){
                                        acumuladoTotalMesMenos2+=parseInt(this.datos[0][2][i].total);
                                    }
                                }
                                if(this.busqueda.indicador=='directa'){
                                    arrayMesMenos2[this.datos[3][j].dia-1]=Math.round10((acumuladoCantidadMesMenos2/acumuladoTotalMesMenos2),-2);
                                }else{
                                    arrayMesMenos2[this.datos[3][j].dia-1]=Math.round10((acumuladoCantidadMesMenos2/acumuladoTotalMesMenos2)*100,-1);
                                }
                            }
                        }else{
                            arrayMesMenos2[this.datos[3][j].dia-1]=Math.round((acumuladoCantidadMesMenos2/this.datos[0][2].total)*100);
                        }
                    }
                }
                arrayData.push(arrayMesMenos2,arrayMesMenos1,arrayMesActual);
                var fecha = new Date();
                var fecha1 = new Date();
                var fecha2 = new Date();
                var mesActual=(fecha.getFullYear())+"-"+(fecha.getMonth()+1);
                var mes1=fecha1.setMonth(fecha1.getMonth() - 1);
                var mesMenos1=fecha1.toISOString().substring(0, 7);
                var mes2=fecha2.setMonth(fecha2.getMonth() - 2);
                var mesMenos2=fecha2.toISOString().substring(0, 7);
                
                arrayMeses.push(mesMenos2,mesMenos1,mesActual);

                    for(var k=0;k<3;k++){
                        arrayDatasets.push({
                            label: arrayMeses[k],
                            data: arrayData[k],
                            // backgroundColor: 'rgba(255,255,255,0.1)',
                            lineTension: 0,
                            fill:false,
                            borderColor: arrayBackground[k],
                            borderWidth: 3
                        });
                    }

                
                this.dataGrafica = {
                    labels: arrayLabels,
                    datasets: arrayDatasets
                };
                
                
                this.confGrafica={
                    responsive:true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                    },
                    scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                        if(indicador=='intensidad' || indicador=='directa'){
                                            return value;
                                        }else{
                                            return value + '%';
                                        }
                                    },
                                    fontSize: 12
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) { 
                                    return "Día " + data.labels[tooltipItem[0].index]; 
                                },
                            label: function(t,value) {
                                if(indicador=='intensidad' || indicador=='directa'){
                                    return t.yLabel;
                                }else{
                                    return (t.yLabel).toFixed(0) +'%';
                                }
                            },
                            //title: () => null,
                            },
                            bodyFontSize:15,
                        }
                }
            },
            limpiar(){
                this.busqueda.asignacion='';
                this.busqueda.estructura='';
                this.busqueda.valorEstructura='';
                this.busqueda.indicador='';
                this.estructuras=[];
                this.asignaciones=[];
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
            LineChart,            
            conf
        }    
    }
</script>
