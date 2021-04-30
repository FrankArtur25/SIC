<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;
use App\Exports;
// use App\Exports\Confirmaciones\TablaConfirmacionesSheet;
// use App\Exports\Confirmaciones\DataConfirmacionesSheet;

class ReporteConfirmacionesExport implements WithMultipleSheets
{
    
    use Exportable;

    public function __construct(Request $rq)
    {
        $this->rq = $rq;
    }


    public function sheets(): array
    {
        $sheets = [];
        
        $sheets[0]=new TablaConfirmacionesSheet($this->rq);
        // $sheets[0]='';
        $sheets[1]=new DataConfirmacionesSheet($this->rq);

        return $sheets;
    }
}
