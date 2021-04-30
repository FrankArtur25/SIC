<template>
    <div >
        <div>
            <p class="font-bold text-blue mb-0">Datos Generales</p>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" v-model="registro.nombre">
                    </div>
                    <div class="form-group">
                        <label for="">Meta</label>
                        <input type="text" class="form-control" v-model="registro.meta">
                    </div>
                    <div class="form-group">
                        <label for="">Local</label>
                        <select class="form-control selectpicker" v-model="registro.local" title="Seleccionar">
                            <option v-for="(item,index) in locales" :key="index" :value="item.idoficina">{{item.local}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Call</label>
                        <select class="form-control selectpicker" v-model="registro.call" title="Seleccionar">
                            <option v-for="(item,index) in calls" :key="index" :value="item.id">{{item.calll}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tipo de Acceso</label>
                        <select class="form-control selectpicker" v-model="registro.tipo_acceso" @change="validarCarteras(registro.tipo_acceso)" title="Seleccionar">
                            <option value="5">ADMINISTRADOR</option>
                            <option value="1">SUPERVISOR</option>
                            <option value="2">GESTOR TELEFÓNICO</option>
                            <option value="7">RRHH</option>
                            <option value="6">SISTEMAS</option>
                            <option value="8">GESTOR TELEFÓNICO - BOLSA</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="registro.tipo_acceso!=2 && registro.tipo_acceso!=6">
                        <label for="">Carteras</label>
                        <select class="form-control selectpicker" multiple v-model="registro.carteras" data-selected-text-format="count" title="Seleccionar">
                            <option value="0">TODAS LAS CARTERAS</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <p class="font-bold text-blue mb-0">Acceso al Sistema</p>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Código</label>
                        <input type="text" class="form-control" disabled v-model="registro.codigo">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Clave</label>
                        <input type="password" class="form-control" v-model="registro.clave">
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
        props:['carteras','locales','calls','codigoaleatorio'],
        data() {
            return {
                registro:{nombre:'',meta:'',local:'',call:'',tipo_acceso:'2',carteras:'0',codigo:this.codigoaleatorio,clave:''},
                spinnerRegistrar:'',
                errors:[]
            }
        },
        methods:{
            generarCodigo(){
                axios.get("codigoEmpleado").then(res=>{
                    if(res.data){
                        this.registro.codigo=res.data;
                    }
                });
            },
            validarDatos(){
                this.errors=[];
                if(!this.registro.nombre){
                    this.errors.push("Ingresar un nombre");
                }
                if(!this.registro.local){
                    this.errors.push("Selecciona un local");
                }
                if(!this.registro.call){
                    this.errors.push("Selecciona un call");
                }
                if(!this.registro.codigo){
                    this.errors.push("Ingresar un código");
                }
                if(!this.registro.clave){
                    this.errors.push("Ingresar una clave");
                }
            },
            registrar(){
                this.errors=[];
                this.validarDatos();
                if(this.errors.length==0){
                    this.spinnerRegistrar=true;
                    axios.post("insertarEmpleado",this.registro).then(res=>{
                        if(res.data="ok"){
                            this.spinnerRegistrar=false;
                            toastr.info('Empleado registrado con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                            this.limpiar();
                            this.generarCodigo();
                        }
                    });
                }
            },
            limpiar(){
                this.registro.codigo="";
                this.registro.carteras="0";
                this.registro.tipo_acceso="2";
                this.registro.clave="";
                this.registro.call="";
                this.registro.local="";
                this.registro.nombre="";
                this.registro.meta="";
            },
            validarCarteras(i){
                if(i==2){
                    this.registro.carteras='';
                }else{
                    this.registro.carteras='0';
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
