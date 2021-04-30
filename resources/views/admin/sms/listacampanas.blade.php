@extends('admin.panel')

@section('menu')
    @include('admin.sms.menuSms')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-sms pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Lista de Campañas</p>
                <p class="mb-0">Detalle</p>
            </div>
        </div>
        <div class="py-3">
            <sms-list-campanas :carteras="{{$carteras}}" :rol="{{$rol}}"/>
        </div>
    </div>         
@endsection
