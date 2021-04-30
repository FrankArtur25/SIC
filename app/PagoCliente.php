<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoCliente extends Model
{
    protected $connection = 'mysql';
    protected $table = "creditoy_cobranzas.pago_cliente_2";
    protected $fillable = ['car_id_FK','pag_cli_cod','pag_cli_pro','pag_cli_mon','pag_cli_fec','pag_cli_est','pag_grup_prod'];
    public $timestamps = false;
}
