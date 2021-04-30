<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="form-control" v-model="busqueda.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                 <div class="form-group">
                    <label for="">Mes de Pago</label>
                    <input type="month" class="form-control" v-model="busqueda.mes">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Completar Campo</label>
                    <select class="form-control" title="Seleccionar" v-model="busqueda.campo">
                        <option value="">Seleccionar</option>
                        <option value="1">Código Cliente</option>
                        <option value="2">Cuenta Producto</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="">Cargar Archivo</label>
                    <input type="file" class="form-control" accept=".xlsx" @change="obtenerArchivo">
                    <small>Estructura (Sin cabecera): Código | Cuenta | Monto Pago | Fecha Pago | Producto</small>
                </div>
            </div>
        </div>        
        <div v-if="viewBtn" class="pt-2">
            <a href="" class="btn btn-outline-blue" @click.prevent="actualizar()">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Cargar Pagos
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        </div>
        <div class="row" v-if="errores!=''">
            <div class="alert alert-warning my-3 col-md-10">
                <p class="font-bold">Completar el(los) siguiente(s) error(es)</p>
                <ul>
                    <li v-for="(item,index) in errores" :key="index">{{item}}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras'],
        data() {
            return {
                spinnerBuscar:false,
                spinnerAsignacion:false,
                busqueda:{cartera:'',mes:'',campo:'',archivo:''},
                datos:[],
                mensaje:{error:''},
                bloquear:false,
                viewBtn:true,
                btnCancelar:true,
                errores:[]
            }
        },
        methods:{
            limpiar(){
                this.busqueda.cartera='';
                this.busqueda.mes='';
                this.busqueda.campo='';
                this.busqueda.archivo='';
            },
            obtenerArchivo(e){
                let file=e.target.files;
                this.busqueda.archivo=file[0];
            },
            validarDatos(){
                this.errores=[];
                if(!this.busqueda.cartera){
                    this.errores.push("Seleccionar cartera");
                }
                if(!this.busqueda.mes){
                    this.errores.push("Seleccionar mes de pago");
                }
                if(!this.busqueda.archivo){
                    this.errores.push("Seleccionar archivo");
                }
            },
            actualizar(){
                this.validarDatos();
                if(this.errores.length==0){
                    this.spinnerBuscar=true;
                    this.datos='';
                    let formData= new FormData();
                    formData.append("cartera",this.busqueda.cartera);
                    formData.append("mes",this.busqueda.mes);
                    formData.append("campo",this.busqueda.campo);
                    formData.append("archivo",this.busqueda.archivo);
                    axios.post("actualizarPagosCartera",formData).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.bloquear=true;
                            //this.viewBtn=false;
                            this.viewTabla=true;
                            this.spinnerBuscar=false;
                            this.btnCancelar=true;
                        }
                    });
                }
            },
            generarAsignacion(){
                this.mensaje.error='';
                this.spinnerBuscar=true;
                //this.btnCancelar=false;
                let formData= new FormData();
                formData.append("cartera",this.busqueda.cartera);
                formData.append("mes",this.busqueda.mes);
                formData.append("campo",this.busqueda.campo);
                formData.append("archivo",this.busqueda.archivo);

                axios.post("actualizarPagosCartera",formData).then(res=>{
                    try {
                        if(res.data=="ok"){
                            toastr.info('Los datos se actualizaron con éxito', 'Actualización Exitosa!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                        }
                    } catch (error) {
                        this.errores.push("Error!");
                    }
                    this.spinnerBuscar=false;
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
         
    }
</script>
