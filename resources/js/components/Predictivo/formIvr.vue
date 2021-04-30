<template>
    <div >
        <!-- datos de campaña -->
        <div class="">
            <!-- <p class="font-bold text-blue"><b>Datos de Campaña</b></p> -->
            <div class="row" v-if="tab!=3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Seleccionar Cartera</label>
                        <select class="selectpicker form-control" v-model="registro.cartera" @change="addCondiciones(registro.cartera)">
                            <option value="">Seleccionar</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Nombre de la campaña</label>
                        <input type="text" class="form-control" v-model="registro.campana">
                    </div>
                </div> -->
            </div>
        </div>
        <!-- criterios de seleccion -->
        <div class="">
            <p class="font-bold text-blue pt-2"><b>Criterios de Selección</b></p>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a @click="seleccionTab(1)" class="nav-item nav-link active text-dark" id="nav-criterios-tab" data-toggle="tab" href="#nav-criterios" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fa fa-filter pr-1"></i>Selección de Criterios</a>
                    <a @click="seleccionTab(2)" class="nav-item nav-link text-dark" id="nav-codigos-tab" data-toggle="tab" href="#nav-codigos" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fa fa-file-alt pr-1"></i>Ingresar Códigos</a>
                    <a @click="seleccionTab(3)" class="nav-item nav-link text-dark" id="nav-codigos-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fa fa-cloud-upload-alt pr-1"></i>Cargar Data</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active py-3" id="nav-criterios" role="tabpanel" aria-labelledby="nav-cartera-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <p class="font-bold">1. Tipo de Clientes:</p>
                                <select class="selectpicker form-control " title="Seleccionar"  v-model="detalle.tipo_cliente">
                                    <option value="1">Todos los clientes</option>
                                    <option value="2">Clientes nuevos</option>
                                    <option value="3">Clientes sin gestión en el mes</option>
                                </select>
                            </div>   
                        </div>
                    </div>
                    <p class="font-bold">2. Tipo de Cuentas</p>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Ubicabilidad</label>
                                <select class="selectpicker form-control input-sb" data-actions-box="true"  multiple title="Seleccionar" v-model="detalle.ubicabilidad" @change="listaRespuestas(detalle.ubicabilidad)" >
                                    <option value="0">Contacto</option>
                                    <option value="1">No Contacto</option>
                                    <option value="2">No Disponible</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Respuesta</label>
                                <select class="selectpicker form-control " data-actions-box="true"  multiple title="..." v-model="detalle.respuesta" :disabled="detalle.ubicabilidad==''"  data-selected-text-format="count > 3">
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
                                    <input type="text" class="form-control input-sb " v-model="detalle.capital_i" >
                                    <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                    <input type="text" class="form-control input-sb flex-wrap" v-model="detalle.capital_f">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">Deuda</label>
                            <div class="form-group d-flex">    
                                    <input type="text" class="form-control input-sb px-0"  value=">=" style="width:10%" disabled>
                                    <input type="text" class="form-control input-sb " v-model="detalle.deuda_i" >
                                    <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                    <input type="text" class="form-control input-sb flex-wrap" v-model="detalle.deuda_f">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">Importe</label>
                            <div class="form-group d-flex">    
                                    <input type="text" class="form-control input-sb px-0"  value=">=" style="width:10%" disabled>
                                    <input type="text" class="form-control input-sb " v-model="detalle.importe_i" >
                                    <input type="text" class="form-control input-sb flex-wrap px-0"  value="<=" style="width:10%" disabled>
                                    <input type="text" class="form-control input-sb flex-wrap" v-model="detalle.importe_f">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" v-if="scors.length">
                            <div class="form-group">
                                <label for="">Score</label>
                                <select v-model="detalle.score" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!scors.length">
                                    <option v-for="(item,index) in scors" :key="index" :value="item.valor">{{item.valor}}</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-3" v-if="entidades.length">
                            <div class="form-group">
                                <label for="">Entidades</label>
                                <select v-model="detalle.entidad" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!entidades.length">
                                    <option v-for="(item,index) in entidades" :key="index" :value="item.valor">{{item.valor}}</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-3" v-if="prioridades.length">
                            <div class="form-group">
                                <label for="">Prioridad</label>
                                <select v-model="detalle.prioridad" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!prioridades.length">
                                    <option v-for="(item,index) in prioridades" :key="index" :value="item.valor">{{item.valor}}</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-3" v-if="tramos.length">
                            <div class="form-group">
                                <label for="">Tramo</label>
                                <select v-model="detalle.tramo" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!tramos.length">
                                    <option v-for="(item,index) in tramos" :key="index" :value="item.valor">{{item.valor}}</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-3" v-if="zonas.length">
                            <div class="form-group">
                                <label for="">Zona</label>
                                <select v-model="detalle.zona" class="selectpicker form-control " data-actions-box="true"  multiple title="..."  data-selected-text-format="count > 3" :disabled="!zonas.length">
                                    <option v-for="(item,index) in zonas" :key="index" :value="item.valor">{{item.valor}}</option>
                                </select>
                            </div>   
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade pb-3" id="nav-codigos" role="tabpanel" aria-labelledby="nav-cartera-tab">
                    <textarea class="form-control" rows="10" v-model="detalle.codigos"></textarea>
                    <small>Ingresar codigos separados por ","</small>
                </div>
                <div class="tab-pane fade pb-3" id="nav-data" role="tabpanel" aria-labelledby="nav-cartera-tab">
                    <input type="file" class="form-control" accept=".xlsx" @change="obtenerArchivo">
                    <small class="px-3">Estructura de carga: Código | Número | Departamento</small>
                </div>
            </div>
        </div>
        <!-- tipo de números -->
        <div class="" v-if="tab!=3">
            <p class="font-bold text-blue pt-2"><b>Tipo de Números</b></p>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="selectpicker form-control " title="Seleccionar" v-model="detalle.tipo_numeros">
                            <option value="1">Número última gestión</option>
                            <option value="2">Todos los números</option>
                        </select>
                    </div>   
                </div>
            </div>
        </div>
        <!-- inhibir codigos -->
        <div class="" v-if="tab!=3">
            <p class="font-bold text-blue pt-2"><b>Inhibir</b></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check pb-3">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="1" v-model="detalle.inhibir_paletas">Paletas de Gestión
                        </label>
                    </div>
                    <!-- <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="1" v-model="detalle.gestion_dia">Clientes Gestionados en el día
                        </label>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- errores -->
        <div v-if="errores!=''" class="alert alert-warning"> 
            <p class="font-bold">Mensaje del Sistema</p>
            <ul>
                <li v-for="(item,index) in errores" :key="index">{{item}}</li>
            </ul>
        </div>
        <!-- botones -->
        <div class="my-3">
            <a href="" @click.prevent="calcularCampana()" class="btn btn-outline-blue">
                Calcular Campaña
            </a>
            <a href="" @click.prevent="limpiar()" class="btn btn-outline-blue">Limpiar</a>
        </div>

        <!-- modal detalle -->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalDetalle" >
            <div v-if="loadingModal" class="d-flex justify-content-center align-items-center" style="margin-top:150px;">
                <div>
                    <span class="spinner-border spinner-border-xl text-white ml-3" role="status" aria-hidden="true"></span>
                    <p style="font-20px;" class="text-white">Cargando...</p>
                </div>
            </div>
            <div v-else class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewResultados">
                    <p class="text-white font-30 mb-0">{{resultados}}</p>
                    <p class="text-white">registro(s) aprox.</p>
                    <a href="" v-if="spinnerRegistrar==false" class="btn btn-white" @click.prevent="cancelar()">Cancelar</a>
                    <a href="" v-if="resultados!=0" class="btn btn-danger" @click.prevent="registrar()">
                        <span v-if="spinnerRegistrar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Registrar Campaña
                    </a>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-thumbs-up fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Campaña Registrada con Éxito!</p>
                    <p class="text-white">Asignar registros al usuario {{usuario}}</p>
                    <a href="" v-if="spinnerDescargar==false" class="btn btn-white" @click.prevent="cancelar()">Cancelar</a>
                    <a href="" class="btn btn-primary" @click.prevent="descargar()">
                        <span v-if="spinnerDescargar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Asignar y Descargar Archivo
                    </a>
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
                spinnerRegistrar:false,
                viewResultados:false,
                registro:{cartera:'',fechaInicio:'',fechaFin:'',usuario:'',campana:'',detalle:''},
                detalle:{cartera:'',tipo_cliente:'1',tipo_numeros:'1',ubicabilidad:[],respuesta:[],capital_i:'',capital_f:'',deuda_i:'',deuda_f:'',importe_i:'',importe_f:'',score:[],entidad:[],zona:[],prioridad:[],tramo:[],gestion_dia:'',codigos:'',inhCodigos:'',inhibir_paletas:1},
                bloquear:false,
                respuestas:[],
                scors:[],
                entidades:[],
                zonas:[],
                prioridades:[],
                tramos:[],
                resultados:0,
                loadingModal:true,
                usuario:'',
                idCampana:'',
                spinnerDescargar:false,
                errores:[],
                tab:'',
                archivo:''
            }
        },
        methods:{
            listaRespuestas(ubi){
                this.respuestas="";
                if(ubi.length!=0){
                    axios.get("listaRespuestaSms/"+ubi).then(res=>{
                        if(res.data){
                            this.respuestas=res.data;
                        }
                    })
                }
            },
            addCondiciones(cartera){
                this.scors=[];
                this.entidades=[];
                this.zonas=[];
                this.prioridades=[];
                this.tramos=[];
                if(cartera!=0){
                    axios.get("condiciones/"+cartera).then(res=>{
                        if(res.data){
                            var datos=res.data;
                            this.scors=datos['score'];
                            this.entidades=datos['entidades'];
                            this.zonas=datos['zonas'];
                            this.prioridades=datos['prioridades'];
                            this.tramos=datos['tramos'];
                        }
                    })
                }else{
                    this.mensaje="Selecciona una cartera";
                }
            },
            validarCampos(){
                this.errores=[];
                if(!this.registro.cartera){
                    this.errores.push("Seleccione una cartera");
                }
                if(!this.registro.fechaInicio){
                    this.errores.push("Ingrese una fecha de inicio predictivo");
                }
                if(!this.registro.fechaFin){
                    this.errores.push("Ingrese una fecha fin de predictivo");
                }
                if(!this.registro.usuario){
                    this.errores.push("Ingrese un usuario de predictivo");
                }
                if(!this.registro.campana){
                    this.errores.push("Ingrese un nombre a la campaña");
                }
                if(!this.detalle.tipo_cliente && this.tab==1){
                    this.errores.push("Seleccione un tipo de cliente");
                }
                if(!this.detalle.tipo_numeros){
                    this.errores.push("Seleccione un tipo de números");
                }
                if(!this.detalle.codigos && this.tab==2){
                    this.errores.push("Ingresar códigos");
                }
            },
            calcularCampana(){
               this.errores=[];
               this.validarCampos();
               if(this.errores.length==0){
                   this.loadingModal=true;
                   this.detalle.cartera=this.registro.cartera;
                   $('#modalDetalle').modal({backdrop: 'static', keyboard: false});
                   axios.post("calcularCampana",this.detalle).then(res=>{
                        if(res.data){
                            this.resultados=res.data[0].cantidad;
                            this.viewResultados=true;
                            this.loadingModal=false;
                        }
                    });
               }
           },
           limpiar(){
            this.registro={cartera:'',fechaInicio:'',fechaFin:'',usuario:'',campana:'',detalle:''};
            this.detalle={cartera:'',tipo_cliente:'1',tipo_numeros:'1',ubicabilidad:[],respuesta:[],capital_i:'',capital_f:'',deuda_i:'',deuda_f:'',importe_i:'',importe_f:'',score:[],entidad:[],zona:[],prioridad:[],tramo:[],gestion_dia:'',codigos:'',inhCodigos:'',inhibir_paletas:1};
            this.errores=[];
           },
           cancelar(){
               $('#modalDetalle').modal('hide');
               this.viewResultados=false;
           },
           registrar(){
               this.usuario='';
               this.idCampana='';
               var parametros={
                        cartera:this.registro.cartera,
                        fechaInicio:this.registro.fechaInicio,
                        fechaFin:this.registro.fechaFin,
                        usuario:this.registro.usuario,
                        campana:this.registro.campana,
                        tipo_cliente:this.detalle.tipo_cliente,
                        tipo_numeros:this.detalle.tipo_numeros,
                        ubicabilidad:this.detalle.ubicabilidad,
                        respuesta:this.detalle.respuesta,
                        capital_i:this.detalle.capital_i,
                        capital_f:this.detalle.capital_f,
                        deuda_i:this.detalle.deuda_i,
                        deuda_f:this.detalle.deuda_f,
                        importe_i:this.detalle.importe_i,
                        importe_f:this.detalle.importe_f,
                        score:this.detalle.score,
                        entidad:this.detalle.entidad,
                        zona:this.detalle.zona,
                        prioridad:this.detalle.prioridad,
                        tramo:this.detalle.tramo,
                        gestion_dia:this.detalle.gestion_dia,
                        codigos:this.detalle.codigos,
                        total:this.resultados,
                        inhibir_paletas:this.$delete.inhibir_paletas
                    };
               this.spinnerRegistrar=true;
               axios.post("crearCampana",parametros).then(res=>{
                    if(res.data){
                        var resp=res.data;
                        resp.forEach(element => {
                            this.idCampana=element.id;
                            this.usuario=element.usuario;
                        });
                        this.spinnerRegistrar=false;
                        this.loadingModal=false;
                        this.viewResultados=false;
                        this.limpiar();
                    }
                });
           },
           descargar(){
               this.spinnerDescargar=true;
               axios.get("asignar/"+this.idCampana+"/"+this.usuario).then(res=>{
                    if(res.data=="ok"){
                        this.spinnerDescargar=false;
                        window.location.href="./descargarPredictivo/"+this.idCampana;
                    }
               });
           },
           seleccionTab(opcion){
               this.tab=opcion;
               if(this.tab==1){
                   this.detalle.codigos='';
                   this.archivo='';
               }
               if(this.tab==2){
                   this.detalle={tipo_cliente:'1',tipo_numeros:'1',ubicabilidad:[],respuesta:[],capital_i:'',capital_f:'',deuda_i:'',deuda_f:'',importe_i:'',importe_f:'',score:[],entidad:[],zona:[],prioridad:[],tramo:[]};
                   this.archivo='';
               }
               if(this.tab==3){
                   this.detalle.codigos='';
                   this.detalle={tipo_cliente:'1',tipo_numeros:'1',ubicabilidad:[],respuesta:[],capital_i:'',capital_f:'',deuda_i:'',deuda_f:'',importe_i:'',importe_f:'',score:[],entidad:[],zona:[],prioridad:[],tramo:[]};
               }
               
           },
           obtenerArchivo(e){
                let file=e.target.files;
                this.archivo=file[0];
            },
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
        components: {
            
        }    
    }
</script>
