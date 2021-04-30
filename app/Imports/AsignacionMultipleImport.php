<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Empleado;

class AsignacionMultipleImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        
        return $rows;
    }
}
