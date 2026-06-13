@extends('layouts.app')

@section('title', $categoria->nombreCategoria . ' | ' . (is_object($grupo) ? $grupo->nombreGrupo ?? 'Todos' : 'Todos'))

@section('content')
<div class="container ">
    <br>
    <h1 class="text-center"><i class="{{$categoria->iconCategoria}}"></i> {{$categoria->nombreCategoria}}</h1>
        @if($grupo == null)
            <h3 class="text-center text-black-50">Todos</h3>
        @else
            <h3 class="text-center text-black-50">{{$grupo->nombreGrupo}}</h3>
        @endif
    <div class="row">
        <div id="carruselGrupos" class="carousel slide d-none d-sm-block mb-3" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
          @php
            $chunks = $categoria->GrupoProducto->chunk(5);
          @endphp
          @foreach ($chunks as $index => $chunk)
            @php
              $activeCarrusel = false;
            @endphp
            @foreach ($chunk as $valChunk)
              @if($valChunk->idGrupoProducto == $grupo->idGrupoProducto)
                @php
                  $activeCarrusel = true;
                @endphp
              @endif
            @endforeach
            <div class="carousel-item {{ $activeCarrusel == true ? 'active' : '' }}  text-center">
              <div class="row justify-content-center">
                  <div class="col-1"></div>
                @foreach ($chunk as $grup)
                  <div class="col-2">
                     <a href="{{route('categoria', [$categoria->slugCategoria,$grup->slugGrupo])}}" class="text-decoration-none text-dark">
                        <img  src="{{asset('storage/'.$grup->imagenGrupo)}}" 
                              alt="Marca {{ $loop->parent->iteration }}" 
                              width="80%" 
                              class="rounded-3 group-hover group-img-hover {{$grup->idGrupoProducto == $grupo->idGrupoProducto ? 'group-selected' : ''}} ">
                        <h5 class="mt-2 fw-bold" style="font-size: 1.05rem; line-height: 1.2;">{{$grup->nombreGrupo}}</h5>
                    </a>
                  </div>
                @endforeach
                <div class="col-1"></div>
              </div>
            </div>
          @endforeach
        </div>
        @php $dis = ""; @endphp
        @if(count($categoria->GrupoProducto) < 6)
            @php $dis = "d-none";  @endphp
        @else
        
        @endif
        <button class="carousel-control-prev" style="width:10%" type="button" data-bs-target="#carruselGrupos" data-bs-slide="prev">
          <span class="text-dark {{$dis}}"><i class="bi bi-arrow-bar-left" style="font-size: 3rem"></i></span>
        </button>
        <button class="carousel-control-next" style="width:10%" type="button" data-bs-target="#carruselGrupos" data-bs-slide="next">
          <span class="text-dark {{$dis}}"><i class="bi bi-arrow-bar-right" style="font-size: 3rem"></i></span>
        </button>
      </div>
      <div id="carruselGruposSmart" class="carousel slide d-block d-md-none mb-3" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
          @php
            $chunks = $categoria->GrupoProducto->chunk(3);
          @endphp
          @foreach ($chunks as $index => $chunk)
            @php
              $activeCarrusel = false;
            @endphp
            @foreach ($chunk as $valChunk)
              @if($valChunk->idGrupoProducto == $grupo->idGrupoProducto)
                @php
                  $activeCarrusel = true;
                @endphp
              @endif
            @endforeach
            <div class="carousel-item {{ $activeCarrusel == true ? 'active' : '' }} text-center">
              <div class="row justify-content-center">
                  <div class="col-1"></div>
                @foreach ($chunk as $grup)
                  <div class="col-3">
                     <a href="{{route('categoria', [$categoria->slugCategoria,$grup->slugGrupo])}}" class="text-decoration-none text-dark">
                        <img  src="{{asset('storage/'.$grup->imagenGrupo)}}" 
                              alt="Marca {{ $loop->parent->iteration }}" 
                              width="80%" 
                              class="border rounded-3 {{$grup->idGrupoProducto == $grupo->idGrupoProducto ? 'group-selected' : ''}}">
                        <p class="mt-2 fw-bold" style="font-size: 0.85rem; line-height: 1.2;">{{$grup->nombreGrupo}}</p>
                    </a>
                  </div>
                @endforeach
                <div class="col-1"></div>
              </div>
            </div>
          @endforeach
        </div>
        @php $dis = ""; @endphp
        @if(count($categoria->GrupoProducto) < 4)
            @php $dis = "d-none";  @endphp
        @else
        
        @endif
        <button class="carousel-control-prev" type="button" data-bs-target="#carruselGruposSmart" data-bs-slide="prev">
          <span class="text-dark {{$dis}}"><i class="bi bi-arrow-bar-left" style="font-size: 2rem"></i></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselGruposSmart" data-bs-slide="next">
          <span class="text-dark {{$dis}}"><i class="bi bi-arrow-bar-right" style="font-size: 2rem"></i></span>
        </button>
      </div>
    </div>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :cantCards="16" :filtros="$filtros" />
        </div>
    </div>
    <br>
    <br>
    <x-carrusel_marcas :marcas="$marcas->shuffle()"/>
</div>
<br>
<br>
@endsection