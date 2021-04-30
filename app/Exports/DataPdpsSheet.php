<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;//mantener los ceros
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use App\Reporte;

class DataPdpsSheet implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison
{
    
    use Exportable;

    public function __construct(Request $rq)
    {
        // $cartera,$fecInicio,$fecFin,$tipoFecha
        $this->rq = $rq;
        // $this->cartera = $cartera;
        // $this->fecInicio = $fecInicio;
        // $this->fecFin = $fecFin;
        // $this->tipoFecha = $tipoFecha;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Reporte::reporteDataPdps($this->rq);
        return  collect($res);
    }

    public function headings(): array
    {
        return [
            "Código",
            "Call",
            "Nombre",
            "Cartera",
            "Fecha de Gestión",
            "Hora",
            "Minuto",
            "Segundo",
            "Usuario / Gestor",
            "Perfil",
            "Medio",
            "Acción",
            "Fecha de Visita",
            "Ubic.",
            "Rspta.",
            "Motivo No Pago",
            "Detalle",
            "Fecha Compromiso",
            "Monto Compromiso",
            "Moneda",
            "Fecha Confirmación",
            "Monto Confirmación",
            "Dirección",
            "Distrito",
            "Provincia",
            "Departamento"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'K' => '@',
            'F' => '@',
            'G' => '0',
            'H' => '@',
            'E' => 'm/d/yy h:mm',
            'M' => 'yyyy-mm-dd',
            'R' => 'yyyy-mm-dd',
            'U' => 'yyyy-mm-dd',
            'S' => '0.00',
            'V' => '0.00'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);   
    }
}
