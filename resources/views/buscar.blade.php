@extends('layouts.app')

@section('content')
    @if(count($productos) == 0)
    
    <div class="container align-middle" style="height:50vh">
        <div class="row" style="height:20vh">
        </div>
        <x-aviso_no_encontrado :nameProduct="$obt"/>
    </div>
    @else
    <br>
    <br>
    <div class="container ">
        <div class="row">
            <div class="col-12">
                <x-card_producto_medio :storage="$productos" :colsmall="6" :colmedio="3" :empres="$empresa" :cantCards="16" :filtros="$filtros" />
            </div>
        </div>
        <br>
        <x-carrusel_marcas :marcas="$marcas->shuffle()"/>
    </div>
    <br>
    <br>
    @endif
@endsection