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

class TablaConfirmacionesSheet implements FromCollection, WithHeadings,WithColumnFormatting,WithStyles,WithStrictNullComparison,ShouldAutoSize
{
    
    use Exportable;
    
    private $cantRegistro=0;
    private $cantColumnas=0;
    private $abc=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    public function __construct(Request $rq)
    {
        $this->rq = $rq;
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        $res=Reporte::generarTablaConfirmaciones($this->rq);
        $this->cantRegistros=count($res);
        $columnas=explode(",",$this->rq->columnas);
        $calls=explode(",",$this->rq->calls);
        if($this->rq->estructura=='1'){
            $this->cantColumnas=count($columnas);
        }else{
            $this->cantColumnas=(count($columnas)*count($calls))+count($columnas);
        }
        return  collect($res);
    }

    public function headings(): array
    {
        $titulos1=['A'=>''];
        $titulos2=[];
        $callNom=['','CALL 01','CALL 02','NEGOCIADOR(A)','CALL03'];
        $columnas=explode(",",$this->rq->columnas);
        $calls=explode(",",$this->rq->calls);
        $cantColumnas=0;

        if($this->rq->cartera=='0'){
            $titulos2['A']='Cartera';
        }else{
            $titulos2['A']='Fecha';
        }

        if($this->rq->estructura=='1'){
            $cantColumnas=count($columnas);
        }else{
            $cantColumnas=(count($columnas)*count($calls))+count($columnas);
        }
        
        if(count($columnas)==1){
            if($columnas[0]==2){
                for($i=1;$i<=$cantColumnas;$i++){
                    $titulos2[$this->abc[$i]]='Cantidad';
                }
            }
            if($columnas[0]==3){
                for($i=1;$i<=$cantColumnas;$i++){
                    $titulos2[$this->abc[$i]]='Monto';
                }
            }
            $k=0;
            for($i=1;$i<=$cantColumnas;$i++){
                if(count($calls)>0){
                    if($i==$cantColumnas){
                        $titulos1[$this->abc[$i]]='TOTAL';
                    }else{
                        $titulos1[$this->abc[$i]]=$callNom[$calls[$k]];
                        $k++;
                    }
                }
            }
        }
        
        if(count($columnas)>1){
            $k=0;
            for($i=1;$i<=$cantColumnas;$i++){
                if($i%2==0){
                    $titulos1[$this->abc[$i]]='';
                    $titulos2[$this->abc[$i]]='Monto';
                }else{
                    $titulos2[$this->abc[$i]]='Cantidad';
                    if(count($calls)>0){
                        if($i==$cantColumnas-1){
                            $titulos1[$this->abc[$i]]='TOTAL';
                        }else{
                            $titulos1[$this->abc[$i]]=$callNom[$calls[$k]];
                            $k++;
                        }
                    }else{
                        $titulos1[$this->abc[$i]]='';
                    }
                }
            }
        }

        $head=[$titulos1,$titulos2];
        return $head;
    }
    
    public function columnFormats(): array
    {
        $formato=['A' => 'dd/mm/yy'];
        $columnas=explode(",",$this->rq->columnas);
        if(count($columnas)==1){
            if($columnas[0]==2){
                for($i=1;$i<=$this->cantColumnas;$i++){
                    $formato[$this->abc[$i]]='0';
                }
            }else{
                for($i=1;$i<=$this->cantColumnas;$i++){
                    $formato[$this->abc[$i]]='#,##0.00';
                }
            }
        }
        
        if(count($columnas)>1){
            for($i=1;$i<=$this->cantColumnas;$i++){
                if($i%2==0){
                    $formato[$this->abc[$i]]='#,##0.00';
                }else{
                    $formato[$this->abc[$i]]='0';
                }
            }
        }
        
        return $formato;
    }

    public function styles(Worksheet $sheet)
    {
        // head ..bg y color
        $sheet->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->getFont()->setBold(true);
        $sheet->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->getFont()->getColor()->setARGB("f5f5f5");
        $background = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '04407d'),
            )
        );
        $sheet ->getStyle('A1:'.$this->abc[$this->cantColumnas].'2')->applyFromArray($background);

        // borders
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

        // combinar celdas
        $columnas=explode(",",$this->rq->columnas);
        if(count($columnas)>1){
            for($i=1;$i<=$this->cantColumnas;$i++){
                if($i%2!=0){
                    $sheet->mergeCells(''.$this->abc[$i].'1:'.$this->abc[$i+1].'1');
                }
            }
        }
        
    }
}
