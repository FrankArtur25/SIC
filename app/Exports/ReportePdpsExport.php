<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;
use App\Exports;

class ReportePdpsExport implements WithMultipleSheets
{
    
    use Exportable;

    public function __construct(Request $rq)
    {
        $this->rq = $rq;
    }


    public function sheets(): array
    {
        $sheets = [];
        
        $sheets[0]=new TablaPdpsSheet($this->rq);
        // $sheets[0]='';
        $sheets[1]=new DataPdpsSheet($this->rq);

        return $sheets;
    }
}
