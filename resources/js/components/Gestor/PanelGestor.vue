<template>
    <div>       
        <div>
            <!-- btn recordatorios -->
             <div class="panel-top text-center">
                 <!-- fa-sort-down -->
                <a href="" class="btn-up" data-toggle="modal" data-target="#modal-recordatorio">
                    <i class="fa fa-clock fa-lg"></i>
                    <i v-if="recordatorio!=''" class="fa fa-circle text-danger pt-3 pl-0" style="position:absolute;"></i>
                </a>
            </div>
            <recordatorio v-if="recordatorio" :recordatorio="recordatorio" :telefonos="telRecordatorio" :pdp="pdpsRecordatorio" :cant_contacto="contactoRecordatorio" :gestiones="historicoGestiones" :tipoacceso="tipoacceso"/>
            <!-- lista de clientes y menu -->
            <clientes :userlogeado="userlogeado" :tipoacceso="tipoacceso" />   
        </div>
        <!-- detalle de cliente -->
        <div v-if="viewDetalleCliente==true" class="bg-white">
             <div class="panelEstandar">
                <div class="d-flex mx-3 justify-content-between">
                    <div class="d-flex">
                        <div class="pr-2 pt-1">
                            <i class="rounded-circle fa fa-user bg-blue text-white p-1"></i>
                        </div>
                        <p class="font-18 font-bold">{{dataCliente[0].nombre}}</p>
                    </div>
                    <div>
                        <a href="" @click.prevent="cerrarDetalle()" class="icono-bars waves-effect pb-2"><i class="fa fa-times"></i></a>
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
                            <detalleCliente :datos="dataCliente" v-if="dataCliente.length>0" :info="detalleGeneral['infoCliente']" :tipoacceso="tipoacceso" />
                        </div>
                        <!-- panel de historico de gestiones -->
                        <div class="col-md-8">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">HISTÓRICO GESTIONES</p>
                            </div>
                            <detalleGestiones :idCliente="idCliente" v-if="dataCliente.length>0" :historico="detalleGeneral['gestiones']"/>
                        </div>
                    </div><br>
                    <!-- panel de informacion deuda-->
                    <div class="row p-0 mx-0 my-2">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">INFORMACIÓN DE LA DEUDA</p>
                            </div> 
                            <detalleCuentas  v-if="dataCliente.length>0" :cuentas="detalleGeneral['cuentas']" />
                        </div>
                    </div>
                    <!-- panel de registro de gestion -->
                    
                    <formRegistrarGestion :id-cliente="idCliente" :tipo="1" :telefonosgenerales="detalleGeneral['telefonos']" :valcontacto="detalleGeneral['validar_contacto']"  :datospdp="detalleGeneral['pdps']" :tipoacceso="tipoacceso" :userocm="userocm" />
                    
                    <!-- botones laterales -->
                    <div class="btns-lateral">
                        <a href="#" class="btn-lateral bg-danger" data-toggle="modal" data-target="#modal-pagos"  @click.prevent="viewPagos=true"><label class="texto-vertical">PAGOS</label></a>
                        <!--acá por ejemplo -->
                        <a href="" data-toggle="modal" data-target="#modal-telefonos" class="btn-lateral" @click.prevent="verTelefonos()"><label class="texto-vertical">TELF</label></a>
                    </div>

                    <!-- panel de telefonos -->
                    <detalleTelefonos v-if="viewTelefonos" :idCliente="idCliente" :telefonospanel="this.detalleGeneral['telefonos']"/>
                    <!-- panel de pagos -->
                    <detallePagos v-if="viewPagos" :idCliente="idCliente"/>
                </div>
             </div>
        </div>
    </div>
</template>

