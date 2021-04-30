<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\PagoCliente;
use Carbon\Carbon;

class PagosBaseImport implements ToModel, WithBatchInserts,WithChunkReading,ShouldQueue
{
    public function __construct($cartera)
    {
        $this->cartera = $cartera;
    }

    public function model(array $row)
    {
        return new PagoCliente([
            'car_id_FK'=> $this->cartera,
            'pag_cli_cod'=> $row[0],
            'pag_cli_pro'=> $row[1],
            'pag_cli_mon'=> $row[2],
            'pag_cli_fec'=> $row[3],
            'pag_cli_est'=> 0,
            'pag_grup_prod'=> $row[4],
            'fecha_add'=>Carbon::now()
        ]);
    }
    
    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }

}
