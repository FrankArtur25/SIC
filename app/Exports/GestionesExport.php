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
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;//mantener los ceros
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Reporte;

class GestionesExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison
{
    
    use Exportable;

    public function __construct($cartera,$fecInicio,$fecFin,$perfil)
    {
        $this->cartera = $cartera;
        $this->fecInicio = $fecInicio;
        $this->fecFin = $fecFin;
        $this->perfil = $perfil;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Reporte::reporteGeneralGestiones($this->cartera,$this->fecInicio,$this->fecFin,$this->perfil);
        return  collect($res);
    }

    public function headings(): array
    {
        return [
            "Código",
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
            'J' => '@',
            'E' => '@',
            'F' => '0',
            'G' => '@',
            'D' => 'm/d/yy h:mm',
            'L' => 'yyyy-mm-dd',
            'Q' => 'yyyy-mm-dd',
            'T' => 'yyyy-mm-dd',
            'R' => '0.00',
            'U' => '0.00'
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
