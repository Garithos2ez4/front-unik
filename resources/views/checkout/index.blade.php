@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold" style="color: {{$empresa->colorUno}}">Elige tu medio de pago <i class="bi bi-credit-card"></i></h2>
            <p class="text-muted">Estas a un paso de finalizar tu compra de forma segura.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
        <div><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <img src="https://logospng.org/download/mercado-pago/logo-mercado-pago-icon-1024.png" alt="MercadoPago" width="100" class="mb-3">
                    <h5 class="fw-bold">Pagar con MercadoPago</h5>
                    <p class="text-muted small">Paga de forma segura con tu tarjeta de credito, debito o dinero en cuenta.</p>
                    <form action="{{ route('checkout.mercadopago') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white shadow-sm mt-3" style="background-color: #009ee3;">
                            Pagar S/ {{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <img src="https://www.niubiz.com.pe/wp-content/uploads/2019/12/logo-niubiz.png" alt="Niubiz" width="150" class="mb-3" style="object-fit: contain; height: 100px;">
                    <h5 class="fw-bold">Pagar con Niubiz</h5>
                    <p class="text-muted small"> pagos con QR (Yape, Plin).</p>
                    <form action="{{ route('checkout.niubiz') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white shadow-sm mt-3" style="background-color: #f73b3e;">
                            Pagar S/ {{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection