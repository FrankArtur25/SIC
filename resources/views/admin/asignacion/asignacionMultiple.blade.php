@extends('admin.panel')

@section('menu')
    @include('admin.asignacion.menuAsignacion')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-users pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Asignación Múltiple</p>
                <p class="mb-0">Por cartera</p>
            </div>
        </div>
        <div class="py-3">
            <asignacion-multiple
             :carteras="{{$carteras}}"
             :codigo="{{$codAleatorio}}"
             />
        </div>
    </div>         
@endsection
