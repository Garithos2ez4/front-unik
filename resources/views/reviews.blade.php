@extends('layouts.app')

@section('title', 'Reviews y Comunidad | ' . $empresa->nombreComercial)

@section('content')
<div class="container py-5 mt-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: {{$empresa->colorUno}}">Comunidad <span style="color: {{$empresa->colorDos}}">Unik</span></h1>
        <p class="lead text-muted">Mira lo que nuestros clientes opinan sobre nuestros productos y descubre los increíbles setups que han armado.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Masonry Grid -->
    <div class="row" data-masonry='{"percentPosition": true }'>
        @foreach($reviews as $review)
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: rgba(255, 255, 255, 0.9);">
                <!-- Imagen del Setup -->
                @if($review->imagen_setup)
                    <img src="{{ $review->imagen_setup }}" class="card-img-top" alt="Setup de {{ $review->nombre_cliente }}" style="object-fit: cover; height: 250px;">
                @endif
                
                <div class="card-body p-4">
                    <!-- Calificación -->
                    <div class="mb-2">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $review->calificacion)
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    
                    <!-- Comentario -->
                    <p class="card-text fw-bold fs-5" style="color: {{$empresa->colorUno}}">"{{ $review->comentario }}"</p>
                    
                    @if($review->producto)
                        <div class="mb-3">
                            <span class="badge" style="background-color: {{$empresa->colorDos}}">
                                <i class="bi bi-box-seam"></i> Producto: {{ Str::limit($review->producto->nombreProducto, 40) }}
                            </span>
                        </div>
                    @endif
                    
                    <hr class="text-muted">
                    
                    <!-- Info del cliente -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill fs-4 text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $review->cliente->nombre ?? 'Cliente' }} {{ $review->cliente->apellidoPaterno ?? '' }}</h6>
                                <small class="text-muted"><i class="bi bi-check-circle-fill text-success"></i> Comprador verificado</small>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Call to action -->
    <div class="text-center mt-5 p-5 rounded-3" style="background-color: {{$empresa->colorTres}}; border: 1px solid {{$empresa->colorUno}};">
        <h2 class="fw-bold" style="color: {{$empresa->colorUno}}">¿Ya eres cliente de {{$empresa->nombreComercial}}?</h2>
        <p class="lead" style="color: {{$empresa->colorUno}}">Anímate a dejar una reseña de tu compra o etiquétanos en redes sociales.</p>
        
        <!-- Botón para abrir Modal -->
        <button type="button" class="btn btn-lg mt-3 fw-bold me-2" data-bs-toggle="modal" data-bs-target="#reviewModal" style="background-color: {{$empresa->colorUno}}; color: white;">
            <i class="bi bi-pencil-square"></i> Dejar una Reseña
        </button>

        <a href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace ?? '#'}}" target="_blank" class="btn btn-lg mt-3 fw-bold" style="background-color: {{$empresa->colorDos}}; color: white;">
            <i class="bi bi-whatsapp"></i> Envíanos tu foto
        </a>
    </div>
</div>

