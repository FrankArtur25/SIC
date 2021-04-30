@extends('admin.panel')

@section('menu')
    @include('admin.indicadores.menuIndicadores')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-layer-group pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Plan de Trabajo</p>
                <p class="mb-0">Crea un plan de trabajo</p>
            </div>
        </div>
        <div class="py-3">
            <form-plan :carteras="{{$carteras}}" :rpta="{{$respuestas}}"/>
        </div>
    </div>         
@endsection
