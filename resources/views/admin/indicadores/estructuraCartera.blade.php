@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-sitemap pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Estructura Por Cartera</p>
                <p class="mb-0">Por Cartera y Gestión</p>
            </div>
        </div>
        <div class="py-3">
            <estructura-cartera :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection

