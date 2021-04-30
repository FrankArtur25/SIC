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

class ReporteRankingExport implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison,ShouldAutoSize
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
        $res=Reporte::reporteRankingGestores($this->rq);
        $this->cantRegistros=count($res);
        return  collect($res);
    }

    public function headings(): array
    {
        $dias=[];
        $fechaInicio = Carbon::parse($this->rq->fechaInicio);
        $fechaFin = Carbon::parse($this->rq->fechaFin);
        $diasDiferencia = ($fechaFin->diffInDays($fechaInicio));
        $this->cantColumnas=5+$diasDiferencia;
        for($i=0;$i<=$diasDiferencia;$i++){
            $fecha = Carbon::parse($this->rq->fechaInicio);
            if($i==0){
                $dias[0]=substr($fechaInicio,0,10);
            }else{
                $dias[$i]=substr($fecha->addDays($i),0,10);
            }
        }
        
        $primerDia=substr($this->rq->fechaInicio,0,7)."-01";
        $weekNum = date("W",strtotime(date($this->rq->fechaInicio))) - date("W",strtotime(date($primerDia))) + 1;
        $titulos1=['A'=>'RANK SEMANA '.$weekNum];
        $titulos2=['Gestor'];

        foreach ($dias as $dia) {
            array_push($titulos2,$dia);
        }
        array_push($titulos2,'Pagos Semana '.$weekNum);
        array_push($titulos2,'Meta Mensual');
        array_push($titulos2,'Meta Semanal');
        array_push($titulos2,'Desfase Semanal');

        $head=[$titulos1,$titulos2];
        return $head;
    }

    public function columnFormats(): array
    {
        $formato=['A' => '@'];
        for($i=1;$i<=$this->cantColumnas;$i++){
            $formato[$this->abc[$i]]='#,##0.00';
        }
        return $formato;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->getFont()->setBold(true);
        $sheet->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->getFont()->getColor()->setARGB("f5f5f5");
        $background = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '04407d'),
            )
        );
        $sheet ->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->applyFromArray($background);

        $borders = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );

        for($i=0;$i<=$this->cantRegistros+2;$i++){
            for($j=0;$j<=$this->cantColumnas;$j++){
                $sheet ->getStyle($this->abc[$j].$i)->applyFromArray($borders);
            }
            if($i==$this->cantRegistros+2){
                $sheet->getStyle("A$i:Y$i")->getFont()->setBold(true);
            }
        }
    }
}
