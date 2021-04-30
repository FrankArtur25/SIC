<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Cód. Operación</label>
                    <input type="text" disabled class="form-control" v-model="asignacion.codigo">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="form-control" v-model="asignacion.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensaje.cartera" class="text-danger">{{mensaje.cartera}}</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label for="">Cargar Archivo (Máx. 10,000 registros)</label>
                    <input type="file" class="form-control" accept=".xlsx" @change="obtenerArchivo">
                    <small>Estructura: Código | Usuario *Sin cabecera</small>
                    <small v-if="mensaje.archivo" class="text-danger">{{mensaje.archivo}}</small>
                </div>
            </div>
        </div>        
        <div v-if="viewBtn" class="pt-2">
            <a href="" class="btn btn-outline-blue" @click.prevent="calcularAsignacion()">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Calcular Asignación
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        </div>
        <div class="my-3" v-if="viewTabla">
            <div class="row">
                <div class="col-md-4 text-center">
                    <table class="table table-hover">
                        <thead class="bg-blue-3 text-white text-center">
                            <tr>
                                <td colspan="2">ASIGNACIÓN</td>
                            </tr>
                            <tr>
                                <td>Usuario</td>
                                <td>Clientes</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in datos" :key="index">
                                <td class="text-center px-2 bg-gray-2">{{item.usuario}}</td>
                                <td class="text-center">{{formatoNumero(item.cantidad,'C')}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="text-center">
                                <td class="bg-gray-2 font-bold">TOTAL</td>
                                <td class="bg-gray-2 font-bold">{{formatoNumero(total('cantidad'),'C')}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <a href="" class="btn btn-outline-blue" @click.prevent="generarAsignacion()">
                <span v-if="spinnerAsignacion" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                Generar Asignación
            </a>
            <a href="" class="btn btn-outline-blue" @click.prevent="viewBtn=true;viewTabla=false;bloquear=false" v-if="btnCancelar">Cancelar</a>
            <div class="alert alert-danger" v-if="mensaje.error">
                <p>{{mensaje.error}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras','codigo'],
        data() {
            return {
                spinnerBuscar:false,
                spinnerAsignacion:false,
                asignacion:{cartera:'',archivo:'',codigo:this.codigo},
                viewTabla:false,
                datos:[],
                mensaje:{cartera:'',archivo:'',error:''},
                bloquear:false,
                viewBtn:true,
                btnCancelar:true
            }
        },
        methods:{
            limpiar(){
                this.asignacion.cartera='';
                this.asignacion.archivo='';
                this.mensaje.cartera='';
                this.mensaje.archivo='';
            },
            obtenerArchivo(e){
                let file=e.target.files;
                this.asignacion.archivo=file[0];
            },
            generarCodigo(){
                axios.get("generarCodigoAsignacion").then(res=>{
                    if(res.data){
                        this.asignacion.codigo=res.data;
                    }
                });
            },
            calcularAsignacion(){
                this.mensaje.error='';
                if(this.asignacion.codigo!='' && this.asignacion.cartera!='' && this.asignacion.archivo!=''){
                    this.spinnerBuscar=true;
                    this.mensaje.cartera='';
                    this.mensaje.archivo='';
                    this.datos='';
                    let formData= new FormData();
                    formData.append("cartera",this.asignacion.cartera);
                    formData.append("archivo",this.asignacion.archivo);
                    formData.append("codigo",this.asignacion.codigo);
                    axios.post("importExcelAsignacion",formData).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.bloquear=true;
                            this.viewBtn=false;
                            this.viewTabla=true;
                            this.spinnerBuscar=false;
                            this.btnCancelar=true;
                        }
                    });
                }else{
                    if(!this.asignacion.cartera){
                        this.mensaje.cartera='Completar campo';
                    }
                    if(!this.asignacion.archivo){
                        this.mensaje.archivo='Completar campo';
                    }
                }
            },
            total(base) {
                return this.datos.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            generarAsignacion(){
                this.mensaje.error='';
                this.spinnerAsignacion=true;
                this.btnCancelar=false;
                let formData= new FormData();
                formData.append("cartera",this.asignacion.cartera);
                formData.append("archivo",this.asignacion.archivo);
                formData.append("codigo",this.asignacion.codigo);
                axios.post("generarAsignacionMultiple",formData).then(res=>{
                    try {
                        if(res.data=="ok"){
                            toastr.info('Los datos se actualizaron con éxito', 'Asignación Exitosa!',{"progressBar": true,"positionClass": "toast-bottom-right",});
                            this.spinnerAsignacion=false;
                            this.viewTabla=false;
                            this.viewBtn=true;
                            this.bloquear=false;
                            this.btnCancelar=true;
                            this.limpiar();
                            this.generarCodigo();
                        }else{
                            this.mensaje.error='ERROR! Los recursos del sistema no son suficientes para completar la acción';
                        }
                    } catch (error) {
                        this.mensaje.error='ERROR! Los recursos del sistema no son suficientes para completar la acción';
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
         
    }
</script>
