<template>
    <div>
        <div class="border-puntos d-flex justify-content-center align-items-center p-3 my-3">
            <div class="text-center" v-if="loading">
                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                <p>Cargando archivo al servidor<br>Porfavor espere...</p>
            </div>
            <div class="text-center" v-else>
                <div v-if="view">
                    <i class="fa fa-cloud-upload-alt text-blue fa-3x"></i><br>
                    <p class="font-bold mb-0">Selecciona un archivo</p>
                    <p class="">Carga un archivo de formato .xlsx</p>
                    <input type="file" ref="file" class="form-control" id="file" name="file" accept=".xlsx" @change="seleccionArchivo"><br>
                    <a href="" class="btn btn-outline-blue" @click.prevent="cargarListaNegra()">Enviar a Lista Negra</a>
                </div>
                <div v-else>
                    <p class="font-bold mb-0">Registro Exitoso!</p>
                    <p>Los datos fueron enviados a lista negra.</p>
                    <a href="" class="btn btn-outline-blue" @click.prevent="viewInicial()">Cargar otro archivo</a>
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
                archivo:'',
                view:true,
                mensaje:''
            }
        },
        methods:{
            viewInicial(){
                this.loading=false;
                this.view=true;
                this.archivo='';
            },
            seleccionArchivo(e){
                this.archivo=e.target.files[0];
            },
            cargarListaNegra(){
                this.loading=true;
                let formData= new FormData(); 
                formData.append("archivo",this.archivo);
                axios.post('cargarListaNegra',formData).then(res=>{
                    if(res.data=="ok"){
                        this.loading=false;
                        this.view=false;
                    }
                })
            },
        },  
        components: {
            
        }
   
    }
</script>
