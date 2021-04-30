@extends('layouts.app')

@section('content')
    <integracion
        :userlogeado="'{!! ( auth()->user()->emp_nom ?? '' ) !!}'" 
        :idlogeado="'{!! ( auth()->user()->emp_id ?? '' ) !!}'"
        :tipoacceso="'{!! ( auth()->user()->emp_tip_acc ?? '' ) !!}'"
        :idcliente="{{$id}}"
        :detallegeneral="{{$datosgenerales}}"
    />
@endsection
