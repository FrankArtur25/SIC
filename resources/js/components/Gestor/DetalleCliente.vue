<template>
    <div>
        <div class="table-responsive">
            <table width="100%" v-for="(item,index) in info" :key="index">
                <tr class="font-12"> 
                    <td class="text-right pr-1">Código</td>
                    <td class=""><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{item.cli_cod? item.cli_cod:'-'}}</label></td>
                    <td class="font-11 pr-1 text-right">DNI/RUC</td>
                    <td><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.doc)}}</label></td>
                </tr>
                <tr class="font-12" v-if="tipoacceso!=2"> 
                    <td class="text-right pr-1">Gest. Telf.</td>
                    <td colspan="3"><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(datos[0].gestor)}}</label></td>
                </tr>
                <tr class="font-12"> 
                    <td class="text-right pr-1">Dirección</td>
                    <td colspan="3"><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.direccion)}}</label></td>
                </tr>
                <tr class="font-12"> 
                    <td class="text-right pr-1">Dist.</td>
                    <td><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.distrito)}}</label></td>
                    <td class="text-right pr-1">Prov.</td>
                    <td><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.provincia)}}</label></td>
                </tr>
                <tr class="font-12"> 
                    <td class="text-right pr-1">Depto.</td>
                    <td><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.departamento)}}</label></td>
                    <td class="text-right pr-1">Prioridad</td>
                    <td><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">-</label></td>
                </tr>
                <tr class="font-12">
                    <td class="text-right pr-1">C. Laboral</td>
                    <td colspan="3"><label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.laboral)}}</label></td>
                </tr>
                <tr class="font-12"> 
                    <td class="text-right pr-1">Sueldo</td>
                    <td colspan="3">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <label class="form-control font-12 form-control-sm mb-1 w-100 h-100">S/.{{formatoVacio(item.sueldo)}}</label>
                            </div>
                            <div class="d-flex">
                                <label class="px-1 pt-2">Entidades</label>
                                <label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.entidades)}}</label>
                            </div>
                            <div class="d-flex">
                                <label class="px-1 pt-2">Score</label>
                                <label class="form-control font-12 form-control-sm mb-1 w-100 h-100">{{formatoVacio(item.score)}}</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="font-12"> 
                    <td class="text-right pr-1">Email</td>
                    <td colspan="3">
                        <div class="input-group mt-1">
                            <input type="text" class="form-control font-12 " id="inlineFormInputGroupUsername"  v-model="dataEmail.email">
                            <div class="input-group-prepend">
                                <a href="" @click.prevent="actualizarEmail()" class="">
                                    <div class="input-group-text"><i class="fa fa-sync"></i></div>
                                </a>
                            </div>
                        </div>
                        <small v-if="msjEmail" class="text-green">{{msjEmail}}</small>
                    </td>
                </tr>
            </table>
        </div><br>
    </div>
</template>

<script>
    export default {
        props:["datos","info","tipoacceso"],
        data() {
            return {
                dataEmail:{email:this.formatoVacio(this.info[0].email),idcliente:this.datos[0].id}, 
                msjEmail:''               
            }
        },
        methods:{
            actualizarEmail(){
                this.msjEmail='';
                axios.put("updateEmail",this.dataEmail).then(res=>{
                    if(res.data=="ok"){
                        this.msjEmail='Registro Actualizado!';
                    }
                });
            },
            formatoVacio(item){
                if(item=='' || item=='null' || item==' ' || item==null){
                    return '-';
                }else{
                    return item;
                }
            }
        }
    }
</script>
