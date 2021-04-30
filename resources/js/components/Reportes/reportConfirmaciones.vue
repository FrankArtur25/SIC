<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Seleccionar Cartera</label>
                    <select class="selectpicker form-control" v-model="busqueda.cartera" title="Seleccionar">
                        <option value="0">TODAS LAS CARTERAS</option>
                        <option v-for="(item,index) in carteras" :key="index" :value="item.id">{{item.cartera}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Estructura</label>
                    <select class="selectpicker form-control" v-model="busqueda.estructura" @change="limpiarCall(busqueda.estructura)" :disabled="busqueda.cartera==0">
                        <option value="1">General</option>
                        <option value="2" selected>Por Call</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Call</label>
                    <select class="selectpicker form-control" multiple title="Seleccionar" data-selected-text-format="count > 2" v-model="busqueda.calls" :disabled="busqueda.estructura==1">
                        <option v-for="(item,index) in calls" :key="index" :value="item.id">{{item.calll}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Tipo de Fecha</label>
                    <select class="selectpicker form-control" v-model="busqueda.tipoFecha">
                        <option value="1" selected>Fecha de Confirmación</option>
                        <option value="2">Fecha de Gestión</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Inicio</label>
                    <input type="date" class="selectpicker form-control" v-model="busqueda.fechaInicio">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Fecha Fin</label>
                    <input type="date" class="selectpicker form-control" v-model="busqueda.fechaFin">
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="">Columnas a mostrar</label>
                    <select class="selectpicker form-control" multiple v-model="busqueda.columnas">
                        <!-- <option value="1">Fecha</option> -->
                        <option value="2">Cantidad</option>
                        <option value="3" selected>Monto</option>
                    </select>
                </div>
            </div>
        </div>
        <a href="" class="btn btn-outline-blue" @click.prevent="generarReporte()">
            <span v-if="spinnerBuscar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  
            Generar Reporte
        </a>
        <a href="" class="btn btn-outline-blue" @click.prevent="limpiar()">Limpiar</a>
        <a href="" v-if="datos!=''" class="btn btn-outline-blue" @click.prevent="descargar()">Descargar Reporte</a>
        <div class="alert alert-warning my-3" v-if="errores!=''">
            <p class="font-bold mb-0">Corrija el(los) siguiente(s) error(es):</p>
            <ul>
                <li v-for="(item,index) in errores" :key="index">{{item}}</li>
            </ul>
        </div>
        <div class="table-responsive my-3" v-if="viewTabla">
            <table class="table table-hover">
                <thead class="bg-blue-3 text-white text-center">
                    <tr>
                        <td>Fecha</td>
                        <td>Cant. Total</td>
                        <td>Monto Total</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in datos" :key="index" class="text-center">
                        <td>{{item.fecha}}</td>
                        <td v-if="item.cantTotal!=''">{{item.cantTotal}}</td>
                        <td v-if="item.montoTotal!=''">{{item.montoTotal}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    
    export default {
        props:['carteras','calls'],
        data() {
            return {
                spinnerBuscar:false,
                busqueda:{cartera:'',estructura:'1',calls:[],tipoFecha:'1',fechaInicio:'',fechaFin:'',columnas:[2,3]},
                viewTabla:false,
                datos:[],
                view:{cantidad:'',monto:''},
                errores:[]
            }
        },
        methods:{
            generarReporte(){
                this.errores=[];
                this.validacion();
                // this.viewTabla=false;
                // this.datos=[];
                if(this.errores.length==0){
                    if(this.busqueda.estructura==1){
                        this.busqueda.calls=0;
                    }
                    window.location.href="generarReporteConfirmaciones/"+this.busqueda.cartera+"/"+this.busqueda.estructura+"/"+this.busqueda.calls+"/"+this.busqueda.tipoFecha+"/"+this.busqueda.fechaInicio+"/"+this.busqueda.fechaFin+"/"+this.busqueda.columnas;
                    
                    // this.spinnerBuscar=true;
                    // axios.post("generarReporteConfirmaciones",this.busqueda).then(res=>{
                    //     if(res.data){
                    //         // this.datos=res.data;
                    //         var blob = new Blob([res.data]);
                    //         if (window.navigator.msSaveOrOpenBlob){
                    //             window.navigator.msSaveBlob(blob, "filename.xlsx");
                    //         }
                    //         else {
                    //             var a = window.document.createElement("a");

                    //             a.href = window.URL.createObjectURL(blob, {
                    //             type: "text/plain"
                    //             });
                    //             a.download = "filename.csv";
                    //             document.body.appendChild(a);
                    //             a.click();
                    //             document.body.removeChild(a);
                    //         }
                    //         // const url = window.URL.createObjectURL(new Blob([res.data]));
                    //         // const link = document.createElement('a');
                    //         // link.setAttribute('download', 'file.xlsx');
                    //         // document.body.appendChild(link);
                    //         // link.click();
                    //         this.viewTabla=true;
                    //         this.spinnerBuscar=false;
                    //     }
                    // });
                }
            },
            limpiar(){
                this.busqueda={cartera:'',estructura:'1',calls:[],tipoFecha:'1',fechaInicio:'',fechaFin:'',columnas:[2,3]};
            },
            validacion(){
                if(!this.busqueda.cartera){
                    this.errores.push("Seleccionar cartera");
                }
                if(!this.busqueda.estructura){
                    this.errores.push("Seleccionar estructura");
                }
                if( this.busqueda.calls.length==0 && this.busqueda.estructura==2){
                    this.errores.push("Seleccionar al menos un call");
                }
                if(!this.busqueda.tipoFecha){
                    this.errores.push("Seleccionar tipo de fecha");
                }
                if(!this.busqueda.fechaInicio){
                    this.errores.push("Ingresar Fecha Inicio");
                }
                if(!this.busqueda.fechaFin){
                    this.errores.push("Ingresar Fecha Fin");
                }
                if( this.busqueda.columnas.length==0 ){
                    this.errores.push("Seleccionar al menos una columna");
                }
            },
            limpiarCall(estructura){
                if(estructura==1){
                    this.busqueda.calls=[];
                }
            }
        },
        updated() {
            $('.selectpicker').selectpicker('refresh'); 
        },
        components: {
            
        }    
    }
</script>
