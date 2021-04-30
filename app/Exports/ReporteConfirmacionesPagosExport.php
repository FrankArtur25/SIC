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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Http\Request;
use App\Reporte;
use Carbon\Carbon;

class ReporteConfirmacionesPagosExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison,ShouldAutoSize
{
    
    use Exportable;

    private $cantRegistro=0;
    private $abc=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    public function __construct(Request $rq)
    {
        $this->rq = $rq;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Reporte::reporteConfirmacionesPagos($this->rq);
        $this->cantRegistros=count($res);
        return  collect($res);
    }

    public function headings(): array
    {
        $titulos1=['COMPARATIVA DE PAGOS','','PAGOS OFICIALES'];
        $titulos2=['CARTERA','CONFIRMACIONES','PAGOS PROCESADOS','PAGOS GEST','PAGOS SUPERV (CANALES)','SIN GESTION','CONFIRMACIONES NO CONSIDERADAS'];
        $head=[$titulos1,$titulos2];
        return $head;
    }

    public function columnFormats(): array
    {
        $formato=[
            'A' => '@',
            'B'=>'#,##0.00',
            'C'=>'#,##0.00',
            'D'=>'#,##0.00',
            'E'=>'#,##0.00',
            'F'=>'#,##0.00',
            'G'=>'#,##0.00'
        ];
        
        return $formato;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('C1:F1');
        $sheet->getStyle('A1:G2')->getFont()->setBold(true);
        $sheet->getStyle('A1:G2')->getFont()->getColor()->setARGB("f5f5f5");
        $background = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '04407d'),
            )
        );
        $sheet ->getStyle('A1:C2')->applyFromArray($background);
        $sheet ->getStyle('C1:F1')->applyFromArray($background);
        $sheet ->getStyle('G1:G2')->applyFromArray($background);

        $background_2 = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFC000'),
            )
        );
        $sheet ->getStyle('D2:F2')->applyFromArray($background_2);

        $borders = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );

        for($i=0;$i<=$this->cantRegistros+2;$i++){
            for($j=0;$j<=6;$j++){
                $sheet ->getStyle($this->abc[$j].$i)->applyFromArray($borders);
            }
            if($i==$this->cantRegistros+2){
                $sheet->getStyle("A$i:Y$i")->getFont()->setBold(true);
            }
        }
    }
}
