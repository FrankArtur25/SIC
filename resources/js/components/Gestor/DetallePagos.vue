<template>
    <div>
        <div class="modal fadeInRight" id="modal-pagos" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-rigth modal-xl w-4" role="document" style="width:330px;">
                <div class="modal-content">
                <div class="modal-header bg-blue pt-3 pl-3 pr-4">
                    <p class="p-title mb-0 text-white"><i class="fa fa-coins pr-3"></i>PAGOS</p>
                    <a href="" class="close " data-dismiss="modal" aria-label="Close">
                        <span class="fa fa-times text-white"></span>
                    </a>
                </div>
                <div class="modal-body overflow-auto pl-3 pr-4 bg-gray-2">
                    <table class="table table-hover">
                        <thead class="bg-blue text-white">
                            <tr class="text-center">
                                <td class="align-middle font-11">CUENTA</td>
                                <td class="align-middle font-11">FECHA</td>
                                <td class="align-middle font-11">MONTO</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="pagos==''" class="text-center">
                                <td colspan="3">Sin historial de pagos</td>
                            </tr>
                            <tr v-for="(item,index) in pagos" :key="index" v-else>
                                <td class="text-center">{{item.cuenta? item.cuenta:'-'}}</td>
                                <td class="text-center">{{item.fecha? item.fecha:'-'}}</td>
                                <td class="text-right">{{item.pagos? 'S/.'+item.pagos:'-'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:["idCliente"],
        data() {
            return {
                pagos:[],
            }
        },
        created(){
           this.listaPagos();
        },
        methods:{
            listaPagos(){
                this.pagos=[];
                const id= this.idCliente;
                axios.get("listaPagos/"+id).then(res=>{
                    if(res.data){
                        this.pagos=res.data;                        
                    }
                })
            },
        }
    }
</script>
