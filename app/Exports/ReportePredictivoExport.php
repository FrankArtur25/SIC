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
use App\Predictivo;

class ReportePredictivoExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison
{
    use Exportable;

    public function __construct($idCampana)
    {
        $this->idCampana = $idCampana;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Predictivo::reporteCampana($this->idCampana);
        return  collect($res);
    }

    public function headings(): array
    {
        return [
            "Gestor",
            "Total Llamadas",
            "Total Clientes",
            "Total de Contactos",
            "Contactabilidad",
            "Total PDP",
            "Monto PDP"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '0',
            'C' => '0',
            'D' => '0',
            'F' => '@',
            'G' => '0.00'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->applyFromArray(array(
            'fill' => array(
                'type'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => '#07417a')
            )
        ));
        
    }
}
