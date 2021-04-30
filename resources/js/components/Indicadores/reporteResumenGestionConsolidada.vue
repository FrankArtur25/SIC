<template>
    <div >
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <label class="font-bold col-form-label text-dark text-righ">Fecha</label>
                        <input type="date" class="form-control" v-model="busqueda.fecha">
                    </div>
                    <small class="text-danger" v-if="mensaje">{{mensaje}}</small>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="text-white">.</label><br>
                        <a href="" class="btn btn-blue" @click.prevent="generarReporte()">
                            <span v-if="spinnerbuscar" class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>
                            Buscar
                        </a>
                    </div>
                </div>
                <div class="col-md-4 my-4 py-1">
                    <a href="" v-if="data1!='' && data2!=''" @click.prevent="descargarExcel()" class="btn btn-outline-blue btn-block waves-effect">
                                <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                    <i class="fa fa-download pr-1"></i>
                        Descargar Reporte
                    </a>
                </div>
            </div><br>
            <div class="row" v-if="viewTable">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablaConsolidado" style="width;50px">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr v-if="data1!=''">
                                    <td colspan="21">FECHA: {{data1[0].fecha_registro}}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CARTERA</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CLIENTES</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CAPITAL</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">DEUDA</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">IC</td>
                                    <td colspan="2" style="font-size:10px !important;">COBERTURA</td>
                                    <td colspan="3" style="font-size:10px !important;">UBICABILIDAD</td>
                                    <td colspan="2" style="font-size:10px !important;">INTENSIDAD</td>
                                    <td colspan="4" style="font-size:10px !important;">PDPS</td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px !important;">CARTERA TOTAL</td>
                                    <td style="font-size:10px !important;">CARTERA CONTACTO</td>
                                    <td style="font-size:10px !important;">CONTACTO + ND</td>
                                    <td style="font-size:10px !important;">NO CONTACTO</td>
                                    <td style="font-size:10px !important;">SIN HISTÓRICO</td>
                                    <td style="font-size:10px !important;">CARTERA TOTAL</td>
                                    <td style="font-size:10px !important;">CARTERA CONTACTO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">MONTO GENERADO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">CUMPLIDO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">CAÍDO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">VIGENTE</td>
                                </tr>
                            </thead>
                            <tbody style="font-size:10px !important;">
                                <tr class="text-center" v-if="data1==''">
                                    <td colspan="21">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in data1" :key="index" class="text-center" style="font-size:10px !important;">
                                    <td class="text-center" style="font-size:10px !important;">{{item.cartera}}</td>
                                    <td>{{formatoNumero(item.clientes,'C')}}</td>
                                    <td>S/.{{item.capital>0? formatoNumero2(item.capital,'M'):'0'}}</td>
                                    <td>S/.{{item.deuda>0? formatoNumero2(item.deuda,'M'):'0'}}</td>
                                    <td>S/.{{item.importe>0? formatoNumero2(item.importe,'M'):'0'}}</td>
                                    <td>{{item.cob_total>0? decimalAdjust('round',item.cob_total,-1):'0'}}%</td>
                                    <td>{{item.cob_contacto>0? decimalAdjust('round',item.cob_contacto):'0'}}%</td>
                                    <td>{{item.ubic_contacto>0? decimalAdjust('round',item.ubic_contacto):'0'}}%</td>
                                    <td>{{item.ubic_no_contacto>0? decimalAdjust('round',item.ubic_no_contacto):'0'}}%</td>
                                    <td>{{item.sin_gestion>0? decimalAdjust('round',item.sin_gestion):'0'}}%</td>
                                    <td>{{decimalAdjust('round',item.int_total)}}</td>
                                    <td>{{decimalAdjust('round',item.int_contacto)}}</td>
                                    <td>S/.{{item.monto>0? formatoNumero2(item.monto,'M'):'0'}}</td>
                                    <td>S/.{{item.cumplido>0? formatoNumero2(item.cumplido,'M'):'0'}}</td>
                                    <td>S/.{{item.caido>0? formatoNumero2(item.caido,'M'):'0'}}</td>
                                    <td>S/.{{item.vigente>0? formatoNumero2(item.vigente,'M'):'0'}}</td>
                                </tr>
                            </tbody>
                            <thead class="bg-gray-2 text-center font-weight-bold" style="font-size:8px !important;">
                                <tr class="text-center" v-if="totales1.clientes==0">
                                </tr>
                                <tr style="font-size:10px !important;" v-else>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totales1.clientes,'C')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.capital,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.deuda,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.importe,'M')}}</td>
                                    <td>{{totales1.ct? decimalAdjust('round',parseFloat(totales1.ct/totales1.contador),-1)+'%':'-'}}</td>
                                    <td>{{totales1.cc? decimalAdjust('round',parseFloat(totales1.cc/totales1.contador),-1)+'%':'-'}}</td>
                                    <td>{{totales1.uc? decimalAdjust('round',parseFloat(totales1.uc/totales1.contador),-1)+'%':'-'}}</td>
                                    <td>{{totales1.unc? decimalAdjust('round',parseFloat(totales1.unc/totales1.contador),-1)+'%':'-'}}</td>
                                    <td>{{totales1.usg? decimalAdjust('round',parseFloat(totales1.usg/totales1.contador),-1)+'%':'-'}}</td>
                                    <td>{{totales1.it? decimalAdjust('round',parseFloat(totales1.it/totales1.contador),-1):'-'}}</td>
                                    <td>{{totales1.ic? decimalAdjust('round',parseFloat(totales1.ic/totales1.contador),-1):'-'}}</td>
                                    <td>S/.{{formatoNumero2(totales1.monto,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.cumplido,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.caido,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales1.vigente,'M')}}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablaConsolidado" style="width;50px"> 
                            <thead class="bg-blue-3 text-white text-center">
                                <tr v-if="data2!=''">
                                    <td colspan="21">FECHA: {{data2[0].fecha_registro}}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CARTERA</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CLIENTES</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">CAPITAL</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">DEUDA</td>
                                    <td rowspan="2" style="vertical-align: middle;font-size:10px !important;">IC</td>
                                    <td colspan="2" style="font-size:10px !important;">COBERTURA</td>
                                    <td colspan="3" style="font-size:10px !important;">UBICABILIDAD</td>
                                    <td colspan="2" style="font-size:10px !important;">INTENSIDAD</td>
                                    <td colspan="4" style="font-size:10px !important;">PDPS</td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px !important;">CARTERA TOTAL</td>
                                    <td style="font-size:10px !important;">CARTERA CONTACTO</td>
                                    <td style="font-size:10px !important;">CONTACTO + ND</td>
                                    <td style="font-size:10px !important;">NO CONTACTO</td>
                                    <td style="font-size:10px !important;">SIN HISTÓRICO</td>
                                    <td style="font-size:10px !important;">CARTERA TOTAL</td>
                                    <td style="font-size:10px !important;">CARTERA CONTACTO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">MONTO GENERADO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">CUMPLIDO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">CAÍDO</td>
                                    <td style="vertical-align: middle;font-size:10px !important;">VIGENTE</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="data2==''">
                                    <td colspan="21">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in data2" :key="index" class="text-center" style="font-size:10px !important;">
                                    <td class="text-center" style="font-size:10px !important;">{{item.cartera}}</td>
                                    <td>{{formatoNumero(item.clientes,'C')}}</td>
                                    <td>S/.{{item.capital>0? formatoNumero2(item.capital,'M'):'0'}}</td>
                                    <td>S/.{{item.deuda>0? formatoNumero2(item.deuda,'M'):'0'}}</td>
                                    <td>S/.{{item.importe>0? formatoNumero2(item.importe,'M'):'0'}}</td>
                                    <td>{{item.cob_total>0? decimalAdjust('round',item.cob_total,-1):'0'}}%</td>
                                    <td>{{item.cob_contacto>0? decimalAdjust('round',item.cob_contacto,-1):'0'}}%</td>
                                    <td>{{item.ubic_contacto>0? decimalAdjust('round',item.ubic_contacto,-1):'0'}}%</td>
                                    <td>{{item.ubic_no_contacto>0? decimalAdjust('round',item.ubic_no_contacto,-1):'0'}}%</td>
                                    <td>{{item.sin_gestion>0? decimalAdjust('round',item.sin_gestion,-1):'0'}}%</td>
                                    <td>{{decimalAdjust('round',item.int_total,-1)}}</td>
                                    <td>{{decimalAdjust('round',item.int_contacto,-1)}}</td>
                                    <td>S/.{{item.monto>0? formatoNumero2(item.monto,'M'):'0'}}</td>
                                    <td>S/.{{item.cumplido>0? formatoNumero2(item.cumplido,'M'):'0'}}</td>
                                    <td>S/.{{item.caido>0? formatoNumero2(item.caido,'M'):'0'}}</td>
                                    <td>S/.{{item.vigente>0? formatoNumero2(item.vigente,'M'):'0'}}</td>
                                </tr>
                            </tbody>
                            <thead class="bg-gray-2 text-center font-weight-bold">
                                <tr class="text-center" v-if="totales2.clientes==0">
                                </tr>
                                <tr style="font-size:10px !important;"  v-else>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totales2.clientes,'C')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.capital,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.deuda,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.importe,'M')}}</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.ct/totales2.contador),-1)}}%</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.cc/totales2.contador),-1)}}%</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.uc/totales2.contador),-1)}}%</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.unc/totales2.contador),-1)}}%</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.usg/totales2.contador),-1)}}%</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.it/totales2.contador),-1)}}</td>
                                    <td>{{decimalAdjust('round',parseFloat(totales2.ic/totales2.contador),-1)}}</td>
                                    <td>S/.{{formatoNumero2(totales2.monto,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.cumplido,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.caido,'M')}}</td>
                                    <td>S/.{{formatoNumero2(totales2.vigente,'M')}}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    import Excel from '../../../../public/js/excel2.js';
    //import Excel from '../../../../public/js/redondear.js';
    export default {
        props:['carteras'],
        data() {
            return {
                data1: [],
                data2: [],
                totales1:{contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0},
                totales2:{contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0},
                viewTable:false,
                spinnerbuscar:false,
                busqueda:{fecha:''},
                mensaje:''
            }
        },
        methods:{
            generarReporte(){
                this.viewTable=false;
                this.spinnerbuscar=true;
                this.totales1={contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0};
                this.totales2={contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0};
                this.data1=[];
                this.data2=[];
                this.mensaje='';
                //let totales1={contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0};
                //let totales2 = {contador:0,clientes:0,capital:0,deuda:0,importe:0,ct:0,cc:0,uc:0,unc:0,usg:0,it:0,ic:0,monto:0,cumplido:0,caido:0,vigente:0};
                if(this.busqueda.fecha!=''){
                    axios.get("resumenGestionConsolidada/"+this.busqueda.fecha).then(res=>{
                        if(res.data){
                            console.log(res.data);
                            var datos=res.data;
                            console.log(datos);
                            this.spinnerbuscar=false;
                            this.viewTable=true;
                            this.data1=datos.data1;
                            console.log(datos.data1);
                            this.data2=datos.data2;
                            console.log(datos.data2);

                            this.data1.forEach((el)=>{
                                this.totales1.contador += 1;
                                this.totales1.clientes += parseInt(el.clientes);
                                this.totales1.capital  += parseFloat(el.capital);
                                this.totales1.deuda    += parseFloat(el.deuda);
                                this.totales1.importe  += parseFloat(el.importe);
                                this.totales1.ct       += el.cob_total>0? parseInt(el.cob_total):0;
                                this.totales1.cc       += el.cob_contacto>0? parseInt(el.cob_contacto):0;
                                this.totales1.uc       += el.ubic_contacto>0? parseFloat(el.ubic_contacto):0;
                                this.totales1.unc      += el.ubic_no_contacto>0? parseFloat(el.ubic_no_contacto):0;
                                this.totales1.usg      += el.sin_gestion>0? parseFloat(el.sin_gestion):0;
                                this.totales1.it       += el.int_total>0? parseFloat(el.int_total):0;
                                this.totales1.ic       += el.int_contacto>0? parseFloat(el.int_contacto):0;
                                this.totales1.monto    += el.monto>0? parseFloat(el.monto):0;
                                this.totales1.cumplido += el.cumplido>0? parseFloat(el.cumplido):0;
                                this.totales1.caido    += el.caido>0? parseFloat(el.caido):0;
                                this.totales1.vigente  += el.vigente>0? parseFloat(el.vigente):0;
                            });
                            console.log(this.totales1);

                            this.data2.forEach((el)=>{
                                this.totales2.contador += 1;
                                this.totales2.clientes += parseInt(el.clientes);
                                this.totales2.capital  += parseFloat(el.capital);
                                this.totales2.deuda    += parseFloat(el.deuda);
                                this.totales2.importe  += parseFloat(el.importe);
                                this.totales2.ct       += el.cob_total>0? parseInt(el.cob_total):0;
                                this.totales2.cc       += el.cob_contacto>0? parseInt(el.cob_contacto):0;
                                this.totales2.uc       += el.ubic_contacto>0? parseFloat(el.ubic_contacto):0;
                                this.totales2.unc      += el.ubic_no_contacto>0? parseFloat(el.ubic_no_contacto):0;
                                this.totales2.usg      += el.sin_gestion>0? parseFloat(el.sin_gestion):0;
                                this.totales2.it       += el.int_total>0? parseFloat(el.int_total):0;
                                this.totales2.ic       += el.int_contacto>0? parseFloat(el.int_contacto):0;
                                this.totales2.monto    += el.monto>0? parseFloat(el.monto):0;
                                this.totales2.cumplido += el.cumplido>0? parseFloat(el.cumplido):0;
                                this.totales2.caido    += el.caido>0? parseFloat(el.caido):0;
                                this.totales2.vigente  += el.vigente>0? parseFloat(el.vigente):0;
                            });
                            console.log(this.totales2);
                        }
                    })
                }else{
                    this.spinnerbuscar=false;
                    this.mensaje="Selecciona una fecha";    
                }
            },
            descargarExcel(){
                var data=[];
                let cantidad=this.data2.length;
                

                if(this.data1!=''){
                    data.push([this.data1[0].fecha_registro]);
                    data.push(["CARTERA","CLIENTES","CAPITAL","DEUDA","IC","COBERTURA","COBERTURA","UBICABILIDAD","UBICABILIDAD","UBICABILIDAD","INTENSIDAD","INTENSIDAD","PDPS","PDPS","PDPS","PDPS"]);
                    data.push(["CARTERA","CLIENTES","CAPITAL","DEUDA","IC","CARTERA TOTAL","CARTERA CONTACTO","CONTACTO + ND","NO CONTACTO","SIN HISTÓRICO","CARTERA TOTAL","CARTERA CONTACTO","MONTO GENERADO","CUMPLIDO","CAÍDO","VIGENTE"]);
                    for(var i=0;i<this.data1.length;i++){
                        data.push([this.data1[i].cartera,
                                this.formatoNumero(parseInt(this.data1[i].clientes),'C'),
                                this.formatoNumero2(parseFloat(this.data1[i].capital),'M'),
                                this.formatoNumero2(parseFloat(this.data1[i].deuda),'M'),
                                this.formatoNumero2(parseFloat(this.data1[i].importe),'M'),
                                this.decimalAdjust('round',parseFloat(this.data1[i].cob_total),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data1[i].cob_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data1[i].ubic_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data1[i].ubic_no_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data1[i].sin_gestion),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data1[i].int_total),-1),
                                this.decimalAdjust('round',parseFloat(this.data1[i].int_contacto),-1),
                                this.formatoNumero2(parseFloat(this.data1[i].monto),'M'),
                                this.formatoNumero2(parseFloat(this.data1[i].cumplido),'M'),
                                this.formatoNumero2(parseFloat(this.data1[i].caido),'M'),
                                this.formatoNumero2(parseFloat(this.data1[i].vigente),'M'),
                        ]);
                    }
                    data.push(["Total",
                        this.formatoNumero(this.totales1.clientes,'C'),
                        this.formatoNumero2(this.totales1.capital,'M'),
                        this.formatoNumero2(this.totales1.deuda,'M'),
                        this.formatoNumero2(this.totales1.importe,'M'),
                        this.decimalAdjust('round',parseFloat(this.totales1.ct/this.totales1.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales1.cc/this.totales1.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales1.uc/this.totales1.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales1.unc/this.totales1.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales1.usg/this.totales1.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales1.it/this.totales1.contador),-1),
                        this.decimalAdjust('round',parseFloat(this.totales1.ic/this.totales1.contador),-1),
                        this.formatoNumero2(this.totales1.monto,'M'),
                        this.formatoNumero2(this.totales1.cumplido,'M'),
                        this.formatoNumero2(this.totales1.caido,'M'),
                        this.formatoNumero2(this.totales1.vigente,'M'),
                    ]);
                }

                if(this.data2!=''){
                    data.push([""]);
                    data.push([this.data2[0].fecha_registro]);
                    data.push(["CARTERA","CLIENTES","CAPITAL","DEUDA","IC","COBERTURA","COBERTURA","UBICABILIDAD","UBICABILIDAD","UBICABILIDAD","INTENSIDAD","INTENSIDAD","PDPS","PDPS","PDPS","PDPS"]);
                    data.push(["CARTERA","CLIENTES","CAPITAL","DEUDA","IC","CARTERA TOTAL","CARTERA CONTACTO","CONTACTO + ND","NO CONTACTO","SIN HISTÓRICO","CARTERA TOTAL","CARTERA CONTACTO","MONTO GENERADO","CUMPLIDO","CAÍDO","VIGENTE"]);
                    
                    for(var i=0;i<this.data2.length;i++){
                        data.push([this.data2[i].cartera,
                                this.formatoNumero(parseInt(this.data2[i].clientes),'C'),
                                this.formatoNumero2(parseFloat(this.data2[i].capital),'M'),
                                this.formatoNumero2(parseFloat(this.data2[i].deuda),'M'),
                                this.formatoNumero2(parseFloat(this.data2[i].importe),'M'),
                                this.decimalAdjust('round',parseFloat(this.data2[i].cob_total),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data2[i].cob_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data2[i].ubic_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data2[i].ubic_no_contacto),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data2[i].sin_gestion),-1)+"%",
                                this.decimalAdjust('round',parseFloat(this.data2[i].int_total),-1),
                                this.decimalAdjust('round',parseFloat(this.data2[i].int_contacto),-1),
                                this.formatoNumero2(parseFloat(this.data2[i].monto),'M'),
                                this.formatoNumero2(parseFloat(this.data2[i].cumplido),'M'),
                                this.formatoNumero2(parseFloat(this.data2[i].caido),'M'),
                                this.formatoNumero2(parseFloat(this.data2[i].vigente),'M'),
                        ]);
                    }
                    data.push(["Total",
                        this.formatoNumero(this.totales2.clientes,'C'),
                        this.formatoNumero2(this.totales2.capital,'M'),
                        this.formatoNumero2(this.totales2.deuda,'M'),
                        this.formatoNumero2(this.totales2.importe,'M'),
                        this.decimalAdjust('round',parseFloat(this.totales2.ct/this.totales2.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales2.cc/this.totales2.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales2.uc/this.totales2.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales2.unc/this.totales2.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales2.usg/this.totales2.contador),-1)+"%",
                        this.decimalAdjust('round',parseFloat(this.totales2.it/this.totales2.contador),-1),
                        this.decimalAdjust('round',parseFloat(this.totales2.ic/this.totales2.contador),-1),
                        this.formatoNumero2(this.totales2.monto,'M'),
                        this.formatoNumero2(this.totales2.cumplido,'M'),
                        this.formatoNumero2(this.totales2.caido,'M'),
                        this.formatoNumero2(this.totales2.vigente,'M'),
                    ]);
                }

                Excel.exportar(data,cantidad,"Resumen_Gestión_Consolidada","Gestión_Consolidada");

            },
            /*totalResumen(base) {
                return this.resumen.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            totalGestiones(base) {
                return this.gestiones.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },*/
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
            formatoNumero2(num,tipo){
                if(tipo=='M'){
                    var nStr=parseFloat(num).toFixed(2);
                }else{
                    var nStr=num;
                }
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '' : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2
            },
            decimalAdjust( type, value, exp) {
                // Si el exp no está definido o es cero...
                if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
                }
                value = +value;
                exp = +exp;
                // Si el valor no es un número o el exp no es un entero...
                if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
                }
                // Shift
                value = value.toString().split('e');
                value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
                // Shift back
                value = value.toString().split('e');
                return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
            },
            
        },
        components: {
            
        }    
    }
</script>
