<template>
    <div >
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Fecha de Gestión</label>
                    <input type="date" class="form-control" v-model="busqueda.fecha">
                    <small v-if="mensaje" class="text-danger">{{mensaje}}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="text-white">-</label><br>
                    <div class="d-flex" style="gap:8px">
                        <a href="" @click.prevent="buscar()" class="btn btn-outline-blue">
                            <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Generar Reporte
                        </a>
                        <a href="" v-if="datos!='' && viewTabla==true" @click.prevent="descargar()" class="btn btn-outline-blue">
                            <span v-if="spinnerDescargar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Descarga Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-reponsive" v-if="viewTabla">
            <table class="table table-hover">
                <thead class="bg-blue-3 text-white text-center">
                    <tr>
                        <td rowspan="2" class="align-middle">CARTERA</td>
                        <td>{{mes_anterior}}</td>
                        <td colspan="2">{{mes_actual}}</td>
                        <td rowspan="2" class="align-middle">VARIACIÓN</td>
                    </tr>
                    <tr>
                        <td>PROCESADOS</td>
                        <td>PROCESADOS</td>
                        <td>POR PROCESAR</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''"  class="text-center">
                        <td colspan="5">No se encontraron resultados</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index"> 
                        <td class="text-center px-2">{{item.cartera}}</td>
                        <td class="text-center px-2 bg-gray" >{{formatoNumero(item.mes_pasado,'M')}}</td>
                        <td class="text-center px-2">{{formatoNumero(item.mes_actual,'M')}}</td>
                        <td class="text-center px-2">{{formatoNumero(item.procesar,'M')}}</td>
                        <td class="text-center px-2" :class="{'text-danger':item.variacion<0,'text-blue':item.variacion>0}">{{formatoNumero(item.variacion,'C')}}%</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-2">
                    <tr > 
                        <th class="text-center px-2">TOTAL</th>
                        <th class="text-center px-2" >{{formatoNumero(total('mes_pasado'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('mes_actual'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('procesar'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(Math.round(100-((total('mes_pasado')/(total('mes_actual')+total('procesar')))*100)),'C')}}%</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    import Excel from '../../../../public/js/excel.js';
    export default {
        props:['carteras'],
        data() {
            return {
                datos:[],
                busqueda:{fecha:''},
                spinnerBuscar:false,
                spinnerDescargar:false,
                mensaje:'',
                viewTabla:false,
                mes_anterior:'',
                mes_actual:'',
                meses:[{num:"01",mes:"ENERO"},{num:"02",mes:"FEBRERO"},{num:"03",mes:"MARZO"},{num:'04',mes:"ABRIL"},{num:'05',mes:"MAYO"},
                      {num:"06",mes:"JUNIO"},{num:"07",mes:"JULIO"},{num:'08',mes:"AGOSTO"},{num:'09',mes:"SETIEMBRE"},{num:'10',mes:"OCTUBRE"},
                      {num:"11",mes:"NOVIEMBRE"},{num:"12",mes:"DICIEMBRE"}]
            }
        },
        methods:{
           buscar(){
                this.viewTabla=false;
                this.mensaje="";
                if(this.busqueda.fecha!=''){
                    this.spinnerBuscar=true;
                    axios.post("comparativaPagosFecha",this.busqueda).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            for(var i=0;i<this.meses.length;i++){
                                if(this.meses[i].num==(this.busqueda.fecha).substr(5,2)){
                                    this.mes_actual=this.meses[i].mes;
                                    if(this.meses[i].mes=="Enero"){
                                        this.mes_anterior="Diciembre";
                                    }else{
                                        this.mes_anterior=this.meses[i-1].mes;
                                    }
                                }
                            }
                            this.spinnerBuscar=false;
                            this.viewTabla=true;
                        }
                    });
                }else{
                    this.mensaje="Ingresa una fecha";
                }
            },
            total(base) {
                return this.datos.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            descargar(){
                let data=[];
                let totalPagos=0;
                let totalPagosActual=0;
                let totalProcesar=0;
                data.push(["COMPARATIVA DE PAGOS","OCTUBRE","NOVIEMBRE","NOVIEMBRE",""]);
                data.push(["CARTERA","PROCESADOS","PROCESADOS","POR PROCESAR","VARIACIÓN"]);
                this.datos.forEach(d => {
                    data.push([
                        d.cartera,
                        this.formatoNumero(parseFloat(d.mes_pasado),'M'),
                        this.formatoNumero(parseFloat(d.mes_actual),'M'),
                        this.formatoNumero(parseFloat(d.procesar),'M'),
                        this.formatoNumero(d.variacion,'C')+"%",
                    ]);
                    totalPagos+=parseFloat(d.mes_pasado);
                    totalPagosActual+=parseFloat(d.mes_actual);
                    totalProcesar+=parseFloat(d.procesar);
                });
                data.push(["Total",
                            this.formatoNumero(totalPagos.toFixed(2),'C'),
                            this.formatoNumero(totalPagosActual.toFixed(2),'M'),
                            this.formatoNumero(totalProcesar.toFixed(2),'M'),
                            this.formatoNumero(Math.round(100-((totalPagos/(totalPagosActual+totalProcesar))*100)),'C')+"%"]);
                Excel.exportar(data,"Comparativa_pagos","Comparativa de Pagos",1);
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
            Excel
        }    
    }
</script>
