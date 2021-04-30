<template>
        <div >
            <div class="mt-3 pl-4">
                <div class="d-flex flex-wrap" style="gap:8px">
                    <a href="" data-toggle="modal" data-target="#modalPagos" class="btn btn-blue" @click.prevent="verPagos()">
                        Ver Pagos
                    </a>
                    <a href="" data-toggle="modal" data-target="#modalCarteras" class="btn btn-blue" @click.prevent="verCarteras()">
                        Ver Carteras
                    </a>
                    </div>         
            </div><br>

            <!-- Modal RESUMEN PAGOS-->
            <div class="form-group row">
                <div class="modal fade bd-example-modal-md" id="modalPagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header btn-blue">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Pagos a la Fecha</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center" id="modal-body">
                                <div v-if="loading==true" class="d-flex justify-content-center">
                                    <div class="text-center">
                                        <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="viewTable">
                                    <div class="table-responsive">
                                        <table class="table table-4 table-sm table-borderless table-hover table-responsive-sm" style="padding: .4rem .2rem !important;font-size:12px !important;border: 0px !important;">
                                            <thead class="text-center">
                                                <tr class="">
                                                    <th>CARTERA</th>
                                                    <th>ÚLTIMA FECHA DE PAGO</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center text-dark">
                                                <tr v-for="(item,index) in carterasPagos" :key="index" class="text-center">
                                                    <td class="text-center">{{item.car_nom}}</td>
                                                    <td class="text-center" style="color:blue;">
                                                        <span v-if="item.fecha!='Sin Pagos a la Fecha'" style="color:blue;">{{item.fecha}}</span>
                                                        <span v-else style="color:red;">{{item.fecha}}</span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer btn-blue"> <!--excel pu-->
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal RESUMEN PAGOS -->

            <!-- Modal RESUMEN CARTERAS-->
            <div class="form-group row">
                <div class="modal fade bd-example-modal-md" id="modalCarteras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header btn-blue">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Carteras</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center" id="modal-body">
                                <div v-if="loading==true" class="d-flex justify-content-center">
                                    <div class="text-center">
                                        <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="viewTable">
                                    <div class="table-responsive">
                                        <table class="table table-4 table-sm table-borderless table-hover table-responsive-sm">
                                            <thead class="text-center">
                                                <tr class="">
                                                    <th>CARTERA</th>
                                                    <th class='text-white'>-----------</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center text-dark">
                                                <tr v-for="(item,index) in carteras" :key="index" class="text-center">
                                                    <td class="text-center">{{item.car_nom}}</td>
                                                    <td class="text-center" style="color:blue;">
                                                        <span v-if="item.estado=='cargadas'"><i class="fas fa-circle" style="color:green"></i></span>
                                                        <span v-else><i class="fas fa-circle" style="color:red"></i></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer btn-blue"> <!--excel pu-->
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal RESUMEN CARTERAS -->

        </div>
</template>

<script>
    export default {
        data() {
            return {
                pagos: [],
                carterasPagos:[],
                carteras: [],
                carterasCargadas: [],
                viewTable:false,
                loading : false,
                mensaje:''
            }
        },
        methods:{
            verPagos(){
                this.viewTable=false;
                this.loading=true;
                this.pagos=[];
                this.carterasPagos=[];
                this.mensaje='';
                axios.post("infoactualizacionpagos").then(res=>{
                    if(res.data){
                        var datos=res.data;
                        this.loading=false;
                        this.viewTable=true;
                        this.carterasPagos=datos.carterasPagos;
                        this.pagos=datos.pagos;
                        this.carterasPagos.forEach(item=>{
                            let itemCartera = this.pagos.find(el=>parseInt(el.car_id) == parseInt(item.car_id));
                            if(!itemCartera) return;
                            item.fecha = itemCartera.fecha;
                        });
                    }
                })
            },
            verCarteras(){
                this.viewTable=false;
                this.loading=true;
                this.carteras=[];
                this.carterasCargadas=[];
                this.mensaje='';
                axios.post("infoactualizacioncarteras").then(res=>{
                    if(res.data){
                        var datos=res.data;
                        this.loading=false;
                        this.viewTable=true;
                        this.carterasCargadas=datos.carterasCargadas;
                        this.carteras=datos.carteras;
                        this.carteras.forEach(item=>{
                            let itemEstado = this.carterasCargadas.find(el=>parseInt(el.car_id) == parseInt(item.car_id));
                            console.log(itemEstado);
                            if(!itemEstado) return;
                            item.estado = itemEstado.estado;
                        });
                        console.log(this.carteras);
                    }
                })
            }
        }
    }
</script>
