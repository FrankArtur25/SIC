<template>
    <div>
        <div class="py-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="font-bold">Ingresar uno número</label>
                        <input  type="text" class="form-control" v-model="datos.numero">
                        <small v-if="mensaje" class="text-danger">{{mensaje}}</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="" class="font-bold text-white">.</label><br>
                        <a href="" class="btn btn-outline-blue" @click.prevent="buscarListaNegra()">
                            <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Buscar
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive py-2" v-if="view==true">
                        <table class="table " v-if="numeros!=''">
                            <thead class="bg-blue text-white text-center">
                                <tr>
                                    <td>Número</td>
                                    <td>Usuario</td>
                                    <td>Fecha de Carga</td>
                                    <td class="border-0 bg-white"></td>
                                </tr>
                            </thead>   
                            <tbody>
                                <tr v-for="(item,index) in numeros" :key="index" class="text-center">
                                    <td>{{item.numero}}</td>
                                    <td>{{item.usuario}}</td>
                                    <td>{{item.fecha}}</td>
                                    <td class="border-0" ><a href="" @click.prevent="retirarNumero(item.id)" class="btn btn-sm btn-outline-blue">Retirar de Lista Negra</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <p>El número ingresado no se encuentra en lista negra.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading:false,
                view:false,
                datos:{numero:''},
                mensaje:'',
                numeros:[],
                numeroBuscado:''
            }
        },
        methods:{
            buscarListaNegra(){
                this.mensaje='';
                this.view=false;
                this.numeros=[];
                this.numeroBuscado=this.datos.numero;
                if(this.datos.numero!=''){
                    this.loading=true;
                    axios.post('buscarListaNegra',this.datos).then(res=>{
                        if(res.data){
                            this.numeros=res.data;
                            this.loading=false;
                            this.view=true;
                        }
                    })
                }else{
                    this.mensaje="Es necesario completar el campo";
                }
            },
            retirarNumero(id){
                var datos={numero:this.numeroBuscado};
                axios.get('retirarListaNegra/'+id).then(res=>{
                    if(res.data){
                        axios.post('buscarListaNegra',datos).then(res=>{
                            if(res.data){
                                this.numeros=res.data;
                            }
                        })  
                    }
                })
            }
        },  
        components: {
            
        }
   
    }
</script>
