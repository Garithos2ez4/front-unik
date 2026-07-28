@extends('layouts.app')

@section('title', $tipo->tipoProducto . ' | ' . (is_object($selectgroup) && property_exists($selectgroup, 'nombreGrupo') ? $selectgroup->nombreGrupo : 'Todos'))

@section('content')
<br>
<div class="container">
    <h1 class="text-center">{{$tipo->tipoProducto}}</h1>
        @if($selectgroup == null)
            <h3 class="text-center text-black-50">Todos</h3>
        @else
            <h3 class="text-center text-black-50">{{$selectgroup->nombreGrupo}}</h3>
        @endif
    <div class="row">
        <div id="carruselGrupos" class="carousel slide d-none d-sm-block" data-bs-ride="carousel" data-bs-interval="false" style="height:20vh">
        <div class="carousel-inner">
          @php
            $chunks = collect($grupos)->chunk(5);
          @endphp
          @foreach ($chunks as $index => $chunk)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}  text-center">
              <div class="row justify-content-center">
                  <div class="col-1"></div>
                @foreach ($chunk as $grupo)
                  <div class="col-2">
                     <a href="{{route('tipo', [$tipo->slugTipo,$grupo->slugGrupo])}}" class="text-decoration-none text-dark">
                        <img src="{{asset('storage/'.$grupo->imagenGrupo)}}" alt="Marca {{ $loop->parent->iteration }}" width="80%" class="rounded-3 group-hover">
                        <h4>{{$grupo->nombreGrupo}}</h4>
                    </a>
                  </div>
                @endforeach
                <div class="col-1"></div>
              </div>
            </div>
          @endforeach
        </div>
        @php $dis = ""; @endphp
        @if(count($grupos) < 6)
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
      <div id="carruselGruposSmart" class="carousel slide d-block d-md-none" data-bs-ride="carousel" data-bs-interval="false"  style="height:10vh">
        <div class="carousel-inner">
          @php
            $chunks = collect($grupos)->chunk(3);
          @endphp
          @foreach ($chunks as $index => $chunk)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}  text-center">
              <div class="row justify-content-center">
                  <div class="col-1"></div>
                @foreach ($chunk as $grupo)
                  <div class="col-3">
                     <a href="{{route('tipo', [$tipo->slugTipo,$grupo->slugGrupo])}}" class="text-decoration-none text-dark">
                        <img src="{{asset('storage/'.$grupo->imagenGrupo)}}" alt="Marca {{ $loop->parent->iteration }}" width="80%" class="border rounded-3">
                        <p>{{$grupo->nombreGrupo}}</p>
                    </a>
                  </div>
                @endforeach
                <div class="col-1"></div>
              </div>
            </div>
          @endforeach
        </div>
        @php $dis = ""; @endphp
        @if(count($grupos) < 4)
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
    @if(count($productos) == 0)
        <div class="container align-middle" style="height:50vh">
            <div class="row" style="height:20vh">
            </div>
            <x-aviso_no_encontrado :nameProduct="''" />
        </div>
    @else
    <div class="row">
        <div class="col-md-2 d-none d-sm-block" >
            <div class="row" style="height:3rem">
            </div>
            <x-filtro_medio :pagina="'tipo'" :parametrosFillMedio="$parametrosFiltro" :totalProductsMedio="count($productos)"/>
        </div>
        <div class="col-md-10 d-none d-md-flex" id="reload_filtro" >
            <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :dolar="$tipoCambio" :cantCards="16" :filtros="$filtros ?? null" />
        </div>
        <div class="col-12 d-block d-sm-none sticky-div" id="stickyDiv">
            <x-filtro_small :empresaFillSmall="$empresa" :pagina="'tipo'" :parametrosFillSmall="$parametrosFiltro" :totalProductsSmall="count($productos)" :paginaFillSmall="'tipo'"/>
        </div>
        <div class="col-12 d-flex d-sm-none">
            <x-card_producto_small :selectedProducts="$productos" :colsmall="6" :colmedio="4" :empres="$empresa" :dolar="$tipoCambio"/>
        </div>
    </div>
    <br>
    <div class="row">
        @php
            $productoService = app('App\Services\ProductoServiceInterface');
            // Use same category or type logic. Let's just pass 15 products randomly from the type or category.
            // But we don't have $categoria directly accessible. Let's use getProductsFilter.
            $sugerencias = $productoService->getProductsFilter('idTipo', $tipo->idTipo, 15, request());
        @endphp
        <div class="col-12">
            <x-slider_medio :producto="$sugerencias" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Productos Sugeridos'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="'#'"/>
        </div>
    </div>
    @endif
</div>
@endsection