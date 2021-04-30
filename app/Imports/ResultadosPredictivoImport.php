<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
use App\Predictivo;

class ResultadosPredictivoImport implements ToCollection
{

    public function __construct($idcampana)
    {
        $this->idcampana = $idcampana;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Predictivo::actualizarResultados($this->idcampana,$row[2],$row[1],$row[0]);
        }
    }
    // public function model(array $row)
    // {
        
    //     return new ListaNegra([
    //        'bl_numero' => $row[0],
    //        'bl_c_n'    => 'N', 
    //        'emp_id_FK' => $usuario,
    //        'fecha_add' => Carbon::now()
    //     ]);
    // }
}
