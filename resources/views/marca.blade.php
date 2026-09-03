@extends('layouts.app')

@section('title', $marca->nombreMarca . ' | ' . $empresa->nombreComercial)
@section('description', 'Encuentra los mejores productos de la marca ' . $marca->nombreMarca . ' en ' . $empresa->nombreComercial . '. Calidad garantizada y envíos a todo el Perú.')

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    @if(isset($productos) && method_exists($productos, 'count') && $productos->count() > 0)
        @foreach($productos->take(10) as $index => $prod)
        {
          "@type": "ListItem",
          "position": {{ $index + 1 }},
          "url": "{{ route('producto', $prod->slugProducto) }}"
        }@if(!$loop->last),@endif
        @endforeach
    @endif
  ]
}
</script>
@endpush

@section('content')
<div class="container">
    <br>
    <div class="row text-center align-items-center d-flex">
        <div class="col-12 align-items-center">
            <h1 class="fw-bold">{{$marca->nombreMarca}}</h1>
            <img src="{{asset('storage/'.$marca->imagenMarca)}}" alt="Logo de {{ $marca->nombreMarca }}" height="50px" class="rounded-3">
        </div>
    </div>
    @if(count($productos) == 0)
        <div class="container align-middle" style="height:50vh">
            <div class="row" style="height:20vh">
            </div>
            <x-aviso_no_encontrado :nameProduct="''" />
        </div>
    @else
    <div class="row">
        <div class="col-md-12">
            <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :cantCards="16" :filtros="$filtros" />
        </div>
    </div>
    <br>
    @endif
</div>

@endsection