<template>
    <div>
        <div  class="modal" tabindex="-1" role="dialog" id="modalCarga" >
            <div v-if="loadingModal" class="d-flex justify-content-center align-items-center" style="margin-top:150px;">
                <div>
                    <span class="spinner-border spinner-border-xl text-white ml-3" role="status" aria-hidden="true"></span>
                    <p style="font-20px;" class="text-white">Cargando...</p>
                </div>
            </div>
            <!-- <div v-else class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center">
                    <p><i class="fa fa-thumbs-up fa-4x text-white"></i></p>
                    <p style="font-20px;" class="text-white">Campaña Registrada!</p>
                </div>
            </div> -->

        </div>
        <div class="text-center d-flex justify-content-center pt-5" v-if="loadingIn">
           <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
        </div>
        <div v-else class="fadeIn" >
            <div class="form-crear fadeIn" v-if="viewFrm"> 
                <form @submit.prevent="crearCampana" method="POST"> 
                    <div class="row">
                        <div class="col-md-3" >
                            <div class="form-group">
                                <label for="">Nombre de campaña</label>
                                <input type="text" class="form-control input-sb" autofocus v-model="campana.nombre">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Selecciona una cartera</label>
                                <select class="form-control input-sb" v-model="campana.cartera" @change="limpiar()">
                                    <option v-for="(item,index) in carteras" :key="index" :value="item.id" >{{item.cartera}}</option>
                                </select>
                                <small class="form-text text-danger">{{mensaje}}</small>
                            </div>
                        </div>
                        <div class="col-md-3 py-0">
                            <div class="card-inf p-3 border d-flex justify-content-between">
                                <div>
                                    <p class="text-num">{{campana.totalClientes}}</p>
                                    <p>Cant. Clientes</p>
                                </div>
                                <i class="fa fa-users fa-2x text-gray"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-inf p-3 border d-flex justify-content-between">
                                <div>
                                    <p class="text-num">{{campana.totalSms}}</p>
                                    <p>Cant. SMS</p>
                                </div>
                                <i class="fa fa-envelope fa-2x text-gray"></i>
                            </div>
                        </div>
                    </div>
                    <a href="" @click.prevent="viewCondicion()" class="btn btn-outline-blue"><i class="fa fa-plus fa-xs pr-2"></i>Añadir Criterio</a>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead class="bg-blue text-white">
                                <tr>
                                    <td style="min-width: 2rem;" class="text-center"></td>
                                    <td style="vertical-align:top;text-align:center;" width="25%">Detalle Criterio</td>
                                    <td style="vertical-align:top;text-align:center;" width="25%">Speech</td>
                                    <td style="vertical-align:top;text-align:center;">Enviar a</td>
                                    <td style="vertical-align:top;text-align:center;">Número Contacto</td>
                                    <td style="vertical-align:top;text-align:center;">Cant. de Clientes</td>
                                    <td style="vertical-align:top;text-align:center;">Cant. de SMS</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="campana.detalle==''">
                                    <td colspan="7" class="text-center">Sin datos</td>
                                </tr>
                                <tr v-for="(item,index) in campana.detalle" :key="index" v-else>
                                    <td style="vertical-align:top;min-width: 2.5rem;" class="text-center">
                                        <a href="" @click.prevent="eliminarCondicion(index)" class="btn-outline-green rounded py-1 px-2"><i class="fa fa-times fa-xs"></i></a>
                                    </td>
                                    <td style="vertical-align:top;">
                                        <a class="text-dark text-left" data-toggle="collapse" :href="'#n'+index" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <b>{{item.nombre}}</b>
                                        </a>
                                        <div class="collapse" :id="'n'+index">
                                            <div class="alert alert-info">
                                                {{item.textcondicion}}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align:top;">
                                        <a class="text-dark text-left" data-toggle="collapse" :href="'#s'+index" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            {{item.nomSpeech}}
                                        </a>
                                        <div class="collapse" :id="'s'+index">
                                            <div class="alert alert-info">
                                                {{item.descSpeech}}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align:top;">{{item.destino}}</td>
                                    <td style="vertical-align:top;">{{item.nomNumeros}}</td>
                                    <td style="vertical-align:top;text-align:center;">{{item.cantClientes}}</td>
                                    <td style="vertical-align:top;text-align:center;">{{item.cantSms}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pt-4" >
                            <div class="form-group">
                                <label for="">Fecha de envío</label>
                                <input type="date" class="form-control input-sb"  v-model="campana.fecha" :min="fechaActual">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger fadeInCont px-3"  v-if="errors.length">
                            <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                            <ul class="px-3"><li v-for="(error,index) in errors" :key="index" >{{ error }}</li></ul>
                    </div>
                    <button type="submit"  class="btn bg-blue mt-3 text-white waves-effect">Crear Campaña</button>
                </form>
            </div>

            <!-- criterios -->
            <div class="form-criterios fadeInCont" v-else>
                        <div class="card-inf px-2 py-2">
                            <div class="d-flex ">
                                <a href="" @click.prevent="viewCampana()" class="text-left text-dark" style="text-decoration:none;"><i class="fa fa-reply fa-lg pr-3"></i></a><br>
                                <b style="font-size:14px;" class="pt-1">Nuevo Criterio</b>
                            </div><br><hr>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group w-50">
                                        <label for="">Ingresa un nombre</label>
                                        <input type="text" class="form-control input-sb" required autofocus v-model="condicion.nombre">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <p><b>1. Mensaje(s) enviado(s) a: </b></p>
                                        <select class="selectpicker form-control " title="..."  v-model="condicion.destinatario">
                                            <option value="1">Todos los clientes</option>
                                            <option value="2">Clientes nuevos</option>
                                            <option value="3">Clientes sin gestión en el mes</option>
                                        </select>
                                    </div>   
                                </div>
                            </div><br><br><br>
                            <p><b>2. Selecciona criterios </b></p>
                            <div class="row mt-3">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Ubicabilidad</label>
                                        <select class="selectpicker form-control input-sb" data-actions-box="true"  multiple title="..." v-model="ubicabilidad" @change="listaRespuestas(ubicabilidad)" >
                                            <option value="0">Contacto</option>
                                            <option value="1">No Contacto</option>
                                            <option value="2">No Disponible</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="">Respuesta</label>
                                        <select class="selectpicker form-control " data-actions-box="true"  multiple title="..." v-model="selectrespuestas" :disabled="rpta==true"  data-selected-text-format="count > 3">
                                            <option v-for="(item,index) in respuestas" :key="index" :value="item.respuesta">{{item.respuesta}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row frmmonto">
                                <div class="col-md-4 ">
                                    <label for="">Capital</label>
                                    <div class="form-group d-flex">    
                                            <input type="text" class="form-control input-sb px-0"  value=">=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb " v-model="condicion.capital1" >
                                            <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb flex-wrap" v-model="condicion.capital2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Deuda</label>
                                    <div class="form-group d-flex">    
                                            <input type="text" class="form-control input-sb px-0"  value=">=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb " v-model="condicion.deuda1" >
                                            <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb flex-wrap" v-model="condicion.deuda2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Importe</label>
                                    <div class="form-group d-flex">    
                                            <input type="text" class="form-control input-sb px-0"  value=">=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb " v-model="condicion.importe1" >
                                            <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                            <input type="text" class="form-control input-sb flex-wrap" v-model="condicion.importe2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" v-if="scors.length">
                                    <div class="form-group">
                                        <label for="">Score</label>
                                        <select v-model="dscore" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!scors.length">
                                            <option v-for="(item,index) in scors" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-3" v-if="entidades.length">
                                    <div class="form-group">
                                        <label for="">Entidades</label>
                                        <select v-model="dentidades" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!entidades.length">
                                            <option v-for="(item,index) in entidades" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-3" v-if="prioridades.length">
                                    <div class="form-group">
                                        <label for="">Prioridad</label>
                                        <select v-model="dprioridades" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!prioridades.length">
                                            <option v-for="(item,index) in prioridades" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-3" v-if="tramos.length">
                                    <div class="form-group">
                                        <label for="">Tramo</label>
                                        <select v-model="dtramos" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!tramos.length">
                                            <option v-for="(item,index) in tramos" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-3" v-if="zonas.length">
                                    <div class="form-group">
                                        <label for="">Zona</label>
                                        <select v-model="dzonas" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!zonas.length">
                                            <option v-for="(item,index) in zonas" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-3" v-if="descuentos.length && campana.cartera==9">
                                    <div class="form-group">
                                        <label for="">Campaña</label>
                                        <select v-model="ddescuentos" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!descuentos.length">
                                            <option v-for="(item,index) in descuentos" :key="index" :value="item.val">{{item.valor}}</option>
                                        </select>
                                    </div>   
                                </div>
                            </div><br><br><br>
                            <div class="row mt-3">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <p><b>3. Números a enviar:</b></p>
                                        <select class="selectpicker form-control " title="..." v-model="condicion.numero">
                                            <option value="1">Número última gestión</option>
                                            <option value="2">Todos los números</option>
                                        </select>
                                    </div>   
                                </div>
                            </div>
                            
                            <br><br>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p><b>4. Selecciona un Speech:</b></p>
                                     <div class="form-group">
                                        <select class="selectpicker form-control input-sb" title="..." @change="accionesSpeech(speech.valor)" v-model="speech.valor" data-live-search="true">
                                            <option data-divider="true"></option>
                                            <option value="0" data-icon="fa fa-plus">Crear Speech</option>
                                            <option data-divider="true"></option>
                                            <option v-for="(item,index) in speechs" :key="index" :value="item.id">{{item.nombre}}</option>
                                        </select>
                                     </div>
                                </div>
                            </div>
                            <br><br>

                            <div>
                                <div class="text-center d-flex justify-content-center py-2" v-if="cargaSpeech">
                                    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                                </div>
                                <div class="alert alert-success"  v-else-if="msjSpeech">
                                    <p>{{msjSpeech}}</p>
                                </div>
                            
                                <div v-if="crearSpeech" class="alert bg-celeste pt-4">
                                    <form @submit.prevent="agregarSpeech" method="POST">  
                                        <div class="form-group ">
                                            <label for="">Nombre a speech</label>
                                            <input type="text" class="form-control input-sb w-50 bg-transparente" v-model="speech.nombre" id="nombreSpeech">
                                        </div>
                                        <div class="overflow-auto pb-4 pt-2">
                                            <div class="text-center d-flex justify-content-center " v-if="loading">
                                                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                                            </div>
                                            <div v-else>
                                                <div v-if="etiquetas!=''" class="d-flex">
                                                    <a href="" v-for="(item,index) in etiquetas" :key="index" @click.prevent="addEtiqueta(index,item.cant,item.nombre)" class="btn btn-outline-blue ml-2">{{item.nombre}}</a>                   
                                                </div>
                                                <div v-else class="text-center d-flex justify-content-center">
                                                    <p class="mb-0">Sin datos</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p>Escribe un speech (Max. 160 caractéres)</p>
                                        <textarea class="form-control" rows="3" v-model="speech.texto" id="mostrarhtml" :maxlength="maxleng" @keyup="cantcaracteres"></textarea><br>
                                        <small>{{caracteres}} caractéres</small><br><br><br>

                                        <div class="alert alert-danger fadeInCont px-3"  v-if="errorsSpeech.length" style="line-height:18px;">
                                                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                                                <ul class="px-3"><li v-for="(error,index) in errorsSpeech" :key="index" >{{ error }}</li></ul>
                                        </div>
                                        <button type="submit" class="btn btn-outline-green">Crear Speech</button>
                                        <br><br><br><br>
                                    </form>
                                </div> 
                            </div>
                            <div v-if="viewSpeech">
                                <div class="alert alert-info">
                                    <p style="line-height:18px;">
                                        <b>Detalle del Speech</b><br><br>
                                        {{vspeech.desc}}
                                    </p>
                                </div>  <br><br>
                            </div>

                            <div class="alert alert-danger fadeInCont px-3"  v-if="errorsCondicion.length" style="line-height:18px;">
                                    <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                                    <ul class="px-3"><li v-for="(error,index) in errorsCondicion" :key="index" >{{ error }}</li></ul>
                            </div>
                            <div class="alert alert-danger fadeInCont fadeOut px-3"  v-if="mensajeCant"><p class="pt-2">{{mensajeCant}}</p></div>
                            <a href="" @click.prevent="agregarCondicion()" id="btnAgregarCriterio" class="btn btn-blue  waves-effect">Agregar Criterio</a><br><br>
                               
                        </div>
                    </div>
        </div>
    </div>
</template>

<script>

    export default {
        props:['carteras','rol'],
        data() {
            return {
                loadingIn : false,
                loading:false,
                loadingModal:false,
                color:'#3367d6',
                color2:'#9CDDEB',
                colorModal:'#ec7c48',
                datos:{nombre:''},
                errors:[],
                errorsSpeech:[],
                errorsCondicion:[],
                condiciones:[],
                // carteras:[],
                speechs:[],
                respuestas:[],
                etiquetas:[],
                entidades:[],
                prioridades:[],
                scors:[],
                zonas:[],
                tramos:[],
                descuentos:[],
                condicion:{nombre:'',numero:'1',capital1:'',deuda1:'',importe1:'',capital2:'',deuda2:'',importe2:'',destinatario:'1'},
                ubicabilidad:[],
                selectrespuestas:[],
                speech:{valor:'',texto:'',nombre:'',cartera:''},
                vspeech:{nombre:'',desc:'',id:''},
                campana:{nombre:'',cartera:'',detalle:[],totalClientes:0,totalSms:0,fecha:''},
                viewFrm:true,
                viewSpeech:false,
                crearSpeech:false,
                mensaje:'',
                cargaSpeech:false,
                msjSpeech:'',
                rpta:true,
                datosClientes:[],
                dentidades:[],
                dprioridades:[],
                dscore:[],
                dzonas:[],
                dtramos:[],
                ddescuentos:[],
                detalleCondicion:"",
                sqlCondicion:"",
                mensajeCant:"",
                fechaActual:"",
                caracteres:0,
                tempspeech:"",
                tempcaracter:0,
                maxleng:160,
            }
        },
        created(){
            this.hoy()
        },
        methods:{
            hoy(){
                var f = new Date();
                var dia= f.getDate();
                var mes=(f.getMonth() +1);
                if(dia<10)
                    dia='0'+dia; //agrega cero si el menor de 10
                if(mes<10)
                    mes='0'+mes //agrega cero si el menor de 10
                this.fechaActual=f.getFullYear() + "-" + mes + "-" + dia;
            },
            viewCondicion(){
                if(this.campana.cartera!=0){
                    this.limpiarCamposCondiciones();
                    this.viewFrm=false;   
                    this.loadingIn=true;
                    this.mensaje='';
                    this.mensajeCant='';
                    axios.get("carteraSpeech/"+this.campana.cartera).then(res=>{
                        if(res.data){
                            this.speechs=res.data;
                            this.loadingIn=false;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/score").then(res=>{
                        if(res.data){
                            this.scors=res.data;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/entidades").then(res=>{
                        if(res.data){
                            this.entidades=res.data;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/zona").then(res=>{
                        if(res.data){
                            this.zonas=res.data;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/prioridad").then(res=>{
                        if(res.data){
                            this.prioridades=res.data;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/tramo").then(res=>{
                        if(res.data){
                            this.tramos=res.data;
                        }
                    })
                    axios.get("tagCondicion/"+this.campana.cartera+"/descuento").then(res=>{
                        if(res.data){
                            this.descuentos=res.data;
                        }
                    })
                }else{
                    this.mensaje="Selecciona una cartera";
                }
                
            },
            viewCampana(){
                this.viewFrm=true;
                this.crearSpeech=false;
                this.viewSpeech=false;  
                this.speech.valor='';
            },
            accionesSpeech(option){
                if(option==0){
                    this.crearSpeech=true;
                    this.viewSpeech=false;
                    this.loading=true;
                    axios.get("listEtiquetas").then(res=>{
                        if(res.data){
                            this.etiquetas=res.data;
                            this.loading=false;
                        }
                    })
                   $('#nombreSpeech').focus(); 
                   $('#btnAgregarCriterio').attr("disabled", true);

                }else{
                    this.crearSpeech=false;
                    this.viewSpeech=true;
                    for(var i=0;i<this.speechs.length;i++){
                        if(this.speechs[i].id==option){
                            this.vspeech.nombre=this.speechs[i].nombre;
                            this.vspeech.desc=this.speechs[i].descripcion;
                            this.vspeech.id=this.speechs[i].id;
                        }
                    }
                    $('#btnAgregarCriterio').attr("disabled", false);
                }
            },
            reemplazarEtiquetas(){
                this.tempspeech=this.speech.texto;
                for(var i=0;i<this.etiquetas.length;i++){
                    this.tempspeech=this.tempspeech.replace(this.etiquetas[i].nombre,"");
                    if(this.speech.texto.indexOf(this.etiquetas[i].nombre)!==-1){
                        this.tempcaracter+=parseInt(this.etiquetas[i].cant);
                    }
                }
            },
            addEtiqueta(index,cant,nombre){
                //this.etiquetas.splice(index, 1);
                this.tempcaracter=0;
                if(this.caracteres+parseInt(cant)<=160){
                    this.speech.texto+=nombre;
                    this.reemplazarEtiquetas();
                    this.caracteres=this.tempspeech.length+this.tempcaracter; 
                }
                $("#mostrarhtml").focus();
                
            },
            listaRespuestas(ubi){
                this.rpta=true;
                this.respuestas="";
                if(ubi.length!=0){
                    axios.get("listaRespuestaSms/"+ubi).then(res=>{
                        if(res.data){
                            this.respuestas=res.data;
                            this.rpta=false;
                            this.loadingIn=false;
                        }
                    })
                }

            },
            totales(){
                this.campana.totalClientes=0;
                this.campana.totalSms=0;
                for(var i=0; i<this.campana.detalle.length;i++){
                    this.campana.totalClientes=this.campana.totalClientes+parseInt(this.campana.detalle[i].cantClientes);
                    this.campana.totalSms=this.campana.totalSms+parseInt(this.campana.detalle[i].cantSms);

                 } 
            },
            armarCondicion(){
               if(this.ubicabilidad.length!=0){
                   var rspts=""
                   for(var i=0;i<this.ubicabilidad.length;i++){
                       if(this.ubicabilidad[i]==0){
                           rspts+="'CONTACTO',";
                       }
                       if(this.ubicabilidad[i]==1){
                           rspts+="'NO CONTACTO',";
                       }
                       if(this.ubicabilidad[i]==2){
                           rspts+="'NO DISPONIBLE',";
                       }   
                   }
                   this.sqlCondicion+="ubicabilidad IN ("+rspts.substr(0,rspts.length-1)+") AND ";
                   this.detalleCondicion+="Ubicabilidad: "+rspts.substr(0,rspts.length-1)+"; ";
               }
               if(this.selectrespuestas.length!=0){
                   var rspts="'"
                   for(var i=0;i<this.selectrespuestas.length;i++){
                       rspts+=this.selectrespuestas[i]+"','";
                   }
                   this.sqlCondicion+="respuesta IN ("+rspts.substr(0,rspts.length-2)+") AND ";
                   this.detalleCondicion+="Respuesta: "+rspts.substr(0,rspts.length-2)+"; ";
               }
               if(this.dscore.length!=0){
                   var valor="'"
                   for(var i=0;i<this.dscore.length;i++){
                       valor+=this.dscore[i]+"','";
                   }
                   this.sqlCondicion+="score IN ("+valor.substr(0,valor.length-2)+") AND ";
                   this.detalleCondicion+="Score: "+valor.substr(0,valor.length-2)+"; ";
               }
               if(this.dentidades.length!=0){
                   var valor="'"
                   for(var i=0;i<this.dentidades.length;i++){
                       valor+=this.dentidades[i]+"','";
                   }
                   this.sqlCondicion+="entidades IN ("+valor.substr(0,valor.length-2)+") AND ";
                   this.detalleCondicion+="Entidades: "+valor.substr(0,valor.length-2)+"; ";
               }
               if(this.dprioridades.length!=0){
                   var valor="'"
                   for(var i=0;i<this.dprioridades.length;i++){
                       valor+=this.dprioridades[i]+"','";
                   }
                   this.sqlCondicion+="prioridad IN ("+valor.substr(0,valor.length-2)+") AND ";
                   this.detalleCondicion+="Prioridad: "+valor.substr(0,valor.length-2)+"; ";
               }
               if(this.dtramos.length!=0){
                   var valor="'";
                   var valor2="";
                   var desval="";
                   var valor3=""
                   for(var i=0;i<this.dtramos.length;i++){
                        if(this.dtramos[i]=='<= 2016'){
                           valor2=" tramo <=2016 OR ";
                           desval="'<=2016'";
                        }else{
                            valor+=this.dtramos[i]+"','";
                        }
                   }
                   
                   this.sqlCondicion+="("+valor2;
                   if(valor!="'"){
                       this.sqlCondicion+="tramo IN ("+valor.substr(0,valor.length-2)+") "
                    };
                   this.sqlCondicion+=") AND ";
                   this.detalleCondicion+="Tramo: "+desval+" "+valor.substr(0,valor.length-2)+"; ";
                   
               }
               if(this.dzonas.length!=0){
                   var valor="'"
                   for(var i=0;i<this.dzonas.length;i++){
                       valor+=this.dzonas[i]+"','";
                   }
                   this.sqlCondicion+="departamento IN ("+valor.substr(0,valor.length-2)+") AND ";
                   this.detalleCondicion+="Departamento: "+valor.substr(0,valor.length-2)+"; ";
               }
                if(this.ddescuentos.length!=0){
                   var valor="'"
                   for(var i=0;i<this.ddescuentos.length;i++){
                       valor+=this.ddescuentos[i]+"','";
                   }
                   this.sqlCondicion+="nom_dscto IN ("+valor.substr(0,valor.length-2)+") AND ";
                   this.detalleCondicion+="Dsctos: "+valor.substr(0,valor.length-2)+"; ";
               }
                if(this.condicion.capital1!=''  && this.condicion.capital2!='' ){
                    this.sqlCondicion+="(total_capital between "+this.condicion.capital1+" and "+this.condicion.capital2+") AND " ;
                   this.detalleCondicion+=" Capital: ["+this.condicion.capital1+" - "+this.condicion.capital2+"] " ;
                }

                if(this.condicion.deuda1!=''  && this.condicion.deuda2!='' ){
                    this.sqlCondicion+="(total_mora_dolar between "+this.condicion.deuda1+" and "+this.condicion.deuda2+") AND " ;
                   this.detalleCondicion+=" Deuda: ["+this.condicion.deuda1+" - "+this.condicion.deuda2+"] " ;
                }

                if(this.condicion.importe1!=''  && this.condicion.importe2!='' ){
                    this.sqlCondicion+="(total_importe between "+this.condicion.importe1+" and "+this.condicion.importe2+") AND " ;
                   this.detalleCondicion+=" Importe: ["+this.condicion.importe1+" - "+this.condicion.importe2+"] " ;
                }

               this.sqlCondicion=this.sqlCondicion.substr(0,this.sqlCondicion.length-4);
               this.detalleCondicion=this.detalleCondicion.substr(0,this.detalleCondicion.length-2);
               
            },
            agregarCondicion(){
               this.errorsCondicion=[];
               this.mensajeCant="";
               this.validarCamposCondicion();
               var cantNumClientes=0;
               var cantSmsClientes=0;
               //var codCLientes="";
               var nomNumero="";
               var opcionDestinatario="";
               var parametros=[];
               this.sqlCondicion="";
               this.detalleCondicion="";
               this.respuestas=[];
               if(this.errorsCondicion.length==0){
                   //gregar condiciones
                   this.armarCondicion();
                   //seleccionar numeros
                   if(this.condicion.numero==2){
                       nomNumero="Todos los números"
                   }else{
                       nomNumero="Número última gestión"
                   }
                   //seleccionar destinatario
                    if(this.condicion.destinatario==1){
                        opcionDestinatario="Todos los clientes";
                    }
                    if(this.condicion.destinatario==2){
                        opcionDestinatario="Clientes nuevos";
                    }
                    if(this.condicion.destinatario==3){
                        opcionDestinatario="Clientes sin gestión en el mes";
                    }
                   //visualizar modal de carga
                    this.loadingModal=true;
                    $('#modalCarga').modal({backdrop: 'static', keyboard: false});
                    
                    //agrupar dtos
                    parametros={
                        cartera:this.campana.cartera,
                        condiciones:this.sqlCondicion,
                        opcion:this.condicion.numero,
                        destinatario:this.condicion.destinatario
                    }

                    //consultar al controldor
                    axios.post("datosclientesCampana",parametros).then(res=>{
                        if(res.data){
                            //recibir datos del controlador
                            this.datosClientes=res.data;
                            
                            //cantidad de mensjaes relacinados a las condiciones seleccionadas
                            cantNumClientes=this.datosClientes[0].cant_cli;
                            cantSmsClientes=this.datosClientes[0].cant_sms;
                            //codCLientes=this.datosClientes[0].codigos;
                            
                            if(cantSmsClientes>0){
                                //agregar criterio
                                this.campana.detalle.push({nombre:this.condicion.nombre,
                                        textcondicion:this.detalleCondicion,
                                        baseCondicion:this.sqlCondicion,
                                        codNumeros:this.condicion.numero,
                                        nomNumeros:nomNumero,
                                        idSpeech:this.vspeech.id,
                                        nomSpeech:this.vspeech.nombre,
                                        descSpeech:this.vspeech.desc,
                                        destino:opcionDestinatario,
                                        idDestino:this.condicion.destinatario,
                                        //codigos:codCLientes.toString(),
                                        cantClientes:cantNumClientes,
                                        cantSms:cantSmsClientes
                                });
                                this.viewFrm=true;  
                                this.totales();
                            }else{
                                this.mensajeCant="No se encontraron clientes asociados a los criterios seleccionados"
                            }

                            //cerrar modal de carga
                            this.loadingModal=false;
                            $('#modalCarga').modal('hide');
                            setTimeout(() => this.mensajeCant="", 6000);
                        }
                    })
                   
               }

            },
            eliminarCondicion(index){
                this.campana.detalle.splice(index, 1);
                this.totales();
            },
            validarCampos(){
                this.errors=[];
                if(!this.campana.nombre){
                    this.errors.push("Ingrese un nombre a la campaña");
                }
                if(!this.campana.cartera){
                    this.errors.push("Selecciona una cartera");
                }
                if(this.campana.detalle.length==0){
                    this.errors.push("Agrege uno o más criterios");
                }
                if(!this.campana.fecha){
                    this.errors.push("Selecciona una fecha de envío");
                }
                setTimeout(() => this.errors=[], 5000);
            },
            validarCamposSpeech(){
                this.errorsSpeech=[];
                if(!this.speech.nombre){
                    this.errorsSpeech.push("Coloquele un nombre a su speech");
                }
                if(!this.speech.texto){
                    this.errorsSpeech.push("Ingrese un speech");
                }
            },
            validarCamposCondicion(){
                this.errorsCondicion=[];
                if(!this.condicion.nombre){
                    this.errorsCondicion.push("Ingrese un nombre");
                }
                if(!this.speech.valor || this.speech.valor==0){
                    this.errorsCondicion.push("Seleccione un speech");
                }
                setTimeout(() => this.errorsCondicion=[], 5000);
            },
            agregarSpeech(){
                this.errorsSpeech=[];
                this.validarCamposSpeech();
                this.speech.cartera=this.campana.cartera
                if(this.errorsSpeech.length==0){
                    this.cargaSpeech=true;
                    this.crearSpeech=false;
                    this.msjSpeech="";
                    this.speech.texto=this.eliminarAcentos(this.speech.texto);
                    axios.post("insertSpeech",this.speech).then(res=>{
                        if(res.data=="ok"){
                            axios.get("carteraSpeech/"+this.campana.cartera).then(res=>{
                                if(res.data){
                                    this.speechs=res.data;
                                    this.cargaSpeech=false;
                                    this.msjSpeech="El Speech se creó correctamente";
                                    this.speech.valor='';
                                    setTimeout(() => this.msjSpeech="", 4800);
                                }
                            })

                        }
                    })
                }
            },

            limpiarCamposCondiciones(){
                this.speech.nombre="";
                this.speech.texto="";
                this.speech.valor="";

                this.condicion.nombre="";
                this.condicion.numero="1";
                this.condicion.destinatario="1";
                this.condicion.capital1="";
                this.condicion.capital2="";
                this.condicion.deuda1="";
                this.condicion.deuda2="";
                this.condicion.importe1="";
                this.condicion.importe2="";
                this.dentidades=[];
                this.dprioridades=[];
                this.dscore=[];
                this.dzonas=[];
                this.dtramos=[];
                this.ubicabilidad=[];
                this.selectrespuestas=[];
                
                this.viewSpeech=false;
                this.crearSpeech=false;
            },
            limpiarCamposCampana(){
                this.campana.nombre="";
                this.campana.cartera="";
                this.campana.detalle=[];
                this.campana.fecha="";
                this.campana.totalClientes=0;
                this.campana.totalSms=0;
            },
            crearCampana(){
                this.errors=[];
                this.validarCampos();
                this.loadingModal=true;
                if(this.errors.length==0){
                    $('#modalCarga').modal({backdrop: 'static', keyboard: false});
                    axios.post("insertCampana",this.campana).then(res=>{
                        if(res.data=="ok"){
                            this.limpiarCamposCampana();
                            this.loadingModal=false;
                            // $('#modalCarga').modal('hide');
                            setTimeout(() => $('#modalCarga').modal('hide'), 400);
                            toastr.info('Campaña registrada con éxito', 'Registro Exitoso!',{"progressBar": true,"positionClass": "toast-bottom-right",});

                        }
                    })
                }
            },
            limpiar(){
                this.campana.detalle=[];
                this.campana.totalClientes=0;
                this.campana.totalSms=0;
            },
            cantcaracteres(){
                this.tempcaracter=0;
                this.reemplazarEtiquetas();
                this.caracteres=this.tempspeech.length+this.tempcaracter; 
                if(this.caracteres>=160){
                    this.maxleng=this.speech.texto.length;
                }
            },
            eliminarAcentos(str){
                return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            } 
        },  
        
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        }
   
    }
</script>
