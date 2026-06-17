@extends('layouts.app')

@section('title', 'Nuevos Ingresos')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <h2 class="text-empresa-uno mb-4">Nuevos Ingresos</h2>
    </div>
    <div class="row">
        <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :cantCards="30" :filtros="$filtros" />
    </div>
    <br>
    <x-carrusel_marcas :marcas="$marcas->shuffle()"/>
    <br>
    <br>
</div>
@endsection
