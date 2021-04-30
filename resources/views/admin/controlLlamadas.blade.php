@extends('admin.panel')

@section('menu')
    @include('admin.sms.menuSms')
@endsection

@section('panel-contenido')           
    <div class="contenedor">
        <control-llamadas :carteras="{{$carteras}}"/>
    </div>
@endsection
