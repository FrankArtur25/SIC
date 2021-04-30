@extends('admin.panel')

@section('menu')
    @include('admin.predictivo.menuPredictivo')
@endsection

@section('panel-contenido')  
    <div class="px-4">
        <div class="d-flex">
            <i class="fa fa-microphone-alt pr-2 fa-lg pt-3 text-green"></i>
            <div>
                <p class="subheader-title mb-0">Campañas</p>
                <p class="mb-0">IVR</p>
            </div>
        </div>
        <div class="py-3">
            <lista-ivr :carteras="{{$carteras}}" />
        </div>
    </div>         
@endsection
