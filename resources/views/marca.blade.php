@extends('layouts.app')

@section('title', $marca->nombreMarca)

@section('content')
<div class="container">
    <br>
    <div class="row text-center align-items-center d-flex">
        <div class="col-12 align-items-center">
            <h1 class="fw-bold">{{$marca->nombreMarca}}</h1>
            <img src="{{asset('storage/'.$marca->imagenMarca)}}" alt="Marca" height="50px" class="rounded-3">
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