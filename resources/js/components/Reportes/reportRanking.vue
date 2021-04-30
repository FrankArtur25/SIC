<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="selectpicker form-control" v-model="busqueda.cartera" title="Seleccionar">
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Estructura</label>
                    <select class="selectpicker form-control" v-model="busqueda.estructura" @change="limpiarCall(busqueda.estructura)">
                        <option value="1">General</option>
                        <option value="2" selected>Por Call</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Call</label>
                    <!-- data-selected-text-format="count > 2" -->
                    <select class="selectpicker form-control" title="Seleccionar" v-model="busqueda.calls" :disabled="busqueda.estructura==1">
                        <option v-for="(item,index) in calls" :key="index" :value="item.id">{{item.calll}}</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Pago Inicio</label>
                    <input type="date" class="selectpicker form-control" v-model="busqueda.fechaInicio">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Pago Fin</label>
                    <input type="date" class="selectpicker form-control" v-model="busqueda.fechaFin">
                </div>
            </div>
        </div>
        <a href="" class="btn btn-outline-blue" @click.prevent="generarReporte()">
            <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
            Generar Reporte
        </a>
        <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        <div class="alert alert-warning my-3" v-if="errores!=''">
            <p class="font-bold mb-0">Corrija el(los) siguiente(s) error(es):</p>
            <ul>
                <li v-for="(item,index) in errores" :key="index">{{item}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras','calls'],
        data() {
            return {
                spinnerBuscar:false,
                busqueda:{cartera:'',estructura:'1',calls:[],tipoFecha:'1',fechaInicio:'',fechaFin:'',columnas:[2,3]},
                viewTabla:false,
                datos:[],
                view:{cantidad:'',monto:''},
                errores:[]
            }
        },
        methods:{
            generarReporte(){
                this.errores=[];
                this.validacion();
                if(this.errores.length==0){
                    if(this.busqueda.estructura==1){
                        this.busqueda.calls=0;
                    }
                    window.location.href="generarReporteRanking/"+this.busqueda.cartera+"/"+this.busqueda.estructura+"/"+this.busqueda.calls+"/"+this.busqueda.fechaInicio+"/"+this.busqueda.fechaFin;
                    
                }
            },
            limpiar(){
                this.busqueda={cartera:'',estructura:'1',calls:[],fechaInicio:'',fechaFin:''};
            },
            validacion(){
                if(!this.busqueda.cartera){
                    this.errores.push("Seleccionar cartera");
                }
                if(!this.busqueda.estructura){
                    this.errores.push("Seleccionar estructura");
                }
                if( this.busqueda.calls.length==0 && this.busqueda.estructura==2){
                    this.errores.push("Seleccionar al menos un call");
                }
                if(!this.busqueda.fechaInicio){
                    this.errores.push("Ingresar Fecha Inicio");
                }
                if(!this.busqueda.fechaFin){
                    this.errores.push("Ingresar Fecha Fin");
                }
            },
            limpiarCall(estructura){
                if(estructura==1){
                    this.busqueda.calls=[];
                }
            }
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
        components: {
            
        }    
    }
</script>
