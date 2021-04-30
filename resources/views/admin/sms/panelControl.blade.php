@extends('admin.panel')

@section('menu')
    @include('admin.sms.menuSms')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-desktop pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Panel de Control</p>
                <p class="mb-0">Monitoreo de Campañas</p>
            </div>

        </div>
        <div class="py-3">
            <sms-panel-control/>
        </div>
    </div>         
@endsection
