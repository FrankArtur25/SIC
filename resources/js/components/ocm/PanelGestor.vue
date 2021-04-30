<template>
    <div>       
        <div class="bg-white">
             <div class="panelEstandar">
                <div class="d-flex mx-3 justify-content-between">
                    <div class="d-flex">
                        <div class="pr-2 pt-1">
                            <i class="rounded-circle fa fa-user bg-blue text-white p-1"></i>
                        </div>
                        <p class="font-18 font-bold">{{cliente}}</p>
                    </div>
                    <div>
                        <p>{{userlogeado}}</p>
                    </div>
                </div>
                <!-- modal de carga -->
                <div class="panel-carga bg-white" v-if="loadCarga">
                    <div class="d-flex justify-content-center pt-5">
                        <div class="spinner-border text-blue" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <!-- datos -->
                <div v-else>
                    <div class="row p-0 mx-0">
                        <!-- panel de informacion de clientes -->
                        <div class="col-md-4 border-blue">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">INFORMACIÓN DEL CLIENTE</p>
                            </div>
                            <detalleCliente :datos="dataCliente" v-if="dataCliente.length>0" :info="detallegeneral['infoCliente']" :tipoacceso="tipoacceso" />
                        </div>
                        <!-- panel de historico de gestiones -->
                        <div class="col-md-8">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">HISTÓRICO GESTIONES</p>
                            </div>
                            <detalleGestiones :idCliente="idcliente" v-if="dataCliente.length>0" :historico="detallegeneral['gestiones']"/>
                        </div>
                    </div><br>
                    <!-- panel de informacion deuda-->
                    <div class="row p-0 mx-0 my-2">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">INFORMACIÓN DE LA DEUDA</p>
                            </div> 
                            <detalleCuentas  v-if="dataCliente.length>0" :cuentas="detallegeneral['cuentas']" />
                        </div>
                    </div>
                    <!-- panel de registro de gestion -->
                    
                    <formRegistrarGestion :id-cliente="idcliente" :tipo="1" :telefonosgenerales="detallegeneral['telefonos']" :valcontacto="detallegeneral['validar_contacto']"  :datospdp="detallegeneral['pdps']" :tipoacceso="tipoacceso" :numero="detallegeneral['telefono']" />
                    
                    <!-- botones laterales -->
                    <!-- <div class="btns-lateral"> -->
                        <!-- <a href="#" class="btn-lateral bg-danger" data-toggle="modal" data-target="#modal-pagos"  @click.prevent="viewPagos=true"><label class="texto-vertical">PAGOS</label></a> -->
                        <!--acá por ejemplo -->
                        <!-- <a href="" data-toggle="modal" data-target="#modal-telefonos" class="btn-lateral" @click.prevent="verTelefonos()"><label class="texto-vertical">TELF</label></a> -->
                    <!-- </div> -->

                    <!-- panel de telefonos -->
                    <!-- <detalleTelefonos v-if="viewTelefonos" :idCliente="idcliente" :telefonospanel="this.detallegeneral['telefonos']"/> -->
                    <!-- panel de pagos -->
                    <!-- <detallePagos v-if="viewPagos" :idCliente="idcliente"/> -->
                </div>
             </div>
        </div>
    </div>
</template>

<script>
    import detalleCliente from '../Gestor/DetalleCliente';
    import detalleGestiones from '../ocm/DetalleGestiones';
    import detalleCuentas from '../Gestor/DetalleCuentas';
    import formRegistrarGestion from '../ocm/FormRegistrarGestion';
    import detalleTelefonos from '../Gestor/DetalleTelefonos';
    import detallePagos from '../Gestor/DetallePagos';
    import recordatorio from '../Gestor/Recordatorios';
    
    export default {
        props:["userlogeado","idlogeado","tipoacceso","idcliente","detallegeneral"],
        data() {
            return {
                viewDetalleCliente:false,
                viewTelefonos:false,
                viewPagos:false,
                dataCliente:this.detallegeneral['infoCliente'],
                // detallegeneral:[],
                // idcliente:'',
                loadCarga:false,
                recordatorio:[],
                telRecordatorio:[],
                pdpsRecordatorio:[],
                contactoRecordatorio:[],
                historicoGestiones:[],
                idclienteRec:'',
                cliente:'',
                numero:''
            }
        },
        created(){
            if(this.dataCliente.length>0){
                this.cliente=this.detallegeneral['infoCliente'][0].nombre;
            }
        },
        methods:{
            verTelefonos(){
                this.viewTelefonos=true;
                this.$root.$emit('limpiarFrmTel');
            },
        },
        components:{
            detalleCliente,
            detalleGestiones,
            detalleCuentas,
            formRegistrarGestion,
            detalleTelefonos,
            detallePagos,
            recordatorio
        }
    }
</script>
