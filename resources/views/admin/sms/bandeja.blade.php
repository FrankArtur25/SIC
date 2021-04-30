@extends('admin.panel')

@section('menu')
    @include('admin.sms.menuSms')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-envelope pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Bandeja de Entrada</p>
                <p class="mb-0">Visualiza los mensajes de clientes</p>
            </div>
        </div>
        <div class="py-3">
            <sms-bandeja/>
        </div>
    </div>         
@endsection
