@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-stopwatch pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Resumen Gestión Consolidada</p>
                <p class="mb-0">Reporte por cartera</p>
            </div>
        </div>
        <div class="py-3">
            <reporte-resumen-gestion-consolidada/>
        </div>
    </div>         
@endsection
