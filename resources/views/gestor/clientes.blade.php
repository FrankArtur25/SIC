@extends('layouts.app')

@section('content')
    <panel-gestor 
        :userlogeado="'{!! ( auth()->user()->emp_nom ?? '' ) !!}'" 
        :idlogeado="'{!! ( auth()->user()->emp_id ?? '' ) !!}'"
        :tipoacceso="'{!! ( auth()->user()->emp_tip_acc ?? '' ) !!}'"
        :userocm="'{!! ( auth()->user()->user_ocm ?? '' ) !!}'"
    />
@endsection