<script>
    import clientes from './Clientes';
    import detalleCliente from './DetalleCliente';
    import detalleGestiones from './DetalleGestiones';
    import detalleCuentas from './DetalleCuentas';
    import formRegistrarGestion from './FormRegistrarGestion';
    import detalleTelefonos from './DetalleTelefonos';
    import detallePagos from './DetallePagos';
    import recordatorio from './Recordatorios';
    
    export default {
        props:["userlogeado","idlogeado","tipoacceso","userocm"],
        data() {
            return {
                viewDetalleCliente:false,
                viewTelefonos:false,
                viewPagos:false,
                dataCliente:[],
                detalleGeneral:[],
                idCliente:'',
                loadCarga:false,
                recordatorio:[],
                telRecordatorio:[],
                pdpsRecordatorio:[],
                contactoRecordatorio:[],
                historicoGestiones:[],
                idClienteRec:'',
                // viewalerta:'false'
            }
        },
        created(){
        },
        methods:{
            cerrarDetalle(){
                this.viewDetalleCliente=false;
                this.detalleGeneral=[];
                $('#contenidoLista').toggleClass('pos_fixed');
            },       
            verTelefonos(){
                this.viewTelefonos=true;
                this.$root.$emit('limpiarFrmTel');
            },
            telefonosRecordatorio(tel){
                this.$root.$emit('telefonosRecordatorio',tel); 
            },
            datosgenerales(id){
                this.loadCarga=true;
                this.detalleGeneral=[];
                axios.get("detalleCliente/"+id).then(res=>{
                    if(res.data){
                        this.detalleGeneral=res.data;
                        this.loadCarga=false;
                    }
                })
                
            }
        },
        mounted() {
            this.$root.$on('verDetalle', (datos) => {
                this.dataCliente=datos;
                this.idCliente=datos[0].id;
                this.viewDetalleCliente=true;
                this.datosgenerales(this.idCliente);
                // $('html, body').animate({scrollTop:0}, 'slow');
            } );
            // limpiar recordatorios
            this.$root.$on('limpiarRecordatorio', () => {
                this.recordatorio=[];
                this.telRecordatorio=[];
                this.pdpsRecordatorio=[];
                this.contactoRecordatorio=[];
                this.historicoGestiones=[];
            });

            // websocktes
            Echo.private('user.'+this.idlogeado)
            .listen('.evento-recordatorio', (data) => {
                this.recordatorio=[];
                this.telRecordatorio=[];
                this.pdpsRecordatorio=[];
                this.contactoRecordatorio=[];
                this.historicoGestiones=[];
                
                const currentdate = new Date(); 
                const hora= currentdate.getHours()<10?'0'+currentdate.getHours():currentdate.getHours();
                const min= currentdate.getMinutes()<10?'0'+currentdate.getMinutes():currentdate.getMinutes();
                const sec= currentdate.getSeconds()<10?'0'+currentdate.getSeconds():currentdate.getSeconds();
                const datetime=hora+":"+min+":"+sec;
    
                this.recordatorio.push(data.data['recordatorios']);
                this.telRecordatorio.push(data.data['telefonos']);   
                this.pdpsRecordatorio.push(data.data['pdps']);   
                this.contactoRecordatorio.push(data.data['validar_contacto']);   
                this.historicoGestiones.push(data.data['gestiones']);
    
                // if(this.recordatorio.length>0){
                //     this.idClienteRec=this.recordatorio[0][0].id;
                // }
                //this.$root.$emit('refreshGestiones',this.historicoGestiones);
                //this.$root.$emit('refreshFrmGestion',this.telRecordatorio,this.pdpsRecordatorio,this.contactoRecordatorio,this.idClienteRec);

                if(datetime>=data.data['recordatorios'].hora_programada && datetime<=data.data['recordatorios'].hora_fin){
                    toastr.success('Los recordatorios sólo se encuentran disponibles 5min después de su hora programada', 'Tienes un recordatorio activo',{"progressBar": true,"positionClass": "toast-bottom-right",});
                }
            });
        },
        components:{
            clientes,
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
