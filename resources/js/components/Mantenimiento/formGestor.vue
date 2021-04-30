<template>
    <div >
        <div>
            <p class="font-bold text-blue mb-0">Datos Personales</p>
            <div class="row mb-3">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="">Nombre y Apellidos</label>
                        <input type="text" class="form-control" v-model="registro.nombre">
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <p class="font-bold text-blue mb-0">Datos Laborales</p>
            <div class="row">
                <!-- <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Fecha de Ingreso</label>
                        <input type="date" class="form-control" v-model="registro.fecha_ingreso">
                    </div>
                </div> -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Modalidad</label>
                        <select class="form-control" v-model="registro.modalidad" title="Seleccionar">
                            <option value="">Seleccionar</option>
                            <option value="TC">Tiempo Completo</option>
                            <option value="MM">Medio Tiempo Mañana</option>
                            <option value="MT">Medio Tiempo Tarde</option>
                            <option value="PRC">Practicante</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Cartera a trabajar</label>
                        <select class="form-control"  v-model="registro.cartera"  title="Seleccionar">
                            <option value="">Seleccionar</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-5"></div> -->
                
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Firma</label>
                        <input type="text" class="form-control" v-model="registro.firma">
                    </div>
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
            Registrar Datos
        </a>
    </div>
</template>

<script>
    export default {
        props:['carteras'],
        data() {
            return {
                registro:{nombre:'',fecha_ingreso:'',modalidad:'',cartera:'',firma:''},
                spinnerRegistrar:'',
                errors:[]
            }
        },
        methods:{
            validarDatos(){
                this.errors=[];
                if(!this.registro.nombre){
                    this.errors.push("Ingresar un nombre");
                }
                if(!this.registro.modalidad){
                    this.errors.push("Selecciona una modalidad");
                }
                if(!this.registro.cartera){
                    this.errors.push("Selecionar una cartera");
                }
            },
            registrar(){
                this.errors=[];
                this.validarDatos();
                if(this.errors.length==0){
                    this.spinnerRegistrar=true;
                    axios.post("insertarGestor",this.registro).then(res=>{
                        if(res.data="ok"){
                            this.spinnerRegistrar=false;
                            toastr.info('Gestor registrado con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                            this.limpiar();
                        }
                    });
                }
            },
            limpiar(){
                this.registro.cartera="";
                this.registro.fecha_ingreso="";
                this.registro.nombre="";
                this.registro.modalidad="";
                this.registro.firma="";
            }
        },
        
    }
</script>
