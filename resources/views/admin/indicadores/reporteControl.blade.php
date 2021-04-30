@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-desktop pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Reporte de Control</p>
                <p class="mb-0">Diario y Acumulado</p>
            </div>
        </div>
        <div class="py-3">
            <reporte-control />
        </div>
    </div>         
@endsection
