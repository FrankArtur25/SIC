<template>
    <div >
         <div class="row mt-3 mb-1">
            <div class="col-md-4 col-xl-4">
                <div class="">
                    <label for="cartera" class="font-bold col-form-label text-dark text-righ">Nombre de Cartera</label>
                    <select name="cartera" id="cartera" class="form-control" v-model="busqueda.cartera">
                        <option selected value="">Seleccionar</option>
                        <option value="0">TODAS LAS CARTERAS</option>
                        <option v-for="(item,index) in carteras" :key="index"  class="option" :value="item.id">{{item.cartera}}</option>
                    </select>
                    <small v-if="mensajeCartera" class="text-danger">{{mensajeCartera}}</small>
                </div>
            </div>
            <div class="col-md-3 col-xl-2">
                <div class="">
                    <label for="cartera" class="font-bold col-form-label text-white text-righ">-</label>
                    <a href="" @click.prevent="generarReporte()" class="btn btn-outline-blue btn-block waves-effect">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive my-4" v-if="viewTable" >
            <table class="table table-hover">
                <thead class="bg-blue text-white">
                    <tr class="text-center">
                        <td class="align-middle">GESTOR</td>
                        <td class="align-middle">8 A 9</td>
                        <td class="align-middle">9 A 10</td>
                        <td class="align-middle">10 A 11</td>
                        <td class="align-middle">11 A 12</td>
                        <td class="align-middle">12 A 13</td>
                        <td class="align-middle">13 A 14</td>
                        <td class="align-middle">14 A 15</td>
                        <td class="align-middle">15 A 16</td>
                        <td class="align-middle">16 A 17</td>
                        <td class="align-middle">17 A 18</td>
                        <td class="align-middle">18 A 19</td>
                        <td class="align-middle">19 A 20</td>
                        <td class="align-middle">20 A 21</td>
                        <td class="align-middle">TOTAL</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="datos==''">
                        <td colspan="15" class="text-center">No existen datos relacionados</td>
                    </tr>
                    <tr v-else v-for="(item,index) in datos" :key="index">
                        <td>{{item.gestor}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora1==0}">{{formatoNumero(item.hora1,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora2==0}">{{formatoNumero(item.hora2,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora3==0}">{{formatoNumero(item.hora3,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora4==0}">{{formatoNumero(item.hora4,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora5==0}">{{formatoNumero(item.hora5,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora6==0}">{{formatoNumero(item.hora6,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora7==0}">{{formatoNumero(item.hora7,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora8==0}">{{formatoNumero(item.hora8,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora9==0}">{{formatoNumero(item.hora9,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora10==0}">{{formatoNumero(item.hora10,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora11==0}">{{formatoNumero(item.hora11,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora12==0}">{{formatoNumero(item.hora12,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.hora13==0}">{{formatoNumero(item.hora13,'C')}}</td>
                        <td class="text-center" :class="{'bg-gray-2':item.total==0}">{{formatoNumero(item.total,'C')}}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-2 font-bold" v-if="datos!=''">
                    <tr>
                        <td class="text-center">TOTAL ({{totalRegistro}})</td>
                        <td class="text-center">{{formatoNumero(total('hora1'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora2'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora3'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora4'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora5'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora6'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora7'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora8'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora9'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora10'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora11'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora12'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('hora13'),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total('total'),'C')}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props:['carteras'],
        data() {
            return {
                datos: [],
                loading : false,
                busqueda:{cartera:''},
                mensajeCartera:'',
                viewTable:false,
                totalRegistro:0
            }
        },
        methods:{
            generarReporte(){
                this.viewTable=false;
                this.loading=true;
                this.datos=[];
                this.mensajeCartera='';
                if(this.busqueda.cartera!=''){
                    axios.get("cantGestioneHora/"+this.busqueda.cartera).then(res=>{
                        if(res.data){
                            this.datos=res.data;
                            this.totalRegistro=this.datos.length;
                            this.loading=false;
                            this.viewTable=true;
                        }
                    })
                }else{
                    this.datos=[];
                    this.loading=false;
                    this.mensajeCartera="Selecciona una cartera";
                }
            },
            total(base) {
                return this.datos.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
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
            
        }    
    }
</script>
