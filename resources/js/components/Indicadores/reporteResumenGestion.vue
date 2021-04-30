<template>
    <div >
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <label class="font-bold">Cartera</label>
                        <select class="form-control" v-model="busqueda.cartera">
                            <option value="">Seleccionar</option>
                            <option value="0">TODAS LAS CARTERAS</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                        <small class="text-danger" v-if="mensaje">{{mensaje}}</small>
                    </div>
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
            </div><br>
            <div class="row" v-if="viewTable">
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="5">GESTIONES POR CARTERA</td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Cartera</td>
                                    <td class="align-middle">Contacto</td>
                                    <td class="align-middle">No Disponible</td>
                                    <td class="align-middle">No Contacto</td>
                                    <td class="align-middle">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="gestiones==''">
                                    <td colspan="5">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in gestiones" :key="index" class="text-center">
                                    <td class="text-left">{{item.cartera}}</td>
                                    <td>{{formatoNumero(item.contactos,'C')}}</td>
                                    <td>{{formatoNumero(item.no_disponible,'C')}}</td>
                                    <td>{{formatoNumero(item.no_contacto,'C')}}</td>
                                    <td class="font-bold">{{formatoNumero(item.gestiones,'C')}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-2 text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totalGestiones('contactos'),'C')}}</td>
                                    <td>{{formatoNumero(totalGestiones('no_disponible'),'C')}}</td>
                                    <td>{{formatoNumero(totalGestiones('no_contacto'),'C')}}</td>
                                    <td>{{formatoNumero(totalGestiones('gestiones'),'C')}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="5">PDPs Y CONFIRMAS</td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Cartera</td>
                                    <td class="align-middle">PDPs</td>
                                    <td class="align-middle">Monto PDPs</td>
                                    <td class="align-middle">Confirmas</td>
                                    <td class="align-middle">Monto Confirmas</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="gestiones==''">
                                    <td colspan="5">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in gestiones" :key="index" class="text-center">
                                    <td class="text-left">{{item.cartera}}</td>
                                    <td>{{formatoNumero(item.pdps,'C')}}</td>
                                    <td>{{formatoNumero(item.monto_pdps,'M')}}</td>
                                    <td><a href="" @click.prevent="verConfirmaciones(item.idcartera,item.confs,item.cartera)" class="text-black">{{formatoNumero(item.confs,'C')}}</a></td>
                                    <td>{{formatoNumero(item.monto_confs,'M')}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-2 text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totalGestiones('pdps'),'C')}}</td>
                                    <td>{{formatoNumero(totalGestiones('monto_pdps'),'M')}}</td>
                                    <td><a href="" @click.prevent="verConfirmaciones(totalCartera(),totalGestiones('confs'),'TODAS LAS CARTERAS')" class="text-black">{{formatoNumero(totalGestiones('confs'),'C')}}</a></td>
                                    <td>{{formatoNumero(totalGestiones('monto_confs'),'M')}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="6">GESTIONES POR GESTOR</td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Gestor</td>
                                    <td class="align-middle">Cartera</td>
                                    <td class="align-middle">Contacto</td>
                                    <td class="align-middle">No Disponible</td>
                                    <td class="align-middle">No Contacto</td>
                                    <td class="align-middle">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="resumen==''">
                                    <td colspan="6">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in resumen" :key="index">
                                    <td>{{item.gestor}}</td>
                                    <td>{{item.cartera}}</td>
                                    <td class="text-center">{{formatoNumero(item.contacto,'C')}}</td>
                                    <td class="text-center">{{formatoNumero(item.no_disponible,'C')}}</td>
                                    <td class="text-center">{{formatoNumero(item.no_contacto,'C')}}</td>
                                    <td class="text-center font-bold">{{formatoNumero(item.gestiones,'C')}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-2 text-center font-weight-bold">
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <td>{{formatoNumero(totalResumen('contacto'),'c')}}</td>
                                    <td>{{formatoNumero(totalResumen('no_disponible'),'c')}}</td>
                                    <td>{{formatoNumero(totalResumen('no_contacto'),'c')}}</td>
                                    <td>{{formatoNumero(totalResumen('gestiones'),'c')}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalConfirmaciones" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-blue-3 text-white px-3 pb-0">
                            <div class="pb-2">
                                <p class="mb-0">{{conf_cartera}}</p>
                                <small>Confirmaciones</small>
                            </div>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body px-3">
                            <div class="d-flex justify-content-center py-5" v-if="spinnerConf">
                                <span class="spinner-border text-blue" role="status" aria-hidden="true"></span>
                            </div>
                            <div class="table-responsive" v-else>
                                <table class="table table-hover">
                                    <thead class="bg-blue-3 text-white">
                                        <tr>
                                            <td class="text-center">Fecha Conf.</td>
                                            <td class="text-center">Cantidad</td>
                                            <td class="text-center">Monto (S/.)</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in confirmaciones" :key="index">
                                            <td class="text-center" :class="{'bg-gray':item.fecha==hoy}">{{item.fecha}}</td>
                                            <td class="text-center" :class="{'bg-gray':item.fecha==hoy}">{{formatoNumero(item.cantidad,'C')}}</td>
                                            <td class="text-right px-2" :class="{'bg-gray':item.fecha==hoy}">S/. {{formatoNumero(item.monto,'M')}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-gray-2">
                                        <tr>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">{{formatoNumero(totalConfirmaciones('cantidad'),'C')}}</th>
                                            <th class="text-right px-2">S/. {{formatoNumero(totalConfirmaciones('monto'),'M')}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras'],
        data() {
            return {
                resumen: [],
                gestiones: [],
                viewTable:false,
                spinnerbuscar:false,
                busqueda:{cartera:''},
                mensaje:'',
                confirmaciones:[],
                conf_cartera:'',
                spinnerConf:false,
                hoy:'',
                idcartera:''
            }
        },
        created(){
            var date = new Date();
            this.hoy=date.getFullYear()+"-"+this.addZero(date.getMonth()+1)+"-"+this.addZero(date.getDate());
        },
        methods:{
            generarReporte(){
                this.viewTable=false;
                this.spinnerbuscar=true;
                this.resumen=[];
                this.gestiones=[];
                this.mensaje='';
                if(this.busqueda.cartera!=''){
                    this.idcartera=this.busqueda.cartera;
                    axios.get("resumenGestionDia/"+this.busqueda.cartera).then(res=>{
                        if(res.data){
                            var datos=res.data;
                            this.spinnerbuscar=false;
                            this.viewTable=true;
                            this.resumen=datos['resumen'];
                            this.gestiones=datos['gestiones'];
                        }
                    })
                }else{
                    this.spinnerbuscar=false;
                    this.mensaje="Selecciona una cartera";    
                }
            },
            totalResumen(base) {
                return this.resumen.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            totalGestiones(base) {
                return this.gestiones.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
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
            verConfirmaciones(idcartera,cantidad,cartera){
                this.confirmaciones=[];
                if(cantidad>0){
                    this.spinnerConf=true;
                    this.conf_cartera=cartera;
                    $("#modalConfirmaciones").modal();
                    axios.get("detalleConfirmaciones/"+idcartera).then(res=>{
                        if(res.data){
                            this.confirmaciones=res.data;
                            this.spinnerConf=false;
                        }
                    });
                }
            },
            totalConfirmaciones(base) {
                return this.confirmaciones.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            addZero(i) {
                if (i < 10) {
                    i = '0' + i;
                }
                return i;
            },
            totalCartera(){
                return this.idcartera;
            }
        },
        components: {
            
        }    
    }
</script>
