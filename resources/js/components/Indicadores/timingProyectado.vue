<template>
    <div >
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <label class="font-bold">Cartera</label>
                        <select class="form-control" v-model="busqueda.cartera">
                            <option value="">Seleccionar</option>
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
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="5">TIMING DIARIO</td>
                                </tr>
                                <tr>
                                    <td>Día</td>
                                    <td>Ingreso Diario Ideal</td>
                                    <td>Ingreso Diario Real</td>
                                    <td>Estado Diario</td>
                                    <td>Desfase</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="timing==''">
                                    <td colspan="5">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in timing" :key="index" class="text-center">
                                    <td>{{item.dia}}</td>
                                    <td>{{formatoNumero(item.indicador_ideal,'M')}}</td>
                                    <td>{{formatoNumero(item.indicador_real,'M')}}</td>
                                    <td>{{formatoNumero(item.estado,'C')}}%</td>
                                    <td :class="{'text-danger':item.desfase<0,'text-blue':item.desfase>0}">{{formatoNumero(item.desfase,'M')}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-2 text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totalTiming('indicador_ideal'),'M')}}</td>
                                    <td>{{formatoNumero(totalTiming('indicador_real'),'M')}}</td>
                                    <td></td>
                                    <td>{{formatoNumero(totalTiming('desfase'),'M')}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="5">PROYECTADO (3días)</td>
                                </tr>
                                <tr>
                                    <td>Día</td>
                                    <td>Proyectado Ideal</td>
                                    <td>Proyectado Real</td>
                                    <td>% Proyectado</td>
                                    <td>Desfase (S/.)</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="proyectado==''">
                                    <td colspan="5">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in proyectado" :key="index" class="text-center">
                                    <td>{{item.dia}}</td>
                                    <td>{{formatoNumero(item.proyectado_ideal,'M')}}</td>
                                    <td>{{formatoNumero(item.proyectado_real,'M')}}</td>
                                    <td>{{item.proyectado}}</td>
                                    <td :class="{'text-danger':item.desfase<0,'text-blue':item.desfase>0}">{{formatoNumero(item.desfase,'M')}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-2 text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{formatoNumero(totalProyectado('proyectado_ideal'),'M')}}</td>
                                    <td>{{formatoNumero(totalProyectado('proyectado_real'),'M')}}</td>
                                    <td></td>
                                    <td>{{formatoNumero(totalProyectado('desfase'),'M')}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td colspan="5">CUMPLIMIENTO PROYECTADO</td>
                                </tr>
                                <tr>
                                    <td>Cartera</td>
                                    <td>Proyectado</td>
                                    <td>Confirmaciones</td>
                                    <td>% Porcentaje</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="cumplimiento==''">
                                    <td colspan="5">No se encontraron resultados</td>
                                </tr>
                                <tr v-else v-for="(item,index) in cumplimiento" :key="index" class="text-center">
                                    <td>{{item.cartera}}</td>
                                    <td>{{formatoNumero(item.proyectado_ideal,'M')}}</td>
                                    <td>{{formatoNumero(item.monto,'M')}}</td>
                                    <td>{{formatoNumero(item.porcentaje,'C')}}%</td>
                                </tr>
                            </tbody>
                        </table>
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
                proyectado: [],
                timing: [],
                cumplimiento: [],
                viewTable:false,
                spinnerbuscar:false,
                busqueda:{cartera:''},
                mensaje:''
            }
        },
        methods:{
            generarReporte(){
                this.viewTable=false;
                this.spinnerbuscar=true;
                this.timing=[];
                this.cumplimiento=[];
                this.proyectado=[];
                this.mensaje='';
                if(this.busqueda.cartera!=''){
                    axios.get("timingProyectado/"+this.busqueda.cartera).then(res=>{
                        if(res.data){
                            var datos=res.data;
                            this.spinnerbuscar=false;
                            this.viewTable=true;
                            this.timing=datos['timing'];
                            this.proyectado=datos['proyectado'];
                            this.cumplimiento=datos['cumplimiento'];
                        }
                    })
                }else{
                    this.spinnerbuscar=false;
                    this.mensaje="Selecciona una cartera";    
                }
            },
            totalTiming(base) {
                return this.timing.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            totalProyectado(base) {
                return this.proyectado.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
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
            
        }    
    }
</script>
