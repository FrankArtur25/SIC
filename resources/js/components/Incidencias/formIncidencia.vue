<template>
    <div >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="form-control" v-model="registro.cartera" @change="listarGestores(registro.cartera)">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Gestor</label>
                    <select class="form-control" v-model="registro.gestor" :disabled="gestores==''">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in gestores" :key="index"  class="option" :value="item.gestor">{{item.gestor}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Tipo de Incidencia</label>
                    <select class="form-control" v-model="registro.incidencia">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in incidencias" :key="index"  class="option" :value="item.id">{{item.incidencia}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Hora de Inicio</label>
                    <input type="time" class="form-control" v-model="registro.horaInicio">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Hora de Fin</label>
                    <input type="time" class="form-control" v-model="registro.horaFin">
                    <small>*Opcional</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3" v-if="registro.incidencia==4">
                <div class="form-group">
                    <label for="">Fecha de Permiso</label>
                    <input type="date" class="form-control" v-model="registro.fecha">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="">Escribir un detalle de la incidencia</label><br>
                    <textarea class="form-control" rows="4" v-model="registro.detalle"></textarea>
                </div>
            </div>
        </div>
        <div class="row" v-if="errors!=''">
            <div class="col-md-8">
                <div class="alert alert-warning border-left" >
                    <p class="mb-0">Corriga el(los) siguiente(s) error(es):</p>
                    <ul class="text-left"><li v-for="(error,index) in errors" :key="index" >{{ error }}</li></ul>
                </div>
            </div>
        </div>
        <a href="" @click.prevent="registrar()" class="btn btn-outline-blue">
            <span v-if="spinnerRegistrar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Registrar Incidencia
        </a>
    </div>
</template>

<script>
    
    export default {
        props:['carteras'],
        data() {
            return {
                gestores:[],
                incidencias:[],
                registro:{gestor:'',incidencia:'',detalle:'',fecha:'',cartera:'',horaInicio:'',horaFin:''},
                spinnerRegistrar:'',
                errors:[]
            }
        },
        created(){
            this.listarIncidencias();
        },
        methods:{
           listarIncidencias(){
              axios.get("tiposIncidencias").then(res=>{
                if(res.data){
                    this.incidencias=res.data;
                }
              });
           },
           listarGestores(cartera){
                this.registro.gestor="";
                this.gestores=[];
                if(cartera!=''){
                    axios.get("listaGestores/"+cartera).then(res=>{
                        if(res.data){
                            this.gestores=res.data;
                        }
                    });
                }
            },
            validarDatos(){
                this.errors=[];
                if(!this.registro.gestor){
                    this.errors.push("Selecciona un gestor");
                }
                if(!this.registro.incidencia){
                    this.errors.push("Selecciona una incidencia");
                }else{
                    if(this.registro.incidencia==4 && this.registro.fecha=='' ){
                        this.errors.push("Selecciona una fecha de permiso");
                    }
                }
                if(!this.registro.detalle){
                    this.errors.push("Escribe un detalle de la incidencia");
                }
                if(!this.registro.horaInicio){
                    this.errors.push("Selecciona una hora de inicio de la incidencia");
                }
            },
            registrar(){
                this.errors=[];
                this.validarDatos();
                if(this.errors.length==0){
                    this.spinnerRegistrar=true;
                    axios.post("insertIncidencia",this.registro).then(res=>{
                        if(res.data="ok"){
                            this.spinnerRegistrar=false;
                            toastr.info('Incidencia registrada con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                            this.limpiar();
                        }
                    });
                }
            },
            limpiar(){
                this.registro.cartera="";
                this.registro.gestor="";
                this.registro.incidencia="";
                this.registro.fecha="";
                this.registro.detalle="";
                this.registro.horaInicio="";
                this.registro.horaFin="";
            }
        },
        components: {
            
        }    
    }
</script>
