@extends('admin.panel')

@section('menu')
    @include('admin.reportes.menuReportes')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-coins pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Carga de Pagos</p>
                <p class="mb-0">Por Cartera</p>
            </div>
        </div>
        <div class="py-3">
            <cargar-pagos
            :carteras="{{$carteras}}"
            />
        </div>
    </div>         
@endsection
