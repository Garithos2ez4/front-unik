@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-5">
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
                        <span class="text-muted">Envio</span>
                        <span class="text-success fw-bold">Por calcular</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5" style="color: {{$empresa->colorUno}}">S/ {{ number_format($total, 2) }}</span>
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
@endsection
