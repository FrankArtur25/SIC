@extends('admin.panel')

@section('menu')
    @include('admin.incidencias.menuIncidencias')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-tasks pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Lista de Incidencias</p>
                <p class="mb-0">xxx</p>
            </div>
        </div>
        <div class="py-3">
            <list-incidencias 
                :bdatos="{{$datos}}" 
                :tipoacceso="'{!! ( auth()->user()->emp_tip_acc ?? '' ) !!}'"
            />
        </div>
    </div>         
@endsection
