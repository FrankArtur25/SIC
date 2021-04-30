@extends('admin.panel')

@section('menu')
    @include('admin.mantenimiento.menuMantenimiento')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-users pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Registrar Gestor</p>
                <p class="mb-0">Completar los campos obligatorios</p>
            </div>
        </div>
        <div class="py-3">
            <form-gestor
                :carteras="{{$carteras}}"
            />
        </div>
    </div>         
@endsection

