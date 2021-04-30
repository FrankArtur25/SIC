<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Cód. Operación</label>
                    <input type="text" disabled class="form-control" v-model="asignacion.codigo">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="form-control" v-model="asignacion.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensaje.cartera" class="text-danger">{{mensaje.cartera}}</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Ingresar Usuario</label>
                    <input type="text" class="form-control" v-model="asignacion.usuario">
                    <small v-if="mensaje.usuario" class="text-danger">{{mensaje.usuario}}</small>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Ingresar Códigos</label>
                    <textarea class="form-control" rows="7" v-model="asignacion.clientes"></textarea>
                    <small v-if="mensaje.clientes" class="text-danger">{{mensaje.clientes}}</small>
                </div>
            </div>
        </div>
        <div class="pt-2">
            <a href="" class="btn btn-outline-blue" @click.prevent="generarAsignacion()">
                <span v-if="spinnerAsignacion" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                Generar Asignación
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        </div>
        
    </div>
</template>

<script>
    
    export default {
        props:['carteras','codigo'],
        data() {
            return {
                spinnerAsignacion:false,
                asignacion:{cartera:'',codigo:this.codigo,usuario:'',clientes:''},
                mensaje:{cartera:'',error:'',usuario:'',clientes:''},
            }
        },
        methods:{
            limpiar(){
                this.asignacion.cartera='';
                this.asignacion.usuario='';
                this.asignacion.clientes='';
                this.mensaje.cartera='';
                this.mensaje.clientes='';
                this.mensaje.usuario='';
            },
    
            generarCodigo(){
                axios.get("generarCodigoAsignacion").then(res=>{
                    if(res.data){
                        this.asignacion.codigo=res.data;
                    }
                });
            },
            generarAsignacion(){
                this.mensaje.error='';
                if(this.asignacion.codigo!='' && this.asignacion.cartera!='' && this.asignacion.usuario!='' && this.asignacion.clientes!=''){
                    this.spinnerAsignacion=true;
                    this.mensaje.cartera='';
                    this.mensaje.clientes='';
                    this.mensaje.usuario='';
                    axios.post("generarAsignacionIndividual",this.asignacion).then(res=>{
                        try {
                            if(res.data=="ok"){
                                toastr.info('Los datos se actualizaron con éxito', 'Asignación Exitosa!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                                this.spinnerAsignacion=false;
                                this.limpiar();
                                this.generarCodigo();
                            }
                        } catch (error) {
                            this.mensaje.error='ERROR! Se perdió la conexión con el Servidor';
                        }
                    });
                }else{
                    if(!this.asignacion.cartera){
                        this.mensaje.cartera='Completar campo';
                    }
                    if(!this.asignacion.usuario){
                        this.mensaje.usuario='Completar campo';
                    }
                    if(!this.asignacion.clientes){
                        this.mensaje.clientes='Completar campo';
                    }
                }
            },
        },
         
    }
</script>
