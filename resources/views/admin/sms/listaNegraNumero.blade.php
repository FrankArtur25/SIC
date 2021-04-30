@extends('admin.panel')

@section('menu')
    @include('admin.sms.menuSms')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-trash pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Lista Negra</p>
                <p class="mb-0">Evita el envío de SMS </p>
            </div>

        </div>
        <div class="py-3">
            <sms-lista-negra-numero/>
        </div>
    </div>         
@endsection
