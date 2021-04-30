<template>
    <div >
         <!-- <div class="row mt-3 mb-1">
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
        </div> -->
        <div class="table-responsive my-4"  >
            <table class="table table-hover">
                <thead class="bg-blue text-white">
                    <tr>
                        <td colspan="17">
                            <div class="d-flex justify-content-between">
                                <p class="mb-0">CANTIDAD DE GESTIONES</p>
                                <p class="mb-0">Estándar: {{estandar_gestiones}} gestiones/h</p>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td class="align-middle"></td>
                        <td class="align-middle">CARTERA / GESTOR</td>
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
                        <td class="align-middle">TOTAL</td>
                        <td class="align-middle">IDEAL</td>
                        <td class="align-middle">DESFASE</td>
                    </tr>
                </thead>
                <tr v-if="datos==''">
                    <td colspan="17" class="text-center">No existen datos relacionados</td>
                </tr>
                <tbody v-else v-for="(item,index) in datos" :key="index">
                    <tr class="font-weight-bold bg-gray-2">
                        <td class="text-center"><a href="" class="btn btn-sm btn-outline-blue"><i class="fa fa-plus"></i></a></td>
                        <td class="">{{item.nombre}}</td>
                        <td v-for="n in parseInt(horas_trabajo)" :key="n" class="text-center" :class="{'bg-gray-2':item.hora1==0}">{{formatoNumero(total(item.gestores,'hora'+n),'C')}}</td>
                        <td class="text-center">{{formatoNumero(total(item.gestores,'total'),'C')}}</td>
                        <td class="text-center">{{formatoNumero((estandar_gestiones*horas_trabajo)*parseInt(item.gestores.length),'C')}}</td>
                        <td class="text-center">{{formatoNumero(totalDesfase(item.gestores),'C')}}</td>
                    </tr>
                    <tr v-for="(gestor,index) in item.gestores" :key="index" >
                        <td class="border-0"></td>
                        <td class="">{{gestor.gestor}}</td>
                        <td v-for="n in parseInt(horas_trabajo)" :key="n" class="text-center" :class="{'bg-success':gestor['hora'+n]>=estandar_gestiones,'bg-danger':gestor['hora'+n]==0}">{{formatoNumero(gestor["hora"+n],'C')}}</td>
                        <td class="text-center font-weight-bold" >{{formatoNumero(gestor.total,'C')}}</td>
                        <td class="text-center font-weight-bold" >{{formatoNumero(estandar_gestiones*horas_trabajo,'C')}}</td>
                        <td class="text-center font-weight-bold" :class="{'text-danger':gestor.total-(estandar_gestiones*horas_trabajo)<0}">{{formatoNumero(gestor.total-(estandar_gestiones*horas_trabajo),'C')}}</td>
                    </tr>
                </tbody>
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
                totalRegistro:0,
                estandar_contacto:0,
                estandar_gestiones:0,
                horas_trabajo:0
            }
        },
        created(){
            this.generarReporte();
        },
        methods:{
            generarReporte(){
                this.viewTable=false;
                this.loading=true;
                this.datos=[];
                this.mensajeCartera='';
                // if(this.busqueda.cartera!=''){
                    axios.get("reporteControl").then(res=>{
                        if(res.data){
                            let rest=res.data;
                            let estandar=rest.estandar;
                            estandar.forEach(e => {
                                if(e.var=='Contactos'){this.estandar_contacto=parseInt(e.val);}
                                if(e.var=='Gestiones'){this.estandar_gestiones=parseInt(e.val);}
                                if(e.var=='Horas'){this.horas_trabajo=parseInt(e.val);}
                            });
                            this.loading=false;
                            this.viewTable=true;
                            this.totalRegistro=this.datos.length;
                            
                            for (let i = 0; i < rest.gestiones.length; i++) {
                                let gestor = rest.gestiones[i];
                                var grupoCartera;
                                var cartera = {};
                                if (grupoCartera !== gestor.cartera) {
                                    grupoCartera = gestor.cartera;
                                    cartera.nombre = grupoCartera;
                                    cartera.gestores = rest.gestiones.filter(gestor => gestor.cartera === grupoCartera);
                                    this.datos.push(cartera);
                                }
                            }
                        }
                    })
                // }else{
                //     this.datos=[];
                //     this.loading=false;
                //     this.mensajeCartera="Selecciona una cartera";
                // }
            },
            total(data,base) {
                return data.reduce( (sum,cur) => sum+parseFloat(cur[base]) , 0);
            },
            totalDesfase(data) {
                let suma=0;
                data.forEach(el => {
                    suma+=parseInt(el.total-(this.estandar_gestiones*this.horas_trabajo))
                });
                return suma;
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