<!-- Modal para Dejar Reseña -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: {{$empresa->colorUno}}; color: white;">
        <h5 class="modal-title" id="reviewModalLabel"><i class="bi bi-star-fill text-warning"></i> Deja tu reseña</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <h6 class="border-bottom pb-2 mb-3 text-muted">Tus Datos</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tipo Documento</label>
                    <select name="idTipoDocumento" class="form-select" required>
                        @foreach($tipoDocumentos as $td)
                            <option value="{{ $td->idTipoDocumento }}">{{ $td->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Número de Documento</label>
                    <input type="text" class="form-control" name="numeroDocumento" required maxlength="15">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="nombre" maxlength="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidoPaterno" maxlength="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="correo" maxlength="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono/Celular</label>
                    <input type="text" class="form-control" name="telefono" maxlength="15">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3 text-muted">Tu Reseña</h6>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">¿De qué producto opinas? (Opcional)</label>
                    <select name="idProducto" id="idProducto" class="form-select" placeholder="Busca un producto...">
                        <option value="">Reseña general de la tienda</option>
                        @foreach($productos as $prod)
                            <option value="{{ $prod->idProducto }}">{{ $prod->nombreProducto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label d-block">Calificación</label>
                    <div class="rating-stars fs-3 text-warning" style="cursor: pointer;">
                        <i class="bi bi-star-fill star-rating" data-value="1"></i>
                        <i class="bi bi-star-fill star-rating" data-value="2"></i>
                        <i class="bi bi-star-fill star-rating" data-value="3"></i>
                        <i class="bi bi-star-fill star-rating" data-value="4"></i>
                        <i class="bi bi-star-fill star-rating" data-value="5"></i>
                    </div>
                    <input type="hidden" name="calificacion" id="calificacionInput" value="5">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Comentario</label>
                    <textarea class="form-control" name="comentario" rows="3" required maxlength="1000" placeholder="Cuéntanos tu experiencia..."></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Foto de tu setup / producto (Opcional)</label>
                    <input type="file" class="form-control" name="imagen_setup" accept="image/*">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn fw-bold" style="background-color: {{$empresa->colorDos}}; color: white;">Enviar Reseña</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- Masonry JS for Pinterest style layout -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
<!-- Tom Select CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar TomSelect para el select de productos
        const tsProducto = new TomSelect('#idProducto', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Revisar si venimos de la pagina de un producto
        const urlParams = new URLSearchParams(window.location.search);
        const prodId = urlParams.get('prod');
        if (prodId) {
            tsProducto.setValue(prodId);
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        }

        const stars = document.querySelectorAll('.star-rating');
        const calificacionInput = document.getElementById('calificacionInput');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                calificacionInput.value = value;
                
                // Update star visuals
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                    } else {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    }
                });
            });
        });

        // Autocomplete client data
        const inputDocumento = document.querySelector('input[name="numeroDocumento"]');
        const inputTipo = document.querySelector('select[name="idTipoDocumento"]');
        const inputNombre = document.querySelector('input[name="nombre"]');
        const inputApellido = document.querySelector('input[name="apellidoPaterno"]');
        const inputCorreo = document.querySelector('input[name="correo"]');
        const inputTelefono = document.querySelector('input[name="telefono"]');

        inputDocumento.addEventListener('blur', function() {
            const documento = this.value.trim();
            if(documento.length > 5) {
                fetch(`{{ url('/reviews/cliente') }}/${documento}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.encontrado) {
                            // Rellenar datos
                            inputTipo.value = data.datos.idTipoDocumento || 1;
                            inputNombre.value = data.datos.nombre || '';
                            inputApellido.value = data.datos.apellidoPaterno || '';
                            inputCorreo.value = data.datos.correo || '';
                            inputTelefono.value = data.datos.telefono || '';

                            // Bloquear campos
                            inputTipo.style.pointerEvents = 'none';
                            inputTipo.style.opacity = '0.7';
                            
                            if (inputNombre.value) inputNombre.setAttribute('readonly', true);
                            if (inputApellido.value) inputApellido.setAttribute('readonly', true);
                            if (inputCorreo.value) inputCorreo.setAttribute('readonly', true);
                            if (inputTelefono.value) inputTelefono.setAttribute('readonly', true);
                        } else {
                            // Limpiar y desbloquear si no existe y estaban bloqueados
                            if (inputNombre.hasAttribute('readonly')) {
                                inputTipo.style.pointerEvents = 'auto';
                                inputTipo.style.opacity = '1';
                                inputNombre.removeAttribute('readonly');
                                inputApellido.removeAttribute('readonly');
                                inputCorreo.removeAttribute('readonly');
                                inputTelefono.removeAttribute('readonly');
                                
                                inputNombre.value = '';
                                inputApellido.value = '';
                                inputCorreo.value = '';
                                inputTelefono.value = '';
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching cliente:', error));
            }
        });
    });
</script>
@endpush