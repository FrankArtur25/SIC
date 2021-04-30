@extends('admin.panel')

@section('menu')
    @include('admin.mantenimiento.menuMantenimiento')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-user-tie pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Registrar Usuario</p>
                <p class="mb-0">Completar los campos obligatorios</p>
            </div>
        </div>
        <div class="py-3">
            <form-empleado 
                :carteras="{{$carteras}}"
                :locales="{{$locales}}"
                :calls="{{$calls}}"
                :codigoaleatorio="{{$codAleatorio}}"
            />
        </div>
    </div>         
@endsection

