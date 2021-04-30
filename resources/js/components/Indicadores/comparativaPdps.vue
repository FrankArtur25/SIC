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
                        <td colspan="5">{{mes_anterior}}</td>
                        <td colspan="5">{{mes_actual}}</td>
                    </tr>
                    <tr>
                        <td>GENERADO</td>
                        <td>CUMPLIDO</td>
                        <td>CAIDO</td>
                        <td>PENDIENTE</td>
                        <td>% CUMPLIM.</td>
                        <td>GENERADO</td>
                        <td>CUMPLIDO</td>
                        <td>CAIDO</td>
                        <td>PENDIENTE</td>
                        <td>% CUMPLIM.</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''"  class="text-center">
                        <td colspan="11">No se encontraron resultados</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index"> 
                        <td class="text-left px-2">{{item.cartera}}</td>
                        <td class="text-center px-2 bg-gray" >{{formatoNumero(item.monto_pasado,'M')}}</td>
                        <td class="text-center px-2 bg-gray">{{formatoNumero(item.cumplidos_pasado,'M')}}</td>
                        <td class="text-center px-2 bg-gray">{{formatoNumero(item.caidos_pasado,'M')}}</td>
                        <td class="text-center px-2 bg-gray">{{formatoNumero(item.pendiente_pasado,'M')}}</td>
                        <th class="text-center px-2 bg-gray">{{item.cumplimiento_pasado!=null?formatoNumero(item.cumplimiento_pasado,'C'):0}}%</th>
                        <td class="text-center px-2" >{{formatoNumero(item.monto_actual,'M')}}</td>
                        <td class="text-center px-2">{{formatoNumero(item.cumplidos_actual,'M')}}</td>
                        <td class="text-center px-2">{{formatoNumero(item.caidos_actual,'M')}}</td>
                        <td class="text-center px-2">{{formatoNumero(item.pendiente_actual,'M')}}</td>
                        <th class="text-center px-2">{{item.cumplimiento_actual!=null?formatoNumero(item.cumplimiento_actual,'C'):0}}%</th>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-2">
                    <tr > 
                        <th class="text-center px-2">TOTAL</th>
                        <th class="text-center px-2">{{formatoNumero(total('monto_pasado'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('cumplidos_pasado'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('caidos_pasado'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('pendiente_pasado'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(Math.round((total('cumplidos_pasado')/(total('cumplidos_pasado')+total('caidos_pasado')))*100),'C')}}%</th>
                        <th class="text-center px-2">{{formatoNumero(total('monto_actual'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('cumplidos_actual'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('caidos_actual'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(total('pendiente_actual'),'M')}}</th>
                        <th class="text-center px-2">{{formatoNumero(Math.round((total('cumplidos_actual')/(total('cumplidos_actual')+total('caidos_actual')))*100),'C')}}%</th>
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
                    axios.post("comparativaPdpsFecha",this.busqueda).then(res=>{
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
                let totalMontoPasado=0;
                let totalMontoActual=0;
                let totalCumplidoPas=0;
                let totalCumplidoAct=0;
                let totalPendientePas=0;
                let totalPendienteAct=0;
                let totalCaidoPas=0;
                let totalCaidoAct=0;
                
                data.push(["","OCTUBRE","OCTUBRE","OCTUBRE","OCTUBRE","OCTUBRE","NOVIEMBRE","NOVIEMBRE","NOVIEMBRE","NOVIEMBRE","NOVIEMBRE"]);
                data.push(["CARTERA","GENERADO","CUMPLIDO","CAIDO","PENDIENTE","% CUMPLIM.","GENERADO","CUMPLIDO","CAIDO","PENDIENTE","% CUMPLIM."]);
                this.datos.forEach(d => {
                    data.push([
                        d.cartera,
                        this.formatoNumero(parseFloat(d.monto_pasado),'M'),
                        this.formatoNumero(parseFloat(d.cumplidos_pasado),'M'),
                        this.formatoNumero(parseFloat(d.caidos_pasado),'M'),
                        this.formatoNumero(parseFloat(d.pendiente_pasado),'M'),
                        this.formatoNumero(d.cumplimiento_pasado,'C')+"%",
                        this.formatoNumero(parseFloat(d.monto_actual),'M'),
                        this.formatoNumero(parseFloat(d.cumplidos_actual),'M'),
                        this.formatoNumero(parseFloat(d.caidos_actual),'M'),
                        this.formatoNumero(parseFloat(d.pendiente_actual),'M'),
                        this.formatoNumero(d.cumplimiento_actual,'C')+"%",
                    ]);
                    totalMontoPasado+=parseFloat(d.monto_pasado);
                    totalMontoActual+=parseFloat(d.monto_actual);
                    totalCumplidoPas+=parseFloat(d.cumplidos_pasado);
                    totalCumplidoAct+=parseFloat(d.cumplidos_actual);
                    totalCaidoPas+=parseFloat(d.caidos_pasado);
                    totalCaidoAct+=parseFloat(d.caidos_actual);
                    totalPendientePas+=parseFloat(d.pendiente_pasado);
                    totalPendienteAct+=parseFloat(d.pendiente_actual);
                });
                data.push(["Total",
                            this.formatoNumero(totalMontoPasado.toFixed(2),'C'),
                            this.formatoNumero(totalCumplidoPas.toFixed(2),'M'),
                            this.formatoNumero(totalCaidoPas.toFixed(2),'M'),
                            this.formatoNumero(totalPendientePas.toFixed(2),'M'),
                            this.formatoNumero(Math.round(((totalCumplidoPas/(totalCumplidoPas+totalCaidoPas))*100)),'C')+"%",
                            this.formatoNumero(totalMontoActual.toFixed(2),'C'),
                            this.formatoNumero(totalCumplidoAct.toFixed(2),'M'),
                            this.formatoNumero(totalCaidoAct.toFixed(2),'M'),
                            this.formatoNumero(totalPendienteAct.toFixed(2),'M'),
                            this.formatoNumero(Math.round(((totalCumplidoAct/(totalCumplidoAct+totalCaidoAct))*100)),'C')+"%"
                            ]);

                Excel.exportar(data,"Comparativa_pdps","Comparativa de Pdps",2);
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
