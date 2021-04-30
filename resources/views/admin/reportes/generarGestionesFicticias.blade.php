@extends('admin.panel')

@section('menu')
    @include('admin.reportes.menuReportes')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-plus-square pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Generar Gestiones Ficticias</p>
                <p class="mb-0">Por Fecha de Gestión</p>
            </div>
        </div>
        <div class="py-3">
            <generar-gestiones-ficticias
             :carteras="{{$carteras}}"
             :paletas="{{$paletas}}"
             />
        </div>
    </div>         
@endsection
