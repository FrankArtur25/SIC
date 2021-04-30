<template>
    <div >
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">Seleccionar Cartera</label>
                            <select class="form-control" v-model="busqueda.cartera" :disabled="bloquear">
                                <option value="">Seleccionar</option>
                                <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Usuario Predictivo</label>
                            <input type="text" class="form-control" v-model="busqueda.usuario" :disabled="bloquear">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Fecha Predictivo - Desde</label>
                            <input type="datetime-local" class="form-control" v-model="busqueda.fechaInicio" :disabled="bloquear">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Fecha Predictivo - Hasta</label>
                            <input type="datetime-local" class="form-control" v-model="busqueda.fechaFin" :disabled="bloquear">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Cargar archivo</label>
                            <input type="file" class="form-control" :disabled="bloquear">
                        </div>
                    </div>
                    <div class="col-md-6 pt-2" v-if="viewResultados==false">
                        <a href="" @click.prevent="verDetalle()" class="btn btn-outline-blue">
                            <span v-if="spinnerDetalle" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Ver Detalle
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-5" v-if="viewResultados">
                <div class="border rounded text-center p-5">
                    <p class="font-bold">RESULTADOS</p>
                    <div class="d-flex w-100 justify-content-center" style="gap:8px">
                        <div>
                            <p class="font-bold mb-0 font-25">{{cantidadGestiones}}</p>
                            <p class="">Cant. Gestiones<br>Registradas</p>
                        </div>
                        <div>
                            <p class="font-bold mb-0 font-25 text-danger">{{cantidadPorGestionar}}</p>
                            <p class="">Cant. Gestiones<br>Por Registrar</p>
                        </div>
                    </div>
                    <a href="" class="btn btn-outline-blue btn-block" @click.prevent="registrar()">
                        <span v-if="spinnerRegistrar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Registrar Gestiones
                    </a>
                    <a href="" class="btn btn-outline-blue btn-block" @click.prevent="cancelar()">Cancelar</a>
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
                spinnerRegistrar:'',
                spinnerDetalle:'',
                viewResultados:false,
                busqueda:{cartera:'',fechaInicio:'',fechaFin:'',usuario:'',archivo:''},
                bloquear:false,
                cantidadGestiones:0,
                cantidadPorGestionar:0
            }
        },
        created(){
        
        },
        methods:{
           listarIncidencias(){
              axios.get("tiposIncidencias").then(res=>{
                if(res.data){
                    this.incidencias=res.data;
                }
              });
           },
           verDetalle(){
               this.spinnerDetalle=true;
               this.viewResultados=true;
               this.spinnerDetalle=false;
               this.bloquear=true;
           },
           cancelar(){
               this.viewResultados=false;
               this.bloquear=false;
           },
           registrar(){
               this.spinnerRegistrar=true;
               axios.post("",this.busqueda).then(res=>{
                    if(res.data=="ok"){
                        this.spinnerRegistrar=false;
                    }
                });
           }
        },
        components: {
            
        }    
    }
</script>
