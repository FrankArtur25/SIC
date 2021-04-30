@extends('layouts.app')

@section('contentLogin')
<div class="container bg-gray-2" style="height:100vh;min-width:100vw;padding-top:100px;">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-top-blue">
                <div class="card-body">
                    <div class="logo d-flex justify-content-center mb-2">
                        <img src="img/logo.png" width="45px" height="40px" class="pr-2">
                        <div>
                            <h5 class="mb-0 ">Crédito y Cobranzas S.A.C</h5>
                            <p class="mb-0 font-12">Ingeniería en su cobranza</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-blue font-bold mb-0">ACCESO AL SISTEMA</p>
                    </div>
                    <div class="px-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="emp_cod" class="col-form-label text-md-right">Usuario</label>
                                <input id="emp_cod" type="text" class="form-control form-control-sm @error('emp_cod') is-invalid @enderror" name="emp_cod" value="{{ old('emp_cod') }}" required autofocus>
                                @error('emp_cod')
                                    <span class="invalid-feedback" role="alert">
                                        <small>{{ $message }}</small>
                                    </span>
                                @enderror
                            </div>
                        
                            <div class="form-group">
                                <label for="password" class="col-form-label text-md-right">Contraseña</label>
                                <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-blue">
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
