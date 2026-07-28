@extends('layouts.app')

@section('title', 'Iniciar Sesion')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle" style="font-size: 3rem; color: {{$empresa->colorUno}}"></i>
                        <h3 class="fw-bold mt-2" style="color: {{$empresa->colorUno}}">Iniciar Sesion</h3>
                        <p class="text-muted">Ingresa a tu cuenta para continuar</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cliente.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo electronico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contrasena</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Tu contrasena" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white shadow-sm" style="background-color: {{$empresa->colorUno}}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-0 text-muted">No tienes cuenta? <a href="{{ route('cliente.register.form') }}" class="fw-bold text-decoration-none" style="color: {{$empresa->colorUno}}">Registrate aqui</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endsection
