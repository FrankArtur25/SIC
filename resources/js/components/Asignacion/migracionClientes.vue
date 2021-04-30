<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Cód. Operación</label>
                    <input type="text" disabled class="form-control" v-model="registro.codigo">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Cartera</label>
                <select class="selectpicker form-control show-tick" data-size="8"  title="Seleccionar" v-model="registro.cartera" :disabled="bloquear">
                     <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                </select>
                <small v-if="mensaje.cartera" class="text-danger">{{mensaje.cartera}}</small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="">De</label>
                <select class="selectpicker form-control" data-size="8" data-live-search="true" title="Seleccionar" v-model="registro.de_usuario" :disabled="bloquear">
                    <option  v-for="(item,index) in usuarios" :key="index" :value="item.id">{{item.nombre}}</option>
                </select>
                <small v-if="mensaje.de_usuario" class="text-danger">{{mensaje.de_usuario}}</small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="">A</label>
                <select class="selectpicker form-control show-tick" data-size="8" data-live-search="true" title="Seleccionar" v-model="registro.a_usuario" :disabled="bloquear">
                    <option  v-for="(item,index) in usuarios" :key="index" :value="item.id">{{item.nombre}}</option>
                </select>
                <small v-if="mensaje.a_usuario" class="text-danger">{{mensaje.a_usuario}}</small>
            </div>
        </div>
        
        <div v-if="viewBtn" class="pt-2">
            <a href="" class="btn btn-outline-blue" @click.prevent="calcularIntercambio()">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Calcular Intercambio
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        </div>
        <div class="my-3" v-if="viewTabla">
            <div class="row">
                <div class="col-md-4 text-center">
                    <table class="table table-hover">
                        <thead class="bg-blue-3 text-white text-center">
                            <tr>
                                <td colspan="2">ASIGNACIÓN ACTUAL</td>
                            </tr>
                            <tr>
                                <td>Usuario</td>
                                <td>Clientes</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in datos" :key="index">
                                <td class="text-left px-2">{{item.usuario}}</td>
                                <td class="text-center">{{formatoNumero(item.cantidad_actual,'C')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-1">
                    <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                        <i class="fa fa-exchange-alt"></i>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <table class="table table-hover">
                        <thead class="bg-blue-3 text-white text-center">
                            <tr>
                                <td colspan="2">ASIGNACIÓN A REALIZAR</td>
                            </tr>
                            <tr>
                                <td>Usuario</td>
                                <td>Clientes</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left px-2">{{nombres.de_usuario}}</td>
                                <td class="text-center">0</td>
                            </tr>
                            <tr>
                                <td class="text-left px-2">{{nombres.a_usuario}}</td>
                                <td class="text-center">{{formatoNumero(total('cantidad_actual'),'C')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="" class="btn btn-outline-blue" @click.prevent="generarIntercambio()">
                <span v-if="spinnerIntercambio" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                Generar Intercambio
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="viewBtn=true;viewTabla=false;bloquear=false">Cancelar</a>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['usuarios','carteras','codigo'],
        data() {
            return {
                spinnerBuscar:false,
                spinnerIntercambio:false,
                registro:{de_usuario:'',a_usuario:'',cartera:'',codigo:this.codigo},
                viewTabla:false,
                datos:[],
                mensaje:{de_usuario:'',a_usuario:'',cartera:''},
                nombres:{de_usuario:'',a_usuario:'',cartera:''},
                bloquear:false,
                viewBtn:true
            }
        },
        methods:{
            limpiar(){
                this.registro.de_usuario='';
                this.registro.a_usuario='';
                this.registro.cartera='';
                this.registro.codigo='';
            },
            generarCodigo(){
                axios.get("generarCodigoAsignacion").then(res=>{
                    if(res.data){
                        this.registro.codigo=res.data;
                    }
                });
            },
            calcularIntercambio(){
                if(this.registro.a_usuario!='' && this.registro.de_usuario!='' && this.registro.de_usuario!=this.registro.a_usuario){
                    this.spinnerBuscar=true;
                    this.mensaje.de_usuario='';
                    this.mensaje.a_usuario='';
                    this.mensaje.cartera='';
                    this.nombres.de_usuario='';
                    this.nombres.a_usuario='';
                    this.datos='';
                    axios.post("consultarIntercambio",this.registro).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.datos.forEach(el => {
                                if(el.orden==1){
                                    this.nombres.de_usuario=el.usuario;
                                }else{
                                    this.nombres.a_usuario=el.usuario;
                                }
                            });
                            this.bloquear=true;
                            this.viewBtn=false;
                            this.viewTabla=true;
                            this.spinnerBuscar=false;
                        }
                    });
                }else{
                    if(!this.registro.a_usuario){
                        this.mensaje.a_usuario='Completar campo';
                    }
                    if(!this.registro.de_usuario){
                        this.mensaje.de_usuario='Completar campo';
                    }
                    if(!this.registro.cartera){
                        this.mensaje.cartera='Completar campo';
                    }
                }
            },
            total(base) {
                return this.datos.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            generarIntercambio(){
                this.spinnerIntercambio=true;
                axios.post("updateIntercambio",this.registro).then(res=>{
                    if(res.data=="ok"){
                        toastr.info('Los datos se actualizaron con éxito', 'Asignación Exitosa!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                        this.viewBtn=true;
                        this.viewTabla=false;
                        this.spinnerIntercambio=false;
                        this.limpiar();
                        this.bloquear=false;
                        this.generarCodigo();
                    }
                });
            },
            formatoNumero(num,tipo){
                if(tipo=='M'){
                    var nStr=parseFloat(num).toFixed(2);
                }else{
                    var nStr=num;
                }
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2
            },
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
         
    }
</script>
