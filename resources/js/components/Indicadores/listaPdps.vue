<template>
    <div >
        <div id="contenidoInicial">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex" style="gap:8px">
                        <div class="w-50">
                            <label class="mb-0">Código</label>
                            <input type="text" class="form-control form-control-sm" v-model="busqueda.codigo" v-on:keyup.enter="buscar()">
                        </div>
                        <div class="w-50">
                            <label class="mb-0">DNI/RUC</label>
                            <input type="text" class="form-control form-control-sm" v-model="busqueda.documento" v-on:keyup.enter="buscar()">
                        </div>
                    </div>
                    <div class="my-2">
                        <label class="mb-0">Nombre</label>
                        <input type="text" class="form-control form-control-sm" v-model="busqueda.nombre" v-on:keyup.enter="buscar()">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex" style="gap:8px">
                        <div class="w-50">
                            <label class=" mb-0">Tipo</label>
                            <select class="form-control form-control-sm" v-model="busqueda.tipo_gestor">
                                <option value="">Seleccionar</option>
                                <option value="1">Administrador</option>
                                <option value="2">G. Telefónico</option>
                                <!-- <option value="3">G. </option> -->
                            </select>
                        </div>
                        <div class="w-50">
                            <label class=" mb-0">Oficina</label>
                            <select class="form-control form-control-sm" v-model="busqueda.oficina">
                                <option value="">Seleccionar</option>
                                <option v-for="(item,index) in oficinas" :key="index" :value="item.idoficina">{{item.oficina}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="my-2">
                        <label class=" mb-0">Cartera</label>
                        <select class="form-control form-control-sm" v-model="busqueda.cartera">
                            <option value="">Seleccionar</option>
                            <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex" style="gap:8px">
                        <div class="w-50">
                            <label class=" mb-0">Tipo PDP</label>
                            <select class="form-control form-control-sm" v-model="busqueda.tipo_pdp">
                                <option value="">Seleccionar</option>
                                <option value="1">Compromiso de pago</option>
                                <option value="43">Compromiso por cuota</option>
                            </select>
                        </div>
                        <div class="w-50">
                            <label class=" mb-0">Monto</label>
                            <select class="form-control form-control-sm" v-model="busqueda.monto">
                                <option value="">Seleccionar</option>
                                <option value="1">Mayores a 500</option>
                                <option value="2">Mayores a 1000</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex my-2" style="gap:8px">
                        <div class="w-50">
                            <label class=" mb-0">PDP Desde</label>
                            <input type="text" class="form-control form-control-sm" placeholder="dd/mm/aaaa" v-model="busqueda.fechaPdpInicio">
                        </div>
                        <div class="w-50">
                            <label class=" mb-0">PDP Hasta</label>
                            <input type="text" class="form-control form-control-sm" placeholder="dd/mm/aaaa" v-model="busqueda.fechaPdpFin" v-on:keyup.enter="buscar()">
                        </div>
                    </div>
                </div>
            </div>
            <a href="" @click.prevent="buscar()" class="btn btn-outline-blue px-4 my-2">
                <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Buscar
            </a>
            <a href="" @click.prevent="limpiar()" class="btn btn-outline-blue px-4 my-2">
                Limpiar
            </a>
            <a href="" @click.prevent="exportar()" class="btn btn-outline-blue px-4 my-2">
                <span v-if="spinnerExportar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Exportar
            </a>
            <div class="py-3" v-if="viewTabla">
                <div class="table-responsive" >
                    <paginate ref="paginator" name="lista" :list="lista" :per="20" class="px-0">
                        <table class="table table-hover">
                            <thead class="bg-blue-3 text-white text-center">
                                <tr>
                                    <td class="align-middle">Código<br>({{total}})</td>
                                    <td class="align-middle">Nombre</td>
                                    <td class="align-middle">Tipo Doc.</td>
                                    <td class="align-middle">Num. Doc.</td>
                                    <td class="align-middle">Cartera</td>
                                    <td class="align-middle">G. Telef.</td>
                                    <td class="align-middle">Oficina</td>
                                    <td class="align-middle">Usuario/Gestor</td>
                                    <td class="align-middle">Tipo</td>
                                    <td class="align-middle">Gestión</td>
                                    <td class="align-middle">Fecha PDP</td>
                                    <td class="align-middle">Monto PDP</td>
                                    <td class="border-0 bg-white rounded-0 px-0" style="min-width:5px;"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="lista==''"  class="text-center">
                                    <td colspan="13">No existen registros relacionados a la búsqueda</td>
                                </tr>
                                <tr v-else v-for="(item,index) in  paginated('lista')" :key="index"> 
                                    <td>{{item.codigo}}</td>
                                    <td>{{item.nombre}}</td>
                                    <td>{{item.tipo_documento}}</td>
                                    <td>{{item.numero_documento}}</td>
                                    <td>{{item.cartera}}</td>
                                    <td>{{item.gestor_tel}}</td>
                                    <td>{{item.oficina}}</td>
                                    <td>{{item.gestor_gestion}}</td>
                                    <td>{{item.tipo_gestor}}</td>
                                    <td style="min-width:10rem;">{{item.gestion}}</td>
                                    <td style="min-width:4.5rem;">{{item.fecha_pdp}}</td>
                                    <td>{{item.moneda}}{{item.monto_pdp}}</td>
                                    <td class="border-0 bg-white rounded-0 px-0">
                                        <a href="" class="btn-phone" @click.prevent="detalle(item.id)"><i class="fa fa-phone fa-1x"></i></a>
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
            </div>
        </div>

        <!-- detalle -->
        <div v-if="viewDetalleCliente" id="panelDetalleClientePdp">
            <div class="bg-white">
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
                                <detalleCliente :datos="dataCliente" v-if="dataCliente.length>0" :info="detalleGeneral['infoCliente']" :tipoacceso="1" />
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
                        
                        <formRegistrarGestion :id-cliente="idCliente" :tipo="1" :telefonosgenerales="detalleGeneral['telefonos']" :valcontacto="detalleGeneral['validar_contacto']"  :datospdp="detalleGeneral['pdps']" />
                        
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
        
    </div>
</template>

<script>
    import vuePaginate from '../../../../node_modules/vue-paginate';
    import XLSX from '../../../../node_modules/xlsx';
    // import detalleClienteGeneral from './detalleCliente';
    import detalleCliente from '../Gestor/DetalleCliente';
    import detalleGestiones from '../Gestor/DetalleGestiones';
    import detalleCuentas from '../Gestor/DetalleCuentas';
    import formRegistrarGestion from '../Gestor/FormRegistrarGestion';
    import detalleTelefonos from '../Gestor/DetalleTelefonos';
    import detallePagos from '../Gestor/DetallePagos';

    export default {
        props:['carteras'],
        data() {
            return {
                oficinas:[],
                busqueda:{codigo:'',documento:'',nombre:'',tipo_gestor:'',oficina:'',cartera:'',tipo_pdp:'',monto:'',fechaPdpInicio:'',fechaPdpFin:'',fechaInicio:'',fechaFin:''},
                spinnerBuscar:'',
                spinnerExportar:'',
                mensaje:'',
                viewTabla:false,
                paginate: ['lista'],
                lista: [],
                viewDetalleCliente:false,
                viewTelefonos:false,
                viewPagos:false,
                dataCliente:[],
                detalleGeneral:[],
                idCliente:'',
                loadCarga:false,
                historicoGestiones:[],
                total:0,
                cartera:'',
                dataExportar:[]
            }
        },
        created(){
            this.listaOficinas();
            this.diaActual();
        },
        watch:{
            'busqueda.fechaPdpInicio': function(val){this.busqueda.fechaPdpInicio = this.validarFormatoFecha(val)},
            'busqueda.fechaPdpFin': function(val){this.busqueda.fechaPdpFin = this.validarFormatoFecha(val)}            
        },
        methods:{
           listaOficinas(){
                axios.get("listaOficinas").then(res=>{
                    if(res.data){
                        this.oficinas=res.data;
                    }
                });
            }, 
           validarFormatoFecha(value){
                let input = value;
                if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
                let values = input.split('/').map(v=> v.replace(/\D/g, ''));     
                if (values[0]) values[0] = this.checkValue(values[0], 31);
                if (values[1]) values[1] = this.checkValue(values[1], 12);
                var output = values.map((v, i) => v.length == 2 && i < 2 ? v + ' / ' : v);                
                return output.join('').substr(0,14);
            },
           checkValue(str,max){
                if (str.charAt(0) !== '0' || str == '00') {
                    var num = parseInt(str);
                    if (isNaN(num) || num <= 0 || num > max) num = 1;
                    str = num > parseInt(max.toString().charAt(0)) 
                        && num.toString().length == 1 ? '0' + num : num.toString();
                };
                return str;
            },
           buscar(){
               if(this.busqueda.fechaPdpInicio!='' && this.busqueda.fechaPdpFin!=''){
                   let fechas_i =  this.busqueda.fechaPdpInicio.split('/');                   
                   let nuevaFecha_i = `${fechas_i[2]}-${fechas_i[1]}-${fechas_i[0]}`
                   this.busqueda.fechaInicio= nuevaFecha_i.split(' ').join('');
   
                   let fechas_f =  this.busqueda.fechaPdpFin.split('/');                   
                   let nuevaFecha_f = `${fechas_f[2]}-${fechas_f[1]}-${fechas_f[0]}`
                   this.busqueda.fechaFin= nuevaFecha_f.split(' ').join('');
                   
               }
                this.cartera=this.busqueda.cartera;
                this.viewTabla=false;
                this.spinnerBuscar=true;
                axios.post("listaPdps",this.busqueda).then(res=>{
                    if(res.data){
                        this.lista=res.data;
                        this.total=this.lista.length;
                        this.spinnerBuscar=false;
                        this.viewTabla=true;
                    }
                });
            },
           detalle(id){
                this.viewDetalleCliente=true;
                let datos=[];
                $('#contenidoInicial').toggleClass('pos_fixed');
                for(let i=0;i<this.lista.length;i++){
                    if(this.lista[i].id==id){
                        datos.push(this.lista[i]);
                        this.dataCliente=datos;
                        this.idCliente=datos[0].id;
                        this.datosgenerales(this.idCliente);
                        break;
                    }
                }
            },
           cerrarDetalle(){
                this.detalleGeneral=[];
                $('#contenidoInicial').toggleClass('pos_fixed');
                this.viewDetalleCliente=false;
            },       
           verTelefonos(){
                this.viewTelefonos=true;
                this.$root.$emit('limpiarFrmTel');
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
            },
            diaActual(){
                var date = new Date();
                var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
                var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                
                this.busqueda.fechaPdpInicio=this.addZero(primerDia.getDate())+"/"+this.addZero(primerDia.getMonth()+1)+"/"+primerDia.getFullYear();
                this.busqueda.fechaPdpFin=this.addZero(ultimoDia.getDate())+"/"+this.addZero(ultimoDia.getMonth()+1)+"/"+ultimoDia.getFullYear();
                
            },
            addZero(i) {
                if (i < 10) {
                    i = '0' + i;
                }
                return i;
            },
           limpiar(){
               this.busqueda.codigo='';
               this.busqueda.documento='';
               this.busqueda.tipo_gestor='';
               this.busqueda.tipo_pdp='';
               this.busqueda.nombre='';
               this.busqueda.oficina='';
               this.busqueda.monto='';
               this.busqueda.cartera='';
           },
           exportar(){
            this.spinnerExportar=true;
            axios.post("descargarListaPdps",this.busqueda).then(res=>{
                if(res.data){
                    this.dataExportar=res.data;
                    let data = XLSX.utils.json_to_sheet(this.dataExportar)
                    const workbook = XLSX.utils.book_new()
                    const filename = 'PDPS_Clientes'
                    XLSX.utils.book_append_sheet(workbook, data, filename)
                    XLSX.writeFile(workbook, `${filename}.xlsx`)
                    this.spinnerExportar=false;
                }
            })  
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
            XLSX,
            detalleCliente,
            detalleGestiones,
            detalleCuentas,
            formRegistrarGestion,
            detalleTelefonos,
            detallePagos,
        }    
    }
</script>
