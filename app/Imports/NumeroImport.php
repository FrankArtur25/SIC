<?php

namespace App\Imports;

// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithBatchInserts;
// use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\ListaNegra;
use Carbon\Carbon;

class NumeroImport implements ToModel
{

    public function model(array $row)
    {
        $usuario=auth()->user()->emp_cod;
        return new ListaNegra([
           'bl_numero' => $row[0],
           'bl_c_n'    => 'N', 
           'emp_id_FK' => $usuario,
           'fecha_add' => Carbon::now()
        ]);
    }

    // public function batchSize(): int
    // {
    //     return 5000;
    // }
    
    // public function chunkSize(): int
    // {
    //     return 5000;
    // }
}
