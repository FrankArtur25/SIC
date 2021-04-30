@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-transparent pt-3 pb-2 bg-white">
    <div class="container-fluid px-0">
        <div class="navbar-wrapper d-flex">
            <div class="logo d-flex">
                <img src="img/logo.png" width="45px" height="40px" class="pr-2">
                <div>
                    <h5 class="mb-0 ">Crédito y Cobranzas S.A.C</h5>
                    <p class="mb-0 font-12">Ingeniería en su cobranza</p>
                </div>
            </div>
        </div>
        <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navigation"  aria-expanded="false" aria-label="Toggle navigation">
                <img src="img/center.jpeg" alt="" width="35px" height="35px" class=" rounded-circle border">
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
                <ul class="navbar-nav">
                    <li class="nav-item pt-1 px-2 text-right">
                        <p class="font-12 mb-0"><b>{{auth()->user()->emp_nom}}</b><br>{{session()->get('datos')->perfil}}</p>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle px-0" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="img/center.jpeg" alt="" width="35px" height="35px" class=" rounded-circle border">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right mt-2" aria-labelledby="dropdown-configuracion">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
        </div>
    </div>
</nav>
<div class="menu-admin text-center p-4">
    <!-- menu sistemas -->
    @if(auth()->user()->emp_tip_acc==6)
    <p class="font-bold font-18 text-blue mb-0">Menú Sistemas</p><hr style="width:70px;border:1px solid;" class="text-blue">
    <div class="d-flex justify-content-center px-4 py-3 flex-wrap">
            <a href="{{route('mantenimiento')}}" class="menu-card p-3">
                <img src="{{asset('img/mantenimiento.svg')}}" alt="" class="img-fluid" width="140px" height="60px">
                <p class="mb-0 px-1 pt-2 pb-1 font215">Mantenimiento<br></p>
            </a>
            <a href="{{route('asignacion')}}" class="menu-card p-3">
                <img src="{{asset('img/asignacion.svg')}}" alt="" class="img-fluid" width="100px" height="60px">
                <p class="mb-0 px-1 pt-2 pb-1 font-15">Asignación<br>de Usuarios</p>
            </a>
            <a href="{{route('reportesgenerales')}}" class="menu-card p-3">
                <img src="{{asset('img/reportes.svg')}}" alt="" class="img-fluid" width="110px" height="60px">
                <p class="mb-0 px-1 pt-2 pb-1 font-15">Reportes<br>Generales</p>
            </a>
            <a href="{{route('predictivo')}}" class="menu-card p-3">
                <img src="{{asset('img/predictivo.svg')}}" alt="" class="img-fluid" width="105px" height="60px">
                <p class="mb-0 px-1 pt-2 pb-1 font-15">Predictivo e<br>IVR - Elastix</p>
            </a>
    </div>
    @endif
    <!-- menu otros perfiles -->
    <p class="font-bold font-18 text-blue mb-0 mt-4">Menú Principal</p><hr style="width:70px;border:1px solid;" class="text-blue">
    <div class="d-flex justify-content-center px-4 py-3 flex-wrap">
        <a href="{{route('clientes')}}" class="menu-card p-3">
            <img src="{{asset('img/buscar.svg')}}" alt="" class="img-fluid mt-2">
            <p class="mb-0 px-1 pt-2 pb-1 font-15">Consultar/Gestión Clientes</p>
        </a>
        <a href="{{route('indicadores')}}" class="menu-card p-3">
            <img src="{{asset('img/indicador.svg')}}" alt="" class="img-fluid" width="100px" height="40px">
            <p class="mb-0 px-1 pt-2 pb-1 font-15">Indicadores de Gestión</p>
        </a>
        <a href="{{route('sms')}}" class="menu-card p-3">
            <img src="{{asset('img/sms.svg')}}" alt="" class="img-fluid">
            <p class="mb-0 px-1 pt-2 pb-1 font-15">Programación y Envío SMS</p>
        </a>
        <a href="{{route('incidencias')}}" class="menu-card p-3">
            <img src="{{asset('img/incidencias.svg')}}" alt="" class="img-fluid" width="110px" height="60px">
            <p class="mb-0 px-1 pt-2 pb-1 font-15">Incidencias<br>RRHH</p>
        </a>
    </div>
</div>
@endsection
