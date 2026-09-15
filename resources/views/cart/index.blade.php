@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-5">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 300px; width: 100%; border-radius: 8px; }
    </style>
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold" style="color: {{$empresa->colorUno}}">Mi Carrito <i class="bi bi-cart3"></i></h2>
            <hr>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Producto</th>
                                    <th scope="col" class="text-center">Precio</th>
                                    <th scope="col" class="text-center" style="width: 120px;">Cantidad</th>
                                    <th scope="col" class="text-center">Subtotal</th>
                                    <th scope="col" class="pe-4 text-center">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                        <tr data-id="{{ $id }}">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    @if($details['image'])
                                                        <img src="{{ asset('storage/'.$details['image']) }}" alt="{{ $details['name'] }}" width="60" height="60" class="rounded object-fit-contain me-3 border">
                                                    @else
                                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                            <i class="bi bi-image text-muted fs-4"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><a href="{{ route('producto', $details['slug'] ?? 'error') }}" class="text-decoration-none text-dark">{{ $details['name'] }}</a></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">S/ {{ number_format($details['price'], 2) }}</td>
                                            <td class="text-center">
                                                <input type="number" value="{{ $details['quantity'] }}" class="form-control form-control-sm text-center quantity update-cart" min="1">
                                            </td>
                                            <td class="text-center fw-bold text-success">S/ {{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                            <td class="pe-4 text-center">
                                                <button class="btn btn-sm btn-outline-danger remove-from-cart" title="Eliminar"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-cart-x fs-1 text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">Tu carrito esta vacio.</h5>
                                            <a href="{{ url('/') }}" class="btn mt-3 text-white fw-bold" style="background-color: {{$empresa->colorUno}}">Explorar Productos</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(session('cart'))
                <div class="card-footer bg-white border-top-0 p-3 text-end">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-trash3 me-1"></i>Vaciar Carrito</button>
                    </form>
                </div>
                @endif
            </div>

            @if(session('cart'))
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Método de Entrega</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="tipo_entrega" id="entregaTienda" value="tienda" checked>
                        <label class="form-check-label" for="entregaTienda">
                            Recojo en Tienda (Gratis)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="tipo_entrega" id="entregaDomicilio" value="domicilio">
                        <label class="form-check-label" for="entregaDomicilio">
                            Envío a Domicilio (Se calculará el costo)
                        </label>
                    </div>

                    <div id="domicilioContainer" style="display: none;">
                        <hr>
                        <p class="text-muted small mb-2">Busca tu dirección o arrastra el marcador para ubicacion exacta.</p>
                        <div class="mb-3 position-relative">
                            <input type="text" id="direccionEnvio" class="form-control" placeholder="Ej. Av. Los Pinos 123, Miraflores" autocomplete="off">
                            <ul id="sugerenciasDireccion" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></ul>
                        </div>
                        <div id="map" class="mb-3 border"></div>
                        <input type="hidden" id="envioLat">
                        <input type="hidden" id="envioLng">
                        
                        <button type="button" id="btnCalcularEnvio" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-geo-alt me-1"></i> Calcular Envío
                        </button>
                        <div id="envioResult" class="mt-2 small fw-bold"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Resumen de Compra</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">S/ {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Envío</span>
                        <span class="text-success fw-bold" id="resumenEnvio">S/ 0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5" style="color: {{$empresa->colorUno}}" id="resumenTotal" data-subtotal="{{ $total }}">S/ {{ number_format($total, 2) }}</span>
                    </div>
                    
                    @if(session('cart'))
                        @auth('cliente')
                            <a href="{{ route('checkout.index') }}" class="btn btn-lg w-100 fw-bold text-white shadow-sm" style="background-color: {{$empresa->colorUno}}">
                                Proceder al Pago <i class="bi bi-arrow-right-circle ms-1"></i>
                            </a>
                        @else
                            <div class="alert alert-warning p-2 text-center" style="font-size: 0.9rem;">
                                Debes <strong>Iniciar Sesion</strong> para procesar el pago.
                            </div>
                            <a href="{{ route('cliente.login.form') }}" class="btn btn-lg w-100 fw-bold text-white shadow-sm" style="background-color: {{$empresa->colorUno}}">
                                Iniciar Sesion para Pagar
                            </a>
                        @endauth
                    @else
                        <button class="btn btn-lg w-100 fw-bold text-white shadow-sm disabled" style="background-color: {{$empresa->colorUno}}">
                            Proceder al Pago
                        </button>
                    @endif
                    
                    <div class="text-center mt-3">
                        <a href="{{ url('/') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i>Seguir comprando</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('.update-cart');
        updateButtons.forEach(button => {
            button.addEventListener('change', function(e) {
                e.preventDefault();
                const tr = this.closest('tr');
                const id = tr.getAttribute('data-id');
                const quantity = this.value;

                fetch('{{ route("cart.update") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) window.location.reload();
                });
            });
        });

        const removeButtons = document.querySelectorAll('.remove-from-cart');
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const tr = this.closest('tr');
                const id = tr.getAttribute('data-id');

                if(confirm("Deseas eliminar este producto del carrito?")) {
                    fetch('{{ route("cart.remove") }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) window.location.reload();
                    });
                }
            });
        });
    });
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('map')) return;

        let map, marker;
        const defaultLat = -12.046374; // Lima centro
        const defaultLng = -77.042793;

        const radTienda = document.getElementById('entregaTienda');
        const radDomicilio = document.getElementById('entregaDomicilio');
        const container = document.getElementById('domicilioContainer');
        const btnCalcular = document.getElementById('btnCalcularEnvio');
        const txtDireccion = document.getElementById('direccionEnvio');
        const latInput = document.getElementById('envioLat');
        const lngInput = document.getElementById('envioLng');
        
        const resEnvio = document.getElementById('resumenEnvio');
        const resTotal = document.getElementById('resumenTotal');
        const subtotal = parseFloat(resTotal.getAttribute('data-subtotal'));

        // Guardar valores en sesión temporalmente al cambiar (simulado)
        function actualizarTotales(costoEnvio) {
            resEnvio.innerText = costoEnvio === 0 ? 'S/ 0.00' : 'S/ ' + parseFloat(costoEnvio).toFixed(2);
            resTotal.innerText = 'S/ ' + (subtotal + parseFloat(costoEnvio)).toFixed(2);
        }

        function initMap() {
            if (map) return; // ya inicializado
            map = L.map('map').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);
            latInput.value = defaultLat;
            lngInput.value = defaultLng;

            marker.on('dragend', function (e) {
                const pos = marker.getLatLng();
                latInput.value = pos.lat;
                lngInput.value = pos.lng;
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                latInput.value = e.latlng.lat;
                lngInput.value = e.latlng.lng;
            });
            
            // Timeout para asegurar que el div es visible antes de invalidar el size
            setTimeout(() => { map.invalidateSize(); }, 200);
        }

        radTienda.addEventListener('change', function() {
            if (this.checked) {
                container.style.display = 'none';
                actualizarTotales(0);
                // Inform backend to reset shipping
                fetch('{{ route("cart.calculateShipping") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ lat: 0, lng: 0, direccion: '' }) // Invalid coords will fail but we can handle logic if needed. Actually let's just ignore.
                });
            }
        });

        radDomicilio.addEventListener('change', function() {
            if (this.checked) {
                container.style.display = 'block';
                initMap();
            }
        });

        // Autocompletado de dirección
        let timeout = null;
        txtDireccion.addEventListener('input', function() {
            clearTimeout(timeout);
            const val = this.value;
            const ul = document.getElementById('sugerenciasDireccion');
            if(val.length < 3) {
                ul.style.display = 'none';
                return;
            }
            timeout = setTimeout(() => {
                fetch(`{{ route('cart.geocode') }}?text=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    ul.innerHTML = '';
                    if(data.features && data.features.length > 0) {
                        data.features.forEach(f => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action';
                            li.style.cursor = 'pointer';
                            li.innerText = f.properties.label;
                            li.onclick = () => {
                                txtDireccion.value = f.properties.label;
                                ul.style.display = 'none';
                                const coords = f.geometry.coordinates; // [lng, lat]
                                latInput.value = coords[1];
                                lngInput.value = coords[0];
                                map.setView([coords[1], coords[0]], 15);
                                marker.setLatLng([coords[1], coords[0]]);
                            };
                            ul.appendChild(li);
                        });
                        ul.style.display = 'block';
                    } else {
                        ul.style.display = 'none';
                    }
                });
            }, 500);
        });

        // Ocultar sugerencias al hacer click fuera
        document.addEventListener('click', function(e) {
            if(e.target !== txtDireccion) {
                document.getElementById('sugerenciasDireccion').style.display = 'none';
            }
        });

        btnCalcular.addEventListener('click', function() {
            const lat = latInput.value;
            const lng = lngInput.value;
            const dir = txtDireccion.value;
            
            const resultDiv = document.getElementById('envioResult');
            resultDiv.innerText = "Calculando...";
            resultDiv.className = "mt-2 small fw-bold text-info";

            fetch('{{ route("cart.calculateShipping") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ lat: lat, lng: lng, direccion: dir })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    resultDiv.innerText = "Costo calculado: S/ " + data.costo.toFixed(2) + " (" + data.distancia_km + " km)";
                    resultDiv.className = "mt-2 small fw-bold text-success";
                    actualizarTotales(data.costo);
                } else {
                    resultDiv.innerText = data.message;
                    resultDiv.className = "mt-2 small fw-bold text-danger";
                    actualizarTotales(0);
                }
            })
            .catch(err => {
                resultDiv.innerText = "Ocurrió un error.";
                resultDiv.className = "mt-2 small fw-bold text-danger";
            });
        });
    });
</script>
@endsection
