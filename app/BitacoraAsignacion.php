<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitacoraAsignacion extends Model
{
    protected $connection = 'mysql';
    protected $table = "creditoy_asignacion.bitacora_repositorio";
    public $timestamps = false;
    protected $fillable = ['bit_asig_codigo_FK','bit_rep_cli_cod','bit_rep_emp_tel_cod','bit_rep_emp_cod_asig','bit_rep_estado'];
}
