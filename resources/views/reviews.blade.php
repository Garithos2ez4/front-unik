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
        @include('reviews.card', ['review' => $review, 'empresa' => $empresa])
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

@include('reviews.modal', ['empresa' => $empresa, 'tipoDocumentos' => $tipoDocumentos, 'productos' => $productos])

@endsection

@push('scripts')
<!-- Masonry JS for Pinterest style layout -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
<!-- Tom Select CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

@include('reviews.logic.scripts')
@endpush