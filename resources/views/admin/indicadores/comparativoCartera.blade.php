@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fas fa-chart-bar pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Comparativo a la fecha y últimos 3 Cierres.</p>
                <p class="mb-0">Comparativo Por Cartera</p>
            </div>
        </div>
        <div class="py-3">
            <comparativo-cartera :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection
