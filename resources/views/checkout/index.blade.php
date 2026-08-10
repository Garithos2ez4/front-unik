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
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-whatsapp text-success mb-3" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold">Solicitar los numeros de cuenta,link de pago O QR de Pago</h4>
                    <p class="text-muted mt-3 mb-4">
                        Por el momento nuestras pasarelas automáticas están en mantenimiento.<br>
                        Al hacer clic en el botón de abajo, separaremos tus productos (S/ {{ number_format($total, 2) }}) y te generaremos un número de orden.
                        Podrás comunicarte con nosotros por WhatsApp para solicitar tu link de pago o código QR.
                    </p>
                    <form action="{{ route('checkout.manual') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg w-100 fw-bold text-white shadow-sm" style="background-color: #25D366; border-radius: 10px;">
                            <i class="bi bi-bag-check-fill me-2"></i> Confirmar Pedido y Solicitar Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection