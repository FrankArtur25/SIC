@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-chart-line pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Indicadores Operativos</p>
                <p class="mb-0">Comparativo de 3 meses</p>
            </div>
        </div>
        <div class="py-3">
            <indicadores-operativos :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection
