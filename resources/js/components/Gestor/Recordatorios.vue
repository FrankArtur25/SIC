<template>
    <div>
        <div class="modal fade slideInDown" id="modal-recordatorio" 
        tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content " >
                    <div class="modal-body px-3 bg-gray-2 pb-0" v-if="recordatorio==''">
                        <div class="d-flex justify-content-center py-5">
                            <div class="text-center">
                                <i class="fa fa-clock fa-2x text-blue"></i><br>
                                <p class="font-15 pt-1">No tiene recordatorios pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body overflow-auto px-3 bg-gray-2 pb-0" v-else>
                        <div class="table-responsive" id="table-recordatorios">
                            <table class="table table-hover">
                                <thead class="bg-blue text-white text-center">
                                    <tr>
                                        <td class="align-middle">CODIGO</td>
                                        <td class="align-middle">NOMBRE</td>
                                        <td class="align-middle">DNI/RUC</td>
                                        <td class="align-middle">CAPITAL</td>
                                        <td class="align-middle">DEUDA</td>
                                        <td class="align-middle">IC</td>
                                        <td class="align-middle">MEDIO</td>
                                        <td class="align-middle">PRODUCTO</td>
                                        <td class="align-middle">ULT. RPTA</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td>{{recordatorio[0].codigo}}</td>
                                        <td>{{recordatorio[0].nombre}}</td>
                                        <td>{{recordatorio[0].dni}}</td>
                                        <td>{{recordatorio[0].capital}}</td>
                                        <td>{{recordatorio[0].deuda}}</td>
                                        <td>{{recordatorio[0].importe}}</td>
                                        <td>{{recordatorio[0].telefono}}</td>
                                        <td>{{recordatorio[0].producto}}</td>
                                        <td>{{recordatorio[0].ult_resp}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- gestiones -->
                        <div class="pr-2">
                            <div class="d-flex">
                                <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">HISTÓRICO GESTIONES</p>
                            </div>
                            <!-- detalleGestiones -->
                            <div class="table-responsive" style="height:265px">
                                <table class="table table-hover" v-if="gestiones[0]">
                                    <thead class="bg-gray">
                                        <tr class="text-center">
                                            <td class="align-middle font-11" style="width:80px">FECHA</td>
                                            <td class="align-middle font-11" style="width:20px">PERFIL</td>
                                            <td class="align-middle font-11" style="width:90px">MEDIO</td>
                                            <td class="align-middle font-11" style="width:20px">UBIC.</td>
                                            <td class="align-middle font-11" style="width:170px">RESPUESTA</td>
                                            <td class="align-middle font-11" >DETALLE</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="gestiones[0]==''" class="text-center">
                                            <td colspan="6">No existen gestiones asociadas al cliente</td>
                                        </tr>
                                        <tr v-for="(item,index) in gestiones[0]" :key="index" v-else>
                                            <td class="font-11">{{item.fecha}}<br>{{item.hora}}</td>
                                            <td class="text-center">
                                                <i class="fas fa-user-tie fa-lg" v-if="item.tipo==1 || item.tipo==5" ></i>
                                                <i class="fa fa-headset fa-lg" v-if="item.tipo==2"></i>
                                            </td>
                                            <td class="font-11">{{item.medio}}</td>
                                            <td class="text-center font-11">{{item.ubic}}</td>
                                            
                                            <td class="font-11" v-if="item.tolltip==1">
                                                <a href="#" class="text-black" data-toggle="tooltip"  data-placement="right" :title="'Fecha: '+item.fecha_pdp +' Monto: '+item.monto_pdp">{{item.respuesta}}</a>
                                            </td>
                                            <td class="font-11" v-else-if="item.tolltip==0">
                                                {{item.respuesta}}
                                            </td>
                                            <td class="font-11">{{item.detalle}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <!-- <detalleGestiones :idCliente="recordatorio[0].id" :historico="historicoGestiones[0]" v-if="historicoGestiones"/> -->
                        </div>
                        <!-- formulario de registro -->
                         <div class="row px-0 mx-0 my-3 bg-gray-2 rounded" >
                            <div class="col-md-12">
                                <div class="d-flex">
                                    <p class=" badge bg-blue text-white py-2 px-3 min-w-125 text-left">REGISTRO DE GESTIÓN</p>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="row px-0 mx-0 pb-2 pt-2">
                                    <div class="col-md-6">
                                        <table style="width:100%">
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1" style="width:18px">Teléfono</td>
                                                <td class="pb-2">
                                                    <select class="form-control font-12 form-control-sm" v-model="datos.telefono" @change="inactivarUbicabilidad()">
                                                        <option value="">Seleccionar</option>
                                                        <option v-for="(item,index) in telefonos[0]" :key="index" :class="{'bg-success text-white':item.contacto>0,'bg-warning':item.gestion>0 && item.contacto==0,'bg-danger text-white':item.gestion==0 && item.contacto==0}"  :value="item.telefono">{{item.tel}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1">Ubicabilidad</td>
                                                <td class="pb-2">
                                                    <select class="form-control font-12 form-control-sm" v-model="ubicabilidad" @change="obtenerRespuestas()" :disabled="datos.telefono==''">
                                                        <option value="">Seleccionar</option>
                                                        <option value="0">Contacto</option>
                                                        <option value="1">No Contacto</option>
                                                        <option value="2" v-if="tipoacceso!=8">No Disponible</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1">Respuesta</td>
                                                <td class="pb-2">
                                                    <select class="form-control font-12 form-control-sm" :disabled="ubicabilidad=='' || respuestas==''" v-model="datos.respuesta" @change="validarRespuestas(datos.respuesta)">
                                                        <option value="">Seleccionar</option>
                                                        <option v-for="(item,index) in respuestas" :key="index" :value="item.id">{{item.respuesta}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="font-12" v-if="viewMotivo"> 
                                                <td class="text-right pr-1">Motivo de No Pago</td>
                                                <td class="pb-2">
                                                    <select class="form-control font-12 form-control-sm" v-model="datos.motivoNoPago">
                                                        <option value="">Seleccionar</option>
                                                        <option v-for="(item,index) in motivos" :key="index" :value="item.id">{{item.motivo}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table style="width:100%">
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1">Fecha</td>
                                                <td class="pb-2">
                                                    <input type="date" :min="fechaActual" :max="fechaMax" v-on:keydown.capture.prevent.stop class="form-control font-12 form-control-sm" :disabled="![1,2,43].includes(datos.respuesta)" v-model="datos.fechaPDP">
                                                </td>
                                            </tr>
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1">Monto</td>
                                                <td class="pb-2">
                                                    <input type="number" class="form-control font-12 form-control-sm" :disabled="![1,2,43].includes(datos.respuesta)" v-model="datos.montoPDP">
                                                </td>
                                            </tr>
                                            <tr class="font-12"> 
                                                <td class="text-right pr-1">Moneda</td>
                                                <td class="pb-2">
                                                    <select class="form-control font-12 form-control-sm" :disabled="![1,2,43].includes(datos.respuesta)" v-model="datos.moneda">
                                                        <option value="0">SOLES</option>
                                                        <option value="1">DOLARES</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="row px-0 mx-0">
                                    <div class="col-md-2 text-right">
                                        <label class="font-12 pt-2">Detalle de Gestión</label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control w-100" rows="3"  v-model="datos.detalle" maxlength="255"  oncopy="return false" onpaste="return false"></textarea>
                                        <small style="font-size:10px;">Max. 255 caractéres</small>
                                    </div>
                                </div>
                                <div class="row px-0 mx-0 pt-2 pb-5">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-3" style="z-index:999;">
                                        <div class="d-flex py-2" >
                                            <label class="font-12 pr-1 text-right">Recordatorio</label>
                                            <input type="checkbox" class="" v-model="datos.rec">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex mx-1">
                                            <label class="font-12 pr-1 py-2">Fecha</label>
                                            <input type="date" class="form-control font-12 form-control-sm" :disabled="datos.rec==false" v-model="datos.fechaRec">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex mx-1">
                                            <label class="font-12 pr-1 py-2">Hora</label>
                                            <input type="time" class="form-control font-11 form-control-sm" :disabled="datos.rec==false" v-model="datos.horaRec">
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <a href="#" class="btn btn-blue btn-sm px-5" @click.prevent="cantclick+=1,registrar()">
                                            <span v-if="loadButton" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Registrar
                                        </a>
                                        <a href="#"  class="btn btn-blue btn-sm ml-2 px-4" @click.prevent="reprogramar()">
                                            <span v-if="loadButton2" class="spinner-border spinner-border-sm pr-2" role="status" aria-hidden="true"></span>
                                            <i class="fa fa-clock pr-1" v-else></i>Reprogramar
                                        </a>
                                    </div>
                                    <div class="col-md-7"></div>
                                    <div class="col-md-5 text-center mt-3">
                                        <div class="alert w-5 fadeInRight" :class="{'alert-success':(mensaje).substr(0,1)=='R','alert-danger':(mensaje).substr(0,1)=='E',}" v-if="mensaje">
                                            <p class="mb-0"><i class="fa pr-2" :class="{'fa-check ':(mensaje).substr(0,1)=='R','fa-times':(mensaje).substr(0,1)=='E',}"></i>{{mensaje}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-warning" v-if="errorsDatos!=''">
                                    <p class="mb-0">Corriga el(los) siguiente(s) error(es):</p>
                                    <ul class="text-left"><li v-for="(error,index) in errorsDatos" :key="index" >{{ error }}</li></ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer text-center py-0">
                        <a href="" class="close btn btn-block" data-dismiss="modal" aria-label="Close">
                            <span class="fa fa-chevron-up"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['recordatorio',"telefonos","cant_contacto","pdps","gestiones","tipoacceso"],      
        data() {
            return {
                respuestas:[],
                ubicabilidad:'',
                fechaActual:null,
                fechaMax:null,
                // telefonos:this.telefonosgenerales,
                errorsDatos:[],
                // pdps:this.datospdp,
                mensaje:'',
                datos:{detalle:'',montoPDP:'',fechaPDP:null,moneda:0,telefono:'',respuesta:'',rec:'',fechaRec:'',horaRec:'',id:'',tel_rec:'',motivoNoPago:''},
                loadButton:false,
                loadButton2:false,
                // cant_contacto:this.valcontacto,
                motivos:[],
                viewMotivo:false,
                cantclick:0,
                // gestiones:this.historico,
            }
        },
        methods:{
            obtenerRespuestas(){
                this.datos.respuesta="";
                if(this.ubicabilidad=="") return;
                this.respuestas=[];
                const idUbi = this.ubicabilidad;
                axios.get("listaRespuesta/" + idUbi).then(res=>{
                    if(res.data){
                        this.respuestas=res.data;
                    }
                })
            },
            listarMotivos(){
                this.motivos=[];
                if(this.tipoacceso!=8){
                    axios.get("motivo/listaMotivosNoPago").then(res=>{
                        if(res.data){
                            this.motivos=res.data;
                            this.viewMotivo=true;
                        }
                    })
                }
            },
            fechaCalendario(rpta){
                if(rpta==2){
                    this.fechaActual=null;
                    this.fechaMax=null;
                }

                if(rpta==1 || rpta==43){
                    const fecha=new Date();
                    let mes=fecha.getMonth()+1;
                    let dia=fecha.getDate();
                    let anio=fecha.getFullYear();
                    let diaMax=dia+7;
                    let ultDia=new Date(anio, mes, 0);
                    mes=mes<10? '0'+mes:mes;
                    dia=dia<10? '0'+dia:dia;
                    /*if(diaMax<=ultDia.getDate()){
                        diaMax=diaMax<10? '0'+diaMax:diaMax;
                    }else{
                        diaMax = ultDia.getDate();
                    }*/
                    diaMax = ultDia.getDate();
                    this.fechaActual=`${anio}-${mes}-${dia}`;
                    this.fechaMax=`${anio}-${mes}-${diaMax}`;
                }
                
                if(rpta!=1 || rpta!=43 || rpta!=2){
                    this.datos.fechaPDP='';
                    this.datos.montoPDP='';
                }
            },
            validacion(){
                this.errorsDatos=[];
                if(!this.datos.telefono){
                    this.errorsDatos.push("Selecciona un número de teléfono");
                }
                if(!this.ubicabilidad){
                    this.errorsDatos.push("Selecciona una ubicabilidad");
                }
                if(!this.datos.respuesta){
                    this.errorsDatos.push("Selecciona una respuesta");
                }
                if(this.datos.respuesta==1 || this.datos.respuesta==2 || this.datos.respuesta==43){
                    if(!this.datos.fechaPDP || !this.datos.montoPDP){
                        this.errorsDatos.push("Selecciona una fecha y/o monto");
                    }
                }
                if(this.datos.respuesta==33 && this.tipoacceso!=8){
                    if(!this.datos.motivoNoPago){
                        this.errorsDatos.push("Selecciona un motivo de no pago");
                    }
                }
                if(!this.datos.detalle){
                    this.errorsDatos.push("Ingresa un detalle de gestión");
                }
                if(this.datos.rec==true){
                    if(!this.datos.fechaRec || !this.datos.horaRec){
                        this.errorsDatos.push("Selecciona una fecha y/o hora de recordatorio");
                    }
                }
            },
            registrar(){
                try{
                    this.mensaje="";
                    this.errorsDatos=[];
                    this.validacion();
                    if(this.errorsDatos.length==0){
                        this.datos.id=this.recordatorio[0].id;
                        this.loadButton=true;
                        // evaluar detalle identicos
                        if(this.cantclick==1){
                            // registrar gestion
                            axios.post("insertarGestion",this.datos).then(res=>{
                                if(res.data[0]=="ok"){
                                    this.loadButton=false;
                                    this.mensaje = "Registro con éxito";
                                    this.$root.$emit('verListaClientes');
                                    this.limpiar();
                                    this.cantclick=0;
                                    if(res.data[1][0].cant>0){
                                        toastr.warning('Se está repitiendo el detalle de gestión', 'Tener en cuenta!',{"progressBar": true,"positionClass": "toast-top-center",});
                                    }
                                    this.$root.$emit('limpiarRecordatorio');
                                    setTimeout(() => {
                                        this.mensaje="";
                                    }, 5000);
                                }
                            });
                        }
                    }else{
                        this.cantclick=0;
                    }
                }catch(error){
                    this.mensaje = "Error al Registrar";
                    setTimeout(() => {
                        this.mensaje="";
                    }, 5000);
                }
            },
            reprogramar(){
                this.errorsDatos=[];
                if(this.recordatorio.length>0){
                    this.datos.tel_rec=this.recordatorio[0].tel_prog;
                    this.datos.id=this.recordatorio[0].id;
                }
                if(this.datos.tel_rec!="" && this.datos.fechaRec!="" && this.datos.horaRec!=""){
                    this.loadButton2=true;
                    axios.post("insertarRecordatorio",this.datos).then(res=>{
                        if(res.data=="ok"){
                            this.loadButton2=false;
                            this.mensaje = "Registro con éxito";
                            this.limpiar();
                            this.$root.$emit('limpiarRecordatorio');
                            setTimeout(() => {
                                this.mensaje="";
                            }, 5000);
                        }
                    });
                }else{
                    this.errorsDatos.push("Selecciona una fecha y/o hora de recordatorio");
                }
            },
            validarRespuestas(res){
                //respuesta 1,43 y 2--limitacion de calendario
                this.fechaCalendario(res);
                //no ingresar paleta no contacto cuando existe contacto previo
                if(res==44 || res==45){
                    if(this.cant_contacto.length>0){
                        if(this.cant_contacto[0][0].cant_contacto>0){
                            this.datos.respuesta='';
                            toastr.warning('No se puede seleccionar la respuesta asignada, el cliente tiene un contacto previo', '',{"progressBar": true,"positionClass": "toast-top-center",});
                            // $("#panel-mensaje").modal();
                        }
                    }
                }
                //no ingresar paleta contacto cuando no existe contacto previo
                if(res==32){
                    if(this.cant_contacto.length>0){
                        if(this.cant_contacto[0][0].cant_contacto==0){
                            this.datos.respuesta='';
                            toastr.warning('No se puede seleccionar la respuesta asignada, el cliente no tiene un contacto previo', '',{"progressBar": true,"positionClass": "toast-top-center",});
                            // $("#panel-mensaje").modal();
                        }
                    }
                }
                //verificar si ya hay compromisos
                if(res==1){
                    if(this.pdps.length>0){
                        const fecha_ges=this.pdps[0][0].fecha_ges;
                        const fecha_pago=this.pdps[0][0].fecha_pag;
                        const monto=this.pdps[0][0].monto;
                        const usuario=this.pdps[0][0].usuario;
                        const mon=this.pdps[0][0].moneda;
                        let moneda='';
                        if(mon==0){moneda="S/.";}else{moneda="$/.";};
                        if(new Date(this.fechaActual).getTime()<=new Date(fecha_pago).getTime()){
                            this.datos.respuesta='';
                            toastr.info('Fecha de Gestión: '+fecha_ges+'.<br>Usuario: '+usuario+'.<br>Fecha de Pago: '+fecha_pago+'.<br>Cantidad: '+moneda+""+monto
                            , 'Ya existe un compromiso de pago',
                            {"progressBar": true,"positionClass": "toast-top-center",});
                        }
                    }
                }
                //visualizar el motivo de no pago - contatcto con titular
                if(res==33){
                    this.datos.motivoNoPago='';
                    this.listarMotivos();
                }else{
                    this.datos.motivoNoPago='';
                    this.viewMotivo=false;
                }
            },
            limpiar(){
                this.datos.detalle='';
                this.datos.montoPDP='';
                this.datos.fechaPDP=null;
                this.datos.moneda=0;
                this.datos.telefono='';
                this.datos.respuesta='';
                this.datos.rec=false;
                this.datos.fechaRec='';
                this.datos.horaRec='';
                this.datos.motivoNoPago='';
                this.ubicabilidad='';  
                this.viewMotivo=false;              
            },
            inactivarUbicabilidad(){
                if(this.datos.telefono==''){
                    this.ubicabilidad='';
                    this.datos.respuesta='';
                }
            },
        },
        components:{
            // formRegistroGestion,
            // detalleGestiones:() => import('./DetalleGestiones'),
            // formRegistroGestion: () => import('./FormRegistrarGestion'),
        },
    }
</script>
