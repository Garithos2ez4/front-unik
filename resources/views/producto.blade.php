@extends('layouts.app')

@section('title', $producto->nombreProducto)

@section('content')
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-2 d-none d-md-block">

                    @php
                        // Calculamos el slide inicial basado en la cantidad de imágenes
                        $slideOffset = isset($imagesCount) ? $imagesCount : 4; // Asume 4 imágenes por defecto
                    @endphp
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="{{$producto->displayImg($producto->publicImages()[0])}}active" aria-current="true" aria-label="Slide 1">
                        <img src="{{$producto->publicImages()[0]}}" class="d-block w-100 productimg border" alt="...">
                    </a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class="{{$producto->displayImg($producto->publicImages()[1])}}">
                        <img src="{{$producto->publicImages()[1]}}" class="d-block w-100 productimg border" alt="..."></a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class="{{$producto->displayImg($producto->publicImages()[2])}}">
                        <img src="{{$producto->publicImages()[2]}}" class="d-block w-100 productimg border" alt="...">
                    </a>
                    <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4" class="{{$producto->displayImg($producto->publicImages()[3])}}">
                        <img src="{{$producto->publicImages()[3]}}" class="d-block w-100 productimg border" alt="...">
                    </a>
                    <!-- Miniaturas en el lado izquierdo -->
                    @if($producto->videoUrl1)
                        <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $slideOffset }}" aria-label="Slide 5">
                                <i class="bi bi-play-btn-fill text-danger video-icon-big"></i>  
                        </a>
                        @php $slideOffset++; @endphp
                    @endif

                    @if($producto->videoUrl2)
                        <a type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $slideOffset }}" aria-label="Slide 6">                           
                                <i class="bi bi-play-btn-fill video-icon-big"></i>
                        </a>
                    @endif

                </div>
                <div class="col-12 col-md-10">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                      <div class="carousel-inner border shadow">
                        <div class="carousel-item active">
                          <img src="{{$producto->publicImages()[0]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[0])}}" alt="...">
                        </div>
                        <div class="carousel-item">
                          <img src="{{$producto->publicImages()[1]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[1])}}" alt="...">
                        </div>
                        <div class="carousel-item">
                          <img src="{{$producto->publicImages()[2]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[2])}}" alt="...">
                        </div>
                        <div class="carousel-item">
                          <img src="{{$producto->publicImages()[3]}}" class="d-block w-100 {{$producto->displayImg($producto->publicImages()[3])}}" alt="...">
                        </div>
                        <!-- Video 1 -->
                        @if($producto->videoUrl1)
                            <div class="carousel-item justify-content-center" >
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
            <h2 style="color:{{$empresa->colorUno}}">{{$producto->nombreProducto}}</h2>
            <h6 class="{{$producto->estadoColor()}}">{{$producto->estadoProductoWeb}}</h6>
            <h3 style="color:{{$empresa->colorDos}}">{{$producto->precioTotalDolar($preciosService) < 1 ? 'Consultar precio por WhatsApp':'S/.PEN '.$producto->precioTotalSol($preciosService)}}</h3>
            <h5 style="color:{{$empresa->colorUno}};opacity:0.5">{{$producto->precioTotalSol($preciosService) < 1 ? '':'$USD '.$producto->precioTotalDolar($preciosService)}} </h5>
            <p class="mb-0"><i class="bi bi-shield-check"></i> Garantía de {{$producto->garantia}}.</p>
            <p class="mb-0"><i class='bx bxs-truck'></i> Preguntar por envio y disponibilidad.</p>
            <br>
            <p class="mb-0"><strong>Marca:</strong> {{$producto->MarcaProducto->nombreMarca}}</p>
            <p class="mb-0"><strong>Modelo:</strong> {{$producto->modelo}}</p>
            <p class="mb-0"><strong>P/N:</strong> {{$producto->partNumber}}</p>
            <br>
            <div class="col-12 col-md-6">
                <div class="d-grid gap-2">
                    <a class="btn btn-success" href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace}}?text=Hola%2C%20estoy%20interesado%20en%20{{$producto->nombreProducto}}%20de%20su%20sitio%20web. {{$miUrl}}" target="_blank" rel="noopener noreferrer" role="button"><i class="bi bi-whatsapp"></i> Comprar via whatsapp</a>
                </div>
            </div>
            <div class="col-6">
            </div>
        </div>
    </div>
    <br>
    <div class="col-12 d-block border-bottom border-top pt-3 d-sm-none mb-2">
        <a class="text-decoration-none text-empresa-uno fs-5 fw-bolder w-100 d-inline-flex" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottomDesc" aria-controls="offcanvasBottomDesc">
            <p class="w-75"> Información adicional</p><p class="text-end w-25"><i class="bi bi-exclamation-circle"></i></p>
        </a>
    </div>
    <br>
    <div class="row">
        <br>
        <div class="offcanvas offcanvas-bottom h-75" tabindex="-1" id="offcanvasBottomDesc" aria-labelledby="offcanvasBottomLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasBottomLabel">Información adicional</h5>
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
                    <li class="list-group-item bg-body"><strong>{{$detalle->Caracteristicas->especificacion}}: </strong>{{$detalle->caracteristicaProducto}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-1">
            
        </div>
        <div class="col-md-7 d-none d-sm-block">
            <div class="row border-bottom border-dark">
                <h4>Información adicional</h4>
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
    
    <div class="row">
        
    </div>
    <br>
    <div class="row">
        <x-slider_medio :producto="$productosCategoria" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Productos similares'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="route('categoria', [$producto->GrupoProducto->CategoriaProducto->slugCategoria ,$producto->GrupoProducto->slugGrupo])"/>
    </div>
    <br>
</div>
<script>
    const carousel = document.querySelector('#carouselExampleIndicators');

    carousel.addEventListener('slid.bs.carousel', function () {
        const iframes = document.querySelectorAll('.yt-frame');
        iframes.forEach(iframe => {
            const src = iframe.getAttribute('src').split('?')[0];
            iframe.setAttribute('src', src); // Detener video
        });

        // Reproducir solo el iframe visible
        const active = carousel.querySelector('.carousel-item.active iframe');
        if (active) {
            const base = active.getAttribute('src').split('?')[0];
            active.setAttribute('src', base + '?autoplay=1&rel=0');
        }
    });
</script>
<style>
    .video-icon-big {
        font-size: 3.5rem; /* o lo que prefieras */
        margin-top: 10px;
        margin-left:auto;
    }
</style>


@endsection