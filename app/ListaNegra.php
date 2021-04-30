<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaNegra extends Model
{
    protected $connection = 'mysql';
    protected $table = "creditoy_sms.blacklist";
    public $timestamps = false;
    protected $fillable = ['bl_numero','bl_c_n','emp_id_FK','fecha_add'];
}
