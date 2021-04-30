@extends('admin.panel')

@section('menu')
    @include('admin.asignacion.menuAsignacion')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-users pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Bitacora Asignación</p>
                <p class="mb-0">Máx de Duración en SIC: 2 semanas</p>
            </div>
        </div>
        <div class="py-3">
            <bitacora-asignacion
                :carteras="{{$carteras}}"
            />
        </div>
    </div>         
@endsection
