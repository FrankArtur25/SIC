<template>
    <div>
        <div class="table-responsive">
            <table class="table table-hover">
                    <thead class="bg-blue text-white">
                        <tr class="text-center">
                            <td class="align-middle font-11">Nº CUENTA</td>
                            <td class="align-middle font-11">TIPO CUENTA</td>
                            <td class="align-middle font-11">Nº TARJETA</td>
                            <td class="align-middle font-11">PRODUCTO</td>
                            <td class="align-middle font-11">FECHA DEUDA</td>
                            <td class="align-middle font-11">DIAS</td>
                            <td class="align-middle font-11">TRAMO</td>
                            <td class="align-middle font-11">PRIORIDAD</td>
                            <td class="align-middle font-11">MONEDA</td>
                            <td class="align-middle font-11">CAPITAL</td>
                            <td class="align-middle font-11">DEUDA SALDO</td>
                            <td class="align-middle font-11">DEUDA</td>
                            <td class="align-middle font-11">DTO.</td>
                            <td class="align-middle font-11 bg-white border-0 rounded-0" style="max-width:7px;"></td>
                            <td class="align-middle font-11">IMP. DTO.</td>
                            <td class="align-middle font-11">IMP. CANC.</td>
                            <td class="align-middle bg-white border-0 rounded-0 px-0" style="min-width: 0.1rem"></td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr v-if="cuentas==''">
                            <td colspan="14">No existen cuentas asociados al cliente</td>
                        </tr>
                        <tr v-for="(item,index) in cuentas" :key="index" v-else >
                            <td>{{item.cuenta}}</td>
                            <td>{{item.tipo_cuenta}}</td>
                            <td>{{item.tarjeta}}</td>
                            <td>{{item.producto}}</td>
                            <td>{{item.fecha_deuda}}</td>
                            <td>{{item.dias}}</td>
                            <td>{{item.tramo}}</td>
                            <td>{{item.prioridad}}</td>
                            <td>{{item.moneda}}</td>
                            <td>{{formatoMonto(item.capital)}}</td>
                            <td>{{formatoMonto(item.saldo)}}</td>
                            <td>{{formatoMonto(item.deuda)}}</td>
                            <td>{{item.dscto}}</td>
                            <td class="border-0 rounded-0" :class="{'text-danger':item.indicador_dscto==-1,'text-green':item.indicador_dscto==-2,'text-green':item.indicador_dscto==1,'text-black':item.indicador_dscto==0}">{{indicadoresDscto(item.indicador_dscto)}}</td>
                            <td>{{formatoMonto(item.importe_dscto)}}</td>
                            <td>{{formatoMonto(item.importe)}}</td>
                            <td class="border-0 rounded-0 px-0" style="width:0.5rem" v-if="item.dscto_adc!=''">
                                <a href="#" id="tooltip"><i class="far fa-star text-blue"></i><span class="tooltiptext text-center">{{item.dscto_adc}} - {{formatoMonto(item.importe_adc)}}</span></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>       
    </div>
</template>

<script>
    export default {
        props:["cuentas"],
        data() {
            return {
                // cuentas:[],
            }
        },
        methods:{
            indicadoresDscto(indicador){
                if(indicador==1){
                    return "↑"
                }
                if(indicador==-1){
                    return "↓"
                }
                if(indicador==0){
                    return "-"
                }
                if(indicador==-2){
                    return "■"
                }
            },
            formatoMonto(num){
                var nStr=parseFloat(num).toFixed(2);
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
