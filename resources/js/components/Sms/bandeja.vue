<template>
    <div >
        <div class="text-center d-flex justify-content-center pt-5" v-if="loadingIn">
           <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
        <div v-else>
            <div class="d-flex justify-content-center pt-5" v-if="lista==''">
                <div class=" text-center">
                    <i class="fa fa-envelope text-gray-2 fa-3x"></i>
                    <p class="font-12">No se encontraron mensajes<br>en la bandeja de entrada</p>
                </div>
            </div>
            <!-- bandeja -->
            <div v-else>
                <div class="card rounded mt-1" style="height:calc(100vh - 170px)">
                    <div class="row mx-0 h-100">
                        <div class="col-md-3 border-right px-0">
                            <div class="p-3 text-left border-bottom bg-gray-top">
                                <p class="mb-0">Mensajes ({{total}})</p>
                            </div>
                            <div class="overflow-auto panel-list-btns" style="height:calc(100vh - 230px)">
                                <a  v-for="(item,index) in lista" :key="index" href="" @click.prevent="verchat(item.numero_z,index)" class="py-2 text-left px-3 border-bottom btn-blue-hover list-btns" :id="'b'+index"><i class="fa fa-phone fa-1x rounded-circle p-2 border bg-blue-2 text-white mr-2"></i>{{item.numero_z}}</a>
                            </div>
                        </div>
                        <div class="col-md-9 px-0 border-right border-left">
                            <div class="d-flex justify-content-center mt-5 pt-5" v-if="chat==''">
                                <div class="mt-5 pt-5 text-center">
                                    <div class="spinner-border" role="status" v-if="loading">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div v-else>
                                        <i class="fa fa-sms text-gray-2 fa-3x"></i>
                                        <p>No se encontraron mensajes</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row mx-0 h-100">
                                <div class="col-md-8 px-0">
                                    <div class="py-3 text-left px-3 border-bottom bg-gray-top" >
                                        <p class="mb-0">{{datosContacto.numero}}</p>
                                    </div>
                                    <div class="overflow-auto px-3 py-3" style="height:calc(100vh - 228px)">
                                        <p class="text-center">{{datosContacto.fecha}}</p>
                                        <div v-for="(item,index) in chat" :key="index" class="my-1">
                                            <div class="d-flex justify-content-end" v-if="item.estado==0">
                                                <div class="rounded bg-blue-2 text-white py-1 px-2 text-right chat">
                                                    <p class="mb-0 text-left">{{item.msj}}</p>
                                                    <small class="text-gray-light">{{item.hora}}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex" v-if="item.estado==1">
                                                <div class="rounded border py-1 px-2 chat">
                                                    <p class="mb-0 text-left">{{item.msj}}</p>
                                                    <div class="d-flex flex-content-end">
                                                        <small class="text-gray-2 text-right w-100">{{item.hora}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0 border-left">
                                    <div class="py-3 text-left px-3 border-bottom bg-gray-top">
                                        <p class="mb-0">Info. del Contacto</p>
                                    </div>
                                    <div class="overflow-auto text-center" style="height:calc(100vh - 228px)">
                                        <div class="mt-4 px-3">
                                            <img :src="'img/perfil.png'" width="70" height="70">
                                            <p v-if="datosContacto.cliente" class="font-15 mt-3">{{datosContacto.cliente}}</p>
                                            <p v-else class="font-15 mt-3">NO IDENTIFICADO</p>
                                            <p v-if="datosContacto.codigo" class="mb-0 font-weight-bold">Código</p>
                                            <p>{{datosContacto.codigo}}</p>
                                            <p v-if="datosContacto.cartera" class="mb-0 font-weight-bold">Cartera</p>
                                            <p>{{datosContacto.cartera}}</p>

                                        </div>
                                    </div>
                                </div>
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
        props:['carteras'],
        data() {
            return {
                lista: [],
                loading : false,
                loadingIn : true,
                color:'#3367d6',
                datosContacto:{cliente:'',codigo:'',cartera:'',numero:'',fecha:''},
                chat:[],
                total:0
            }
        },
        created(){
            this.listaNumeros();
        },
        methods:{
            listaNumeros(){
                axios.get("bandejaMsj").then(res=>{
                    if(res.data){
                        this.lista=res.data;
                        this.loadingIn=false;
                        this.total=this.lista.length;
                    }
                })
            },
            verchat(numero,index){
                $("#b"+index).addClass('btn-blue-hover-active');
                $(".list-btns").not('#b'+index).removeClass('btn-blue-hover-active');
                this.loading=true;
                this.chat=[];
                this.datosContacto.cliente=this.lista[index].cliente;
                this.datosContacto.codigo=this.lista[index].codigo;
                this.datosContacto.fecha=this.lista[index].fecha;
                this.datosContacto.cartera=this.lista[index].cartera;
                this.datosContacto.numero=this.lista[index].numero_z;
                axios.get("chat/"+numero).then(res=>{
                    if(res.data){
                        this.chat=res.data;
                        this.loading=false;
                    }
                })
            }
        },
        components: {
            
        }    
    }
</script>
