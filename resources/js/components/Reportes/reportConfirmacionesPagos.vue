<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Gestión Inicio</label>
                    <input type="date" class="form-control" v-model="busqueda.fechaInicio">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Gestión Fin</label>
                    <input type="date" class="form-control" v-model="busqueda.fechaFin">
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
                busqueda:{fechaInicio:'',fechaFin:''},
                viewTabla:false,
                datos:[],
                errores:[]
            }
        },
        methods:{
            generarReporte(){
                this.errores=[];
                this.validacion();
                if(this.errores.length==0){
                    window.location.href="generarReporteConfirmacionesPagos/"+this.busqueda.fechaInicio+"/"+this.busqueda.fechaFin;
                }
            },
            limpiar(){
                this.busqueda={fechaInicio:'',fechaFin:''};
            },
            validacion(){
                if(!this.busqueda.fechaInicio){
                    this.errores.push("Ingresar Fecha Inicio");
                }
                if(!this.busqueda.fechaFin){
                    this.errores.push("Ingresar Fecha Fin");
                }
            },
        },
        components: {
            
        }    
    }
</script>
