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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use App\Empleado;

class BitacoraRepositorioAsignacionExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison,ShouldAutoSize
{
    
    use Exportable;

    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Empleado::descargarBitacoraRepositorio($this->codigo);
        return  collect($res);
    }

    public function headings(): array
    {
        return [
            "Código",
            "Asignación Actual",
            "Asignación Realizada",
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFont()->getColor()->setARGB("f5f5f5");
        $background = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '04407d'),
            )
        );
        $sheet ->getStyle('A1:C1')->applyFromArray($background);

    }
}
