@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-handshake pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Plazos de Pagos</p>
                <p class="mb-0">Reporte por día</p>
            </div>
        </div>
        <div class="py-3">
            <pdps :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection
