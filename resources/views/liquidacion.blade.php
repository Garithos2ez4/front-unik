@extends('layouts.app')

@section('title', 'Liquidación')

@section('content')
<div class="container">
    <br>
    <div class="row">
        {{-- algun banner --}}
    </div>
    <div class="row">
        <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :cantCards="16" :filtros="$filtros" />
    </div>
    <br>
</div>
@endsection