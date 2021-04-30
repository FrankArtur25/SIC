<template>
    <div>
        <div class="modal fadeInRight" id="modal-telefonos" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-rigth modal-xl" role="document" style="width:280px;">
                <div class="modal-content">
                <div class="modal-header bg-blue pt-3 pl-3 pr-4">
                    <p class="p-title mb-0 text-white"><i class="fa fa-phone pr-3"></i>TELÉFONOS ({{cantidad}})</p>
                    <a href="" class="close " data-dismiss="modal" aria-label="Close">
                        <span class="fa fa-times text-white"></span>
                    </a>
                </div>
                <div class="modal-body overflow-auto pl-3 pr-4 bg-gray-2">
                    <div>
                        <form method="POST" autocomplete="off" @submit.prevent="registrar">
                            <div class="form-group">
                                <label for="">Ingresar teléfono</label>
                                <input type="text" class="form-control form-control-sm" v-model="arreglo.telefono" maxlength="9" @keypress="soloNumeros">
                                <small v-if="mensaje" :class="{'text-danger':mensaje.substr(0, 1)!='R','text-green':mensaje.substr(0, 1)=='R'}">{{mensaje}}</small>
                            </div>
                            <button type="submit" class="btn btn-outline-blue btn-sm mb-4">
                                <span v-if="loadButton" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Agregar
                            </button>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-blue text-white text-center">
                                    <tr>
                                        <td colspan="2">Número de Contacto</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center"  v-if="telefonos==''">
                                        <td colspan="2">Sin teléfonos</td>
                                    </tr>
                                    <tr class="text-center" v-for="(item,index) in telefonos" :key="index" v-else>
                                        <td class="align-middle">{{item.tel}}</td>
                                        <td>
                                            <select class="form-control form-control-sm" @change.prevent="actualizarEstado(item.estado,item.telefono,item.id)" v-model="item.estado">
                                                <option value="0">Activo</option>
                                                <option value="1">Inactivo</option>
                                                <option value="2">Eliminar</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:["idCliente","telefonospanel"],
        data() {
            return {
                arreglo:{telefono:'',id:this.idCliente},
                mensaje:'',
                telefonos:this.telefonospanel,
                loadButton:false,
                estado:1,
                cantidad:0
            }
        },
        created(){
            this.cantidad=this.telefonos.length;
        },
        methods:{
            listaTelefonos(){
                // this.telefonos=[];
                const id= this.idCliente;
                axios.get("listaTel/"+id).then(res=>{
                    if(res.data){
                        this.telefonos=res.data['telefonos'];                           
                        this.cantidad=this.telefonos.length;
                    }
                })
            },
            registrar(){
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
                                this.$root.$emit('frglistarTelefonos');
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
                        this.$root.$emit('frglistarTelefonos');
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
    }
</script>
