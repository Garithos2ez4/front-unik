@extends('layouts.app')

@section('title', 'Crear Cuenta')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill" style="font-size: 3rem; color: {{$empresa->colorUno}}"></i>
                        <h3 class="fw-bold mt-2" style="color: {{$empresa->colorUno}}">Crear Cuenta</h3>
                        <p class="text-muted">Registrate para acceder a compras y ofertas exclusivas</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cliente.register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="idTipoDocumento" class="form-label fw-semibold">Tipo Documento <span class="text-danger">*</span></label>
                                <select class="form-select" id="idTipoDocumento" name="idTipoDocumento" required>
                                    <option value="" disabled selected>Selecciona</option>
                                    <option value="1" {{ old('idTipoDocumento') == 1 ? 'selected' : '' }}>DNI</option>
                                    <option value="2" {{ old('idTipoDocumento') == 2 ? 'selected' : '' }}>RUC</option>
                                    <option value="3" {{ old('idTipoDocumento') == 3 ? 'selected' : '' }}>CE</option>
                                    <option value="4" {{ old('idTipoDocumento') == 4 ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label for="numeroDocumento" class="form-label fw-semibold">N&uacute;mero de Documento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" value="{{ old('numeroDocumento') }}" placeholder="Ingresa tu documento" required>
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarDoc" title="Buscar datos">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <div id="docFeedback" class="form-text"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="nombre" class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Tu nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidoPaterno" class="form-label fw-semibold">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" value="{{ old('apellidoPaterno') }}" placeholder="Apellido paterno" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidoMaterno" class="form-label fw-semibold">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" value="{{ old('apellidoMaterno') }}" placeholder="Apellido materno">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label fw-semibold">Telefono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="987654321" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electronico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Contrasena <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Minimo 6 caracteres" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contrasena <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repite tu contrasena" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white shadow-sm mt-2" style="background-color: {{$empresa->colorUno}}">
                            <i class="bi bi-person-check me-1"></i> Crear mi Cuenta
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-0 text-muted">Ya tienes cuenta? <a href="{{ route('cliente.login.form') }}" class="fw-bold text-decoration-none" style="color: {{$empresa->colorUno}}">Inicia sesion</a></p>
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

    function buscarDocumento() {
        const tipoDoc = document.getElementById('idTipoDocumento').value;
        const documento = document.getElementById('numeroDocumento').value.trim();
        const feedback = document.getElementById('docFeedback');
        const btnBuscar = document.getElementById('btnBuscarDoc');
        const nombreInput = document.getElementById('nombre');
        const apPaternoInput = document.getElementById('apellidoPaterno');
        const apMaternoInput = document.getElementById('apellidoMaterno');

        if (!tipoDoc) {
            feedback.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle"></i> Selecciona el tipo de documento primero.</span>';
            return;
        }
        if (!documento) {
            feedback.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle"></i> Ingresa el número de documento.</span>';
            return;
        }

        // Mostrar loading
        btnBuscar.disabled = true;
        btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        feedback.innerHTML = '<span class="text-muted">Buscando datos...</span>';
        
        // Desbloquear temporalmente mientras busca o si falla
        nombreInput.readOnly = false;
        apPaternoInput.readOnly = false;
        apMaternoInput.readOnly = false;

        // Primero buscar en la base de datos interna
        fetch(`{{ route('api.cliente.buscar') }}?numeroDocumento=${documento}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const c = data.cliente;

                    nombreInput.value = c.nombre || '';
                    apPaternoInput.value = c.apellidoPaterno || '';
                    apMaternoInput.value = c.apellidoMaterno || '';
                    document.getElementById('idTipoDocumento').value = c.idTipoDocumento || tipoDoc;
                    document.getElementById('telefono').value = c.telefono || '';
                    if (c.correo) document.getElementById('email').value = c.correo;
                    
                    // Bloquear campos para que no puedan ser editados
                    if (c.nombre) nombreInput.readOnly = true;
                    if (c.apellidoPaterno) apPaternoInput.readOnly = true;
                    if (c.apellidoMaterno) apMaternoInput.readOnly = true;
                    
                    if (data.source === 'api') {
                        feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Datos obtenidos exitosamente.</span>';
                    } else {
                        feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Datos autocompletados desde nuestro sistema.</span>';
                    }
                    
                    btnBuscar.disabled = false;
                    btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
                } else {
                    feedback.innerHTML = '<span class="text-muted"><i class="bi bi-info-circle"></i> No se encontraron datos. Completa el formulario manualmente.</span>';
                    btnBuscar.disabled = false;
                    btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
                }
            })
            .catch(() => {
                feedback.innerHTML = '<span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Error al buscar. Completa manualmente.</span>';
                btnBuscar.disabled = false;
                btnBuscar.innerHTML = '<i class="bi bi-search"></i>';
            });
    }

    document.getElementById('btnBuscarDoc').addEventListener('click', buscarDocumento);
    document.getElementById('numeroDocumento').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarDocumento();
        }
    });

    // Si cambian el documento después de buscar, desbloqueamos los campos
    document.getElementById('numeroDocumento').addEventListener('input', function() {
        document.getElementById('nombre').readOnly = false;
        document.getElementById('apellidoPaterno').readOnly = false;
        document.getElementById('apellidoMaterno').readOnly = false;
        const feedback = document.getElementById('docFeedback');
        if(feedback.innerHTML.includes('text-success')){
            feedback.innerHTML = '';
        }
    });
</script>
@endsection

