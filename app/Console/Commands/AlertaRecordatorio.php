<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Recordatorio;
use App\Telefono;
use App\Gestion;
use App\Cliente;
use App\Events\WebsocketsRecordatorio;

class AlertaRecordatorio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordatorio:alerta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Visualizar recordatorios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $telefonos=[];
        $validacion_contacto=[];
        $validacion_pdp=[];
        $gestiones=[];
        $datosgenerales=[];
        $datos=Recordatorio::listarRecordatorio();
        if(count($datos)>0){
            for($i=0;$i<count($datos);$i++){
                // $datosgenerales=[];
                $telefonos=Telefono::infoTelefonos($datos[$i]->id);
                $validacion_contacto=Gestion::validarContacto($datos[$i]->id);
                $validacion_pdp=Gestion::validarPDP($datos[$i]->id);
                $gestiones=cliente::historicoGestiones($datos[$i]->id);

                $datosgenerales=['recordatorios'=>$datos[$i],
                                'telefonos'=>$telefonos,
                                'validar_contacto'=>$validacion_contacto,
                                'pdps'=>$validacion_pdp,
                                'gestiones'=>$gestiones
                                ];
                
                 event(new WebsocketsRecordatorio($datosgenerales));
            }
        }else{
            event(new WebsocketsRecordatorio([]));
        }
    }
}
