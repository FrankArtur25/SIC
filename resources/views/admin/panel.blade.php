@extends('layouts.app')

@section('content')
    <div class="contenedor-general">
        <div class="content-menu text-white">
            <div class="logo d-flex px-3 pt-3 border-bottom">
                <img src="{{asset('img/logo.png')}}" width="45px" height="40px" class="pr-1">
                <div class="pb-4">
                    <p class="mb-0 font-bold">Crédito y Cobranzas S.A.C</p>
                    <p class="mb-0 font-12">Ingeniería en su cobranza</p>
                </div>
            </div>
            <!-- menu -->
            @yield('menu')
            <!-- end menu -->
        </div>
        <div class="content-body">
            <div class="contenedor-body pl-1">
            <nav class="navbar navbar-expand-lg navbar-transparent pt-3 pb-2 bg-white">
                        <div class="container-fluid px-0">
                            <div class="navbar-wrapper d-flex">
                                <a href="#" class="icono-bars waves-effect" onclick="menu()"><i class="fa fa-bars fa-lg"></i></a>
                                <a href="{{route('menu')}}" class="icono-bars waves-effect" title="Menu Principal"><i class="fa fa-home fa-lg"></i></a>
                            </div>
                            <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navigation"  aria-expanded="false" aria-label="Toggle navigation">
                                 <img src="{{asset('img/center.jpeg')}}" alt="" width="35px" height="35px" class=" rounded-circle border">
                            </button>
                            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                                    <ul class="navbar-nav">
                                        <li class="nav-item pt-1 px-2 text-right">
                                            <p class="font-12 mb-0"><b>{{auth()->user()->emp_nom}}</b><br>{{session()->get('datos')->perfil}}</p>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a href="#" class="nav-link dropdown-toggle px-0" id="dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="{{asset('img/center.jpeg')}}" alt="" width="35px" height="35px" class=" rounded-circle border">
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
                <!-- End Navbar -->
                @yield('panel-contenido')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function menu(){
            $('.content-menu').toggleClass('abrir');            
            $('.content-body').toggleClass('p-left');            
        }
    </script>
@endsection