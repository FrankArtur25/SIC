<template>
    <div>
        <div class="modal fade" id="panel-mensaje" tabindex="-1" role="dialog"  aria-hidden="true" style="z-index:9999999">
            <div class="modal-dialog modal-dialog-rigth" role="document" style="width:280px;">
                <div class="modal-content">
                    <div class="modal-header bg-blue pt-3 pl-3 pr-4">
                        <p class="p-title mb-0 text-white"><i class="fa fa-phone pr-3"></i>Mensaje del Sistema</p>
                        <a href="" class="close " data-dismiss="modal" aria-label="Close">
                            <span class="fa fa-times text-white"></span>
                        </a>
                    </div>
                    <div class="modal-body overflow-auto pl-3 pr-4 bg-gray-2">
                        NO se cniccijcicjci
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:["idCliente"],
        data() {
            return {
                arreglo:{telefono:'',id:this.idCliente},
                mensaje:'',
                telefonos:[],
                loadButton:false,
                estado:1,
                cantidad:0
            }
        },
        created(){
           this.listaTelefonos();
        },
        methods:{
            listaTelefonos(){
                const id= this.idCliente;
                axios.get("listaTel/"+id).then(res=>{
                    if(res.data){
                        this.telefonos=res.data;
                        this.cantidad=this.telefonos.length;
                    }
                })
            },
            async registrar(){
                this.mensaje="";
                if(this.arreglo.telefono!=""){
                    this.loadButton=true;
                    try{
                        axios.post('insertarTel',this.arreglo).then(res=>{
                            if(res.data=="ok"){
                                this.loadButton=false;
                                this.mensaje = "Registro con éxito";
                                this.arreglo.telefono='';
                                this.listaTelefonos();
                            }
                        });
                    }catch(error){
                        this.mensaje = "Ha ocurrido un error!";
                    }
                }else{
                    this.mensaje="Completar el campo";
                }
            },
            actualizarEstado(est,tel,id){
                const datos={estado:est,
                            telefono:tel,
                            id:id}
                axios.put('actualizarEstadoTelefono',datos).then(res=>{
                    if(res.data=="ok"){
                        this.listaTelefonos();
                    }
                });
            },
            soloNumeros(e){
                var key = window.event ? e.which : e.keyCode;
                if (key < 48 || key > 57) {
                    e.preventDefault();
                }
            },     
            limpiar(){
                this.mensaje='';
                this.arreglo.telefono='';
            }
        },
        mounted() {
            this.$root.$on ('limpiarFrmTel',() => {
                this.limpiar();
            } );
        },
        updated(){
            this.$nextTick(() => {
                $('[data-toggle="tooltip"]').tooltip();
            })
        }
        
    }
</script>
