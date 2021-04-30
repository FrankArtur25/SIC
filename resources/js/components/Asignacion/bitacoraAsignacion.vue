<template>
    <div >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-bold">Cartera</label>
                    <select class="form-control" v-model="buscar.cartera">
                        <option value="">Seleccionar</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="font-bold">Cód. Operación</label>
                    <input type="text" class="form-control" v-model="buscar.codigo" @keypress.enter="buscarAsignacion()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-bold">Fecha</label>
                    <input type="date" class="form-control" v-model="buscar.fecha" @keypress.enter="buscarAsignacion()">
                </div>
            </div>
            
            <div class="col-md-3 form-group">
                <label for="" class="text-white">.</label><br>
                <a href="" class="btn btn-outline-blue mb-3" @click.prevent="buscarAsignacion()">
                    <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Buscar
                </a>
            </div>
        </div>
        <div class="table-responsive my-2">
            <paginate ref="paginator" name="lista" :list="lista" :per="20" class="px-0">
                <table class="table table-hover">
                    <thead class="bg-blue-3 text-white text-center">
                        <tr>
                            <td>Cód. Operación</td>
                            <td>Cartera</td>
                            <td>Fecha</td>
                            <td>Clientes</td>
                            <td>Tipo</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="lista==''">
                            <td colspan="6" class="text-center">No hay resultados</td>
                        </tr>
                        <tr v-else v-for="(item,index) in paginated('lista')" :key="index">
                            <td class="text-center">{{item.codigo}}</td>
                            <td class="text-left px-2">{{item.cartera}}</td>
                            <td class="text-center">{{item.fecha}}</td>
                            <td class="text-center">{{formatoNumero(item.clientes,'C')}}</td>
                            <td class="text-center">{{item.tipo}}</td>
                            <td class="text-center">
                                <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="modalRegresarAsignacion(item.codigo,item.idcartera,item.cartera,item.fecha,item.clientes)">Regresar Asignación</a>
                                <a href="" class="btn btn-outline-blue btn-sm" @click.prevent="descargar(item.codigo)" >Descargar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </paginate>
        </div>
        <paginate-links  for="lista" v-if="lista.length>0" 
            :async="true"                     
            :limit="4"
            :classes="{'ul': 'pagination', 'li': 'page-item', 'a': 'page-link'}"
        ></paginate-links>


        <!-- modal regresar asignacion -->
        <div  class="modal modalCarga" tabindex="-1" role="dialog" id="modalAsignar" >
            <div class="d-flex justify-content-center align-items-center" style="margin-top:150px;" >
                <div class="text-center" v-if="viewFrmAsignar">
                    <p class="text-white font-30 mb-0">{{detalle.codigo}}</p>
                    <small class="text-white">Código de Operación</small>
                    <p class="text-white font-20">¿Desea regresar la asignación realizada el día {{detalle.fecha}}<br>de la cartera {{detalle.cartera_nombre}}<br>(Cant. Clientes: {{formatoNumero(detalle.cantidad,'C')}})?</p>
                    <a href="" v-if="spinnerAsignar==false" class="btn btn-white" @click.prevent="cancelar()">No</a>
                    <a href="" class="btn btn-danger" @click.prevent="regresarAsignacion()">
                        <span v-if="spinnerAsignar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
                        Sí, Regresar Asignación
                    </a>
                </div>
                <div class="text-center" v-else>
                    <p><i class="fa fa-check-circle fa-3x text-white"></i></p>
                    <p class="text-white font-20 mb-0">Se realizó la asignación con éxito!</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vuePaginate from '../../../../node_modules/vue-paginate';
    export default {
        props:['carteras'],
        data() {
            return {
                buscar:{fecha:'',codigo:'',cartera:''},
                detalle:{fecha:'',codigo:'',cartera:'',cartera_nombre:'',cantidad:''},
                spinnerBuscar:'',
                spinnerActualizar:'',
                paginate: ['lista'],
                lista:[],
                total:0,
                viewFrmAsignar:true,
                spinnerAsignar:false
            }
        },
        methods:{
            buscarAsignacion(){
                this.spinnerBuscar=true;
                axios.post("listBitacoraAsignacion",this.buscar).then(res=>{
                    if(res.data){
                        this.lista=res.data;
                        this.total=this.lista.length;
                        this.spinnerBuscar=false;
                    }
                });
            },
            modalRegresarAsignacion(codigo,idcartera,cartera,fecha,cantidad){
               this.viewFrmAsignar=true;
               this.detalle.codigo=codigo;
               this.detalle.cartera=idcartera;
               this.detalle.cartera_nombre=cartera;
               this.detalle.cantidad=cantidad;
               this.detalle.fecha=fecha;
               $('#modalAsignar').modal({backdrop: 'static', keyboard: false});
            },
            cancelar(){
                this.viewFrmAsignar=true;
                this.spinnerAsignar=false;
                $('#modalAsignar').modal('hide');
            },
            regresarAsignacion(){
                this.spinnerAsignar=true;
                axios.get("updateRegresarAsignacion/"+this.detalle.codigo+"/"+this.detalle.cartera).then(res=>{
                    if(res.data=="ok"){
                        this.viewFrmAsignar=false;
                        this.spinnerAsignar=true;
                        this.buscarAsignacion();
                        setTimeout(() => {
                            $('#modalAsignar').modal('hide');
                        }, 1000);
                    }
                });
            },
            descargar(codigo){
                window.location.href="descargarBitacoraRepositorio/"+codigo;
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
        
        components: {
            vuePaginate,
        }    
    }
</script>
