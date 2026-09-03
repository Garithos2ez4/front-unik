@extends('layouts.app')

@section('title', $producto->nombreProducto . ' | ' . $producto->MarcaProducto->nombreMarca . ' | ' . $empresa->nombreComercial)
@section('description', Str::limit(strip_tags(str_replace(["\r", "\n"], ' ', $producto->descripcionProducto)), 160))
@section('og_image', $producto->publicImages()[0])
@section('og_type', 'product')

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $producto->nombreProducto }}",
  "image": [
    "{{ $producto->publicImages()[0] }}"
  ],
  "description": "{{ Str::limit(strip_tags(str_replace(["\r", "\n"], ' ', $producto->descripcionProducto)), 160) }}",
  "sku": "{{ $producto->partNumber ?: $producto->modelo }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $producto->MarcaProducto->nombreMarca }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "PEN",
    "price": "{{ floatval(str_replace(',', '', $producto->precioTotalSol($preciosService))) }}",
    "availability": "{{ strtoupper($producto->estadoProductoWeb) === 'AGOTADO' ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock' }}"
  }
  @if($producto->reviews->count() > 0)
  ,"aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $producto->reviews->avg('calificacion') }}",
    "reviewCount": "{{ $producto->reviews->count() }}"
  }
  @endif
}
</script>
@endpush
@section('content')
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-2 d-none d-md-block">

                    @php
                    // Calculamos el slide inicial basado en la cantidad de im&aacute;genes
                    $slideOffset = isset($imagesCount) ? $imagesCount : 4; // Asume 4 im&aacute;genes por defecto
                    @endphp
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="{{$producto->displayImg($producto->publicImages()[0])}}active" aria-current="true" aria-label="Slide 1">
                        <img src="{{$producto->publicImages()[0]}}" class="d-block w-100 productimg border" alt="{{ $producto->nombreProducto }} - Vista 1">
                    </a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class="{{$producto->displayImg($producto->publicImages()[1])}}">
                        <img src="{{$producto->publicImages()[1]}}" class="d-block w-100 productimg border" alt="{{ $producto->nombreProducto }} - Vista 2"></a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class="{{$producto->displayImg($producto->publicImages()[2])}}">
                        <img src="{{$producto->publicImages()[2]}}" class="d-block w-100 productimg border" alt="{{ $producto->nombreProducto }} - Vista 3">
                    </a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4" class="{{$producto->displayImg($producto->publicImages()[3])}}">
                        <img src="{{$producto->publicImages()[3]}}" class="d-block w-100 productimg border" alt="{{ $producto->nombreProducto }} - Vista 4">
                    </a>
                    <!-- Miniaturas en el lado izquierdo -->
                    @if($producto->videoUrl1)
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $slideOffset }}" aria-label="Slide 5">
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$empresa->logo) }}" class="d-block w-100 productimg border" style="opacity: 0.3;" alt="...">
                            <i class="bi bi-youtube position-absolute top-50 start-50 translate-middle" style="font-size: 2rem; color: rgba(255, 0, 0, 1.0);"></i>
                        </div>
                    </a>
                    @php $slideOffset++; @endphp
                    @endif

                    @if($producto->videoUrl2)
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $slideOffset }}" aria-label="Slide 6">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $producto->MarcaProducto->imagenMarca) }}" class="d-block w-100 productimg border" style="opacity: 0.3;" alt="...">
                            <i class="bi bi-youtube position-absolute top-50 start-50 translate-middle" style="font-size: 1rem; color: rgba(255, 0, 0, 1.0);"></i>
                        </div>
                    </a>
                    @endif
                </div>
                <div class="col-12 col-md-10">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                        <div class="carousel-inner border shadow">
                            <div class="carousel-item active">
                                <img src="{{$producto->publicImages()[0]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[0])}}" alt="{{ $producto->nombreProducto }} - Principal">
                            </div>
                            <div class="carousel-item">
                                <img src="{{$producto->publicImages()[1]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[1])}}" alt="{{ $producto->nombreProducto }} - Detalle 1">
                            </div>
                            <div class="carousel-item">
                                <img src="{{$producto->publicImages()[2]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[2])}}" alt="{{ $producto->nombreProducto }} - Detalle 2">
                            </div>
                            <div class="carousel-item">
                                <img src="{{$producto->publicImages()[3]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[3])}}" alt="{{ $producto->nombreProducto }} - Detalle 3">
                            </div>
                            <!-- Video 1 -->
                            @if($producto->videoUrl1)
                            <div class="carousel-item justify-content-center">
                                <div class="ratio ratio-16x9">
                                    <iframe id="video1"
                                        src="{{ $producto->getYoutubeEmbed($producto->videoUrl1) }}?rel=0&mute=1"
                                        class="yt-frame d-block w-100"
                                        title="Video 1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>
                            @endif

                            <!-- Video 2 -->
                            @if($producto->videoUrl2)
                            <div class="carousel-item justify-content-center">
                                <div class="ratio ratio-16x9">
                                    <iframe id="video2"
                                        src="{{ $producto->getYoutubeEmbed($producto->videoUrl2) }}?rel=0&mute=1"
                                        class="yt-frame d-block w-100"
                                        title="Video 2" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>
                            @endif


                        </div>
                        <div class="d-block d-sm-none">
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12">

                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 pt-4">
            <h6 style="color:{{$empresa->colorUno}};opacity:0.5">{{$producto->GrupoProducto->nombreGrupo}}</h6>
            <h1 class="fs-3 fs-md-2 fw-bold" style="color:{{$empresa->colorUno}};">{{$producto->nombreProducto}}</h1>
            <h6 class="{{$producto->estadoColor()}}">{{$producto->estadoProductoWeb}}</h6>
            @php
            $mostrarPrecio = optional($producto->DetalleProducto)->mostrarPrecioWeb ?? true;
            $precioSolStr = $producto->precioTotalSol($preciosService);
            $precioSol = floatval(str_replace(',', '', $precioSolStr));
            $precioSolNormalStr = $producto->precioNormalSol($preciosService);
            $precioSolNormal = floatval(str_replace(',', '', $precioSolNormalStr));
            $descuentoPorcentaje = 0;
            if ($precioSolNormal > $precioSol && $precioSolNormal > 0) {
            $descuentoPorcentaje = round((($precioSolNormal - $precioSol) / $precioSolNormal) * 100);
            }
            @endphp
            @if(floatval(str_replace(',', '', $producto->precioTotalDolar($preciosService))) < 1 || !$mostrarPrecio)
                <h3 style="color:{{$empresa->colorDos}}">Consultar precio por WhatsApp</h3>
                @else
                <h3 style="{{ $descuentoPorcentaje > 0 ? 'color: #dc3545;' : 'color:'.$empresa->colorDos }}">
                    S/.PEN {{ $precioSolStr }}
                    @if($descuentoPorcentaje > 0)
                    <span class="badge bg-danger ms-2">-{{ $descuentoPorcentaje }}%</span>
                    @endif
                </h3>
                @if($descuentoPorcentaje > 0)
                <h5 class="text-muted text-decoration-line-through">S/.PEN {{ $precioSolNormalStr }}</h5>
                @endif
                @endif
                <h5 style="color:{{$empresa->colorUno}};opacity:0.5">{{(floatval(str_replace(',', '', $producto->precioTotalSol($preciosService))) < 1 || !$mostrarPrecio || !$producto->usar_tc_fijo) ? '':'$USD '.$producto->precioTotalDolar($preciosService) }} </h5>
                <p class="mb-0"><i class="bi bi-shield-check"></i> Garant&iacute;a de {{$producto->garantia}}.</p>
                <p class="mb-0"><i class='bx bxs-truck'></i> Preguntar por envio y disponibilidad.</p>
                <br>
                <p class="mb-0"><strong>Marca:</strong> {{$producto->MarcaProducto->nombreMarca}}</p>
                <p class="mb-0"><strong>Modelo:</strong> {{$producto->modelo}}</p>
                <p class="mb-0"><strong>P/N:</strong> {{$producto->partNumber}}</p>
                <br>
                <div class="col-12 col-md-10 mt-2">
                    <div class="d-grid gap-3 d-md-flex">
                        <a class="btn btn-success btn-lg fw-bold py-3 py-md-2" href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace}}?text=Hola%2C%20estoy%20interesado%20en%20{{$producto->nombreProducto}}%20de%20su%20sitio%20web. {{$miUrl}}" target="_blank" rel="noopener noreferrer" role="button"><i class="bi bi-whatsapp"></i> Comprar via whatsapp</a>

                        @if(strtoupper($producto->estadoProductoWeb) === 'AGOTADO')
                        <button class="btn btn-agotado-navy btn-lg fw-bold py-3 py-md-2" disabled>
                            <i class="bi bi-x-circle"></i> Agotado
                        </button>
                        @elseif($producto->precioTotalSol($preciosService) > 0 && (!$producto->DetalleProducto || $producto->DetalleProducto->mostrarPrecioWeb) && $mostrarPrecio)
                        <button class="btn btn-outline-dark btn-lg fw-bold py-3 py-md-2 add-to-cart-btn" data-id="{{ $producto->idProducto }}">
                            <i class="bi bi-cart-plus"></i> Añadir al carrito
                        </button>
                        @endif
                    </div>
                </div>
                <div class="col-6">
                </div>
        </div>
    </div>
    <br>

    <div class="col-12 d-block border-bottom border-top pt-3 d-sm-none mb-2">
        <a class="text-decoration-none text-empresa-uno fs-5 fw-bolder w-100 d-inline-flex" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottomDesc" aria-controls="offcanvasBottomDesc">
            <p class="w-75"> Informaci&oacute;n adicional</p>
            <p class="text-end w-25"><i class="bi bi-exclamation-circle"></i></p>
        </a>
    </div>
    <br>
    <div class="row">
        <br>
        <div class="offcanvas offcanvas-bottom h-75" tabindex="-1" id="offcanvasBottomDesc" aria-labelledby="offcanvasBottomLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasBottomLabel">Informaci&oacute;n adicional</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="">
                    @if(strpos($producto->descripcionProducto, "\n") !== false || strpos($producto->descripcionProducto, "\r\n") !== false)
                    {!! nl2br(e($producto->descripcionProducto)) !!}
                    @else
                    {{ $producto->descripcionProducto }}
                    @endif
                </p>
            </div>
        </div>
        <br>
        <div class="col-12 col-md-4">
            <div class="row border-bottom border-dark">
                <h4>Especificaciones</h4>
            </div>
            <div class="row">
                <ul class="list-group list-group-flush ">
                    @foreach($producto->Caracteristicas_Producto as $detalle)
                    <li class="list-group-item bg-body p-3"><strong>{{$detalle->Caracteristicas->especificacion}}: </strong>{{$detalle->caracteristicaProducto}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-1">

        </div>
        <div class="col-md-7 d-none d-sm-block">
            <div class="row border-bottom border-dark">
                <h4>Informaci&oacute;n adicional</h4>
            </div>
            <div class="row" style="max-height: 800px;overflow-y: auto;">
                <p class="">
                    @if(strpos($producto->descripcionProducto, "\n") !== false || strpos($producto->descripcionProducto, "\r\n") !== false)
                    {!! nl2br(e($producto->descripcionProducto)) !!}
                    @else
                    {{ $producto->descripcionProducto }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Secci&oacute;n de Reviews del Producto -->
    <div class="row border-bottom border-dark mt-4 align-items-center">
        <div class="col-8">
            <h4>Rese&ntilde;as de Clientes</h4>
        </div>
        <div class="col-4 text-end">
            <a href="{{ route('reviews') }}?prod={{ $producto->idProducto }}" class="btn btn-sm text-white shadow-sm" style="background-color: {{$empresa->colorUno}};">
                <i class="bi bi-pencil-square"></i> Escribir rese&ntilde;a
            </a>
        </div>
    </div>
    <div class="row mt-3">
        @if($producto->reviews->count() > 0)
        <div class="row" data-masonry='{"percentPosition": true }'>
            @foreach($producto->reviews as $review)
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0" style="background-color: rgba(255, 255, 255, 0.9);">
                    @if($review->imagen_setup)
                    <img src="{{ asset('storage/' . $review->imagen_setup) }}" class="card-img-top" alt="Setup de {{ $review->cliente->nombre ?? 'Cliente' }}" style="object-fit: cover; height: 200px;">
                    @endif
                    <div class="card-body">
                        <div class="mb-2">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $review->calificacion)
                                <i class="bi bi-star-fill text-warning"></i>
                                @else
                                <i class="bi bi-star text-warning"></i>
                                @endif
                                @endfor
                        </div>
                        <p class="card-text fw-bold" style="color: {{$empresa->colorUno}}">"{{ $review->comentario }}"</p>
                        <hr class="text-muted">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold fs-6">{{ $review->cliente->nombre ?? 'Cliente' }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill text-success"></i> Verificado</small>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Include Masonry JS if it's not loaded globally -->
        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
        @else
        <div class="col-12">
            <div class="alert alert-light text-center" role="alert">
                <i class="bi bi-chat-left-dots fs-3 text-muted"></i>
                <p class="mb-0 mt-2">A&uacute;n no hay rese&ntilde;as para este producto. &iexcl;S&eacute; el primero en compartir tu experiencia!</p>
                <a href="{{ route('reviews') }}?prod={{ $producto->idProducto }}" class="btn text-white mt-3 shadow-sm fw-bold" style="background-color: {{$empresa->colorDos}};">
                    <i class="bi bi-star-fill text-warning"></i> Dejar una rese&ntilde;a
                </a>
            </div>
        </div>
        @endif
    </div>
    <br>
    <div class="row">
        <x-slider_medio :producto="$productosCategoria" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Productos similares'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="route('categoria', [$producto->GrupoProducto->CategoriaProducto->slugCategoria ,$producto->GrupoProducto->slugGrupo])" />
    </div>
    </div>
    <br>
</div>

<!-- Floating Action Buttons (Mobile Only) -->
<div class="d-block d-md-none fixed-bottom bg-white shadow-lg p-3 border-top" style="z-index: 1040;">
    <div class="d-flex gap-2">
        <a class="btn btn-success flex-grow-1 fw-bold py-2" href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace}}?text=Hola%2C%20estoy%20interesado%20en%20{{$producto->nombreProducto}}%20de%20su%20sitio%20web. {{$miUrl}}" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-whatsapp"></i> Comprar
        </a>
        @if(strtoupper($producto->estadoProductoWeb) === 'AGOTADO')
        <button class="btn btn-agotado-navy flex-grow-1 fw-bold py-2" disabled>
            <i class="bi bi-x-circle"></i> Agotado
        </button>
        @elseif($producto->precioTotalSol($preciosService) > 0 && (!$producto->DetalleProducto || $producto->DetalleProducto->mostrarPrecioWeb) && $mostrarPrecio)
        <button class="btn btn-dark flex-grow-1 fw-bold py-2 add-to-cart-btn" data-id="{{ $producto->idProducto }}">
            <i class="bi bi-cart-plus"></i> Añadir
        </button>
        @endif
    </div>
</div>
<!-- Spacing at the bottom so the floating bar doesn't cover content -->
<div class="d-block d-md-none" style="height: 80px;"></div>

<script src="{{ asset('js/producto.js') }}"></script>
@endsection