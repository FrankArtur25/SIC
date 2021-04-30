@extends('admin.panel')

@section('menu')
    @include('admin.incidencias.menuIncidencias')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-exclamation-triangle pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Registrar Incidencia</p>
                <p class="mb-0">Informar sobre el problema</p>
            </div>
        </div>
        <div class="py-3">
            <form-incidencia :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection
