<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use App\Predictivo;

class RepositorioExport implements FromCollection,WithCustomCsvSettings
{
    use Exportable;

    public function __construct($idcampana)
    {
        $this->idcampana = $idcampana;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Predictivo::listaRepositorio($this->idcampana);
        return  collect($res);
    }
    
    // configuracion de csv
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => ''
        ];
    }
}
