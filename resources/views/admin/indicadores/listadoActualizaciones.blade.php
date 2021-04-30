@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-sync pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Actualizaciones</p>
                <p class="mb-0">Actualizaciones de Pagos y Carteras a la fecha</p>
            </div>
        </div>
        <div class="py-3">
            <listado-actualizaciones/>
        </div>
    </div>         
@endsection