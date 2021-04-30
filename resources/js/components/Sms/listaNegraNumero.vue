<template>
    <div>
        <div class="py-3">
            <div class="form-group">
                <label for="" class="font-bold">Ingresar uno a más números(separados por comas)</label>
                <textarea  rows="8" class="form-control" v-model="datos.numeros"></textarea>
                <small v-if="mensaje" class="text-danger">{{mensaje}}</small>
                <small>Ej: 925412542,952658741,9xxxxxxxx,....</small>
            </div>
            <a href="" class="btn btn-outline-blue mt-2" @click.prevent="registrarListaNegra()">
                <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Enviar a Lista Negra
            </a>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading:false,
                view:true,
                datos:{numeros:''},
                mensaje:''
            }
        },
        methods:{
            registrarListaNegra(){
                this.loading=true;
                if(this.datos.numeros!=''){
                    axios.post('insertarListaNegra',this.datos).then(res=>{
                        if(res.data=="ok"){
                            this.loading=false;
                            this.datos.numeros='';
                        }
                    })
                }else{
                    this.mensaje="Es necesario completar el campo";
                }
            }
        },  
        components: {
            
        }
   
    }
</script>
