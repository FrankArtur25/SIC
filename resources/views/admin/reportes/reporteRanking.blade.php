@extends('admin.panel')

@section('menu')
    @include('admin.reportes.menuReportes')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-coins pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Reporte Ranking Gestor</p>
                <p class="mb-0">Por fecha</p>
            </div>
        </div>
        <div class="py-3">
            <reporte-ranking
             :carteras="{{$carteras}}"
             :calls="{{$calls}}"
             />
        </div>
    </div>         
@endsection
