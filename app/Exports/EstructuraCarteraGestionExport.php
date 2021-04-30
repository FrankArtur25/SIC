<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Estructura;

class EstructuraCarteraGestionExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles
{
    use Exportable;

    public function __construct($cartera,$tipo,$estructura,$valor,$fecInicio,$fecFin)
    {
        $this->cartera = $cartera;
        $this->tipo = $tipo;
        $this->estructura = $estructura;
        $this->valor = $valor;
        $this->fecInicio = $fecInicio;
        $this->fecFin = $fecFin;
        
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        if($this->tipo=='pagos'){
            $res=Estructura::descargaCodigosEstructuraCarteraPagos($this->cartera,$this->tipo,$this->estructura,$this->valor,$this->fecInicio,$this->fecFin);
        }else{
            $res=Estructura::descargarCodigosEstructuraGestionCartera($this->cartera,$this->tipo,$this->estructura,$this->valor,$this->fecInicio,$this->fecFin);
        }
        return  collect($res);
    }

    public function headings(): array
    {
        return [
            "Código",
            "Cliente"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Y1')->getFont()->setBold(true);
        $sheet->getStyle('A1:Y1')->applyFromArray(array(
            'fill' => array(
                'type'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => '#07417a')
            )
        ));
        
    }
}
