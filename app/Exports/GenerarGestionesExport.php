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

class GenerarGestionesExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison,ShouldAutoSize
{
    
    use Exportable;

    private $cantRegistro=0;
    private $cantColumnas=0;
    private $abc=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                       'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

    public function __construct(Request $rq)
    {
        $this->rq = $rq;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Reporte::generarGestionesFicticias($this->rq);
        $this->cantRegistros=count($res);
        return  collect($res);
    }

    public function headings(): array
    {
        $titulos1=['RESPUESTA','DETALLE'];
       
        $head=[$titulos1];
        return $head;
    }

    public function columnFormats(): array
    {
        $formato=[
            'A' => '@',
            'B'=>'#,##0.00'
        ];
        
        return $formato;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A1:B1')->getFont()->getColor()->setARGB("f5f5f5");
        $background = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '04407d'),
            )
        );
        $sheet ->getStyle('A1:B1')->applyFromArray($background);
        /*$sheet ->getStyle('C1:F1')->applyFromArray($background);
        $sheet ->getStyle('G1:G2')->applyFromArray($background);*/

        /*$background_2 = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFC000'),
            )
        );
        $sheet ->getStyle('D2:F2')->applyFromArray($background_2);*/

        $borders = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );

        for($i=0;$i<=$this->cantRegistros+1;$i++){
            for($j=0;$j<=1;$j++){
                $sheet ->getStyle($this->abc[$j].$i)->applyFromArray($borders);
            }
            if($i==$this->cantRegistros+2){
                $sheet->getStyle("A$i:Y$i")->getFont()->setBold(true);
            }
        }
    }
}
