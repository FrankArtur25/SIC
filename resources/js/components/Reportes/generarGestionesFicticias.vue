<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Modalidad</label>
                    <select class="form-control" title="Seleccionar" v-model="busqueda.modalidad" @change="seleccionarModalidad(busqueda.modalidad)">
                        <option value="">Seleccionar</option>
                        <option value="1">Call</option>
                        <option value="2" selected>Campo</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Respuesta</label>
                    <select class="form-control" v-model="busqueda.paleta" title="Seleccionar" :disabled="busqueda.modalidad==''">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in respuestas" :key="index" :value="item.res_id">{{item.res_des}}</option>
                    </select>
                </div>
            </div>
        </div>
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
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Cantidad</label>
                    <input type="number" class="form-control" v-model="busqueda.cantidad">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Cartera</label>
                    <select class="form-control" v-model="busqueda.cartera" title="Seleccionar">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
        </div>
        <a href="" class="btn btn-outline-blue" @click.prevent="generarGestiones()">
            <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
            Generar Gestiones
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
        props:['carteras'],
        data() {
            return {
                spinnerBuscar:false,
                busqueda:{paleta:'',modalidad:'',cantidad:'',fechaInicio:'',fechaFin:'',cartera:'0'},
                viewTabla:false,
                errores:[],
                respuestas:[],
                loading : false,
            }
        },
        methods:{
            seleccionarModalidad(mod){
                if(mod==1){
                    axios.get("listRespuestas").then(res=>{
                        if(res.data){
                            this.respuestas=res.data;
                        }
                    });
                }else{
                    axios.get("listRespuestasCampo").then(res=>{
                        if(res.data){
                            this.respuestas=res.data;
                        }
                    });
                }   
            },
            generarGestiones(){
                this.errores=[];
                //this.busqueda = {paleta:'',modalidad:'',cantidad:'',fechaInicio:'',fechaFin:'',cartera:'0'};
                this.validacion();
                if(this.errores.length==0){
                    window.location.href="generarficticias/"+this.busqueda.paleta+"/"+this.busqueda.modalidad+"/"+this.busqueda.cantidad+"/"+this.busqueda.fechaInicio+"/"+this.busqueda.fechaFin+"/"+this.busqueda.cartera;
                }
            },
            limpiar(){
                this.busqueda={paleta:'',modalidad:'',cantidad:'',fechaInicio:'',fechaFin:'',cartera:'0'};
            },
            validacion(){
                if(!this.busqueda.paleta){
                    this.errores.push("Seleccionar paleta");
                }
                if( !this.busqueda.modalidad){
                    this.errores.push("Seleccionar modalidad");
                }
                if( !this.busqueda.cantidad){
                    this.errores.push("Ingresar cantidad");
                }
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
