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

class EstructuraCarteraCarteraExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles
{
    use Exportable;

    public function __construct($cartera,$ubicabilidad,$estructura,$valor,$mes)
    {
        $this->cartera = $cartera;
        $this->ubicabilidad = $ubicabilidad;
        $this->estructura = $estructura;
        $this->valor = $valor;
        $this->mes = $mes;
        
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        if($this->ubicabilidad=='todos'){
            $res=Estructura::descargarCodigosEstructuraCarteraTodo($this->cartera,$this->ubicabilidad,$this->estructura,$this->valor,$this->mes);
        }else{
            $res=Estructura::descargarCodigosEstructuraCarteraGestion($this->cartera,$this->ubicabilidad,$this->estructura,$this->valor,$this->mes);
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
