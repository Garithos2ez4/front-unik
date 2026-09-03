@extends('layouts.app')

@section('title', 'Formas de envio')

@section('content')
<div class="container">
    <br>
    <br>
    <div class="row d-flex">
        <div class="col-md-2"></div>
        <div class="col-12 col-md-8 text-center">
            <a href="https://wa.me/51959062011?text=Hola,%20deseo%20registrar%20mi%20pedido%20en%20la%20agencia%20seleccionada" target="_blank">
                <img src="{{asset('storage/enviovertical.png')}}" alt="Envios" width="80%" class="rounded-3">
                <img src="{{asset('storage/enviobanner.png')}}" alt="Envios" width="80%" class="rounded-3 mb-3">
            </a>

            <div class="mt-4 mb-2">
                <a href="https://wa.me/51959062011?text=Hola,%20deseo%20registrar%20mi%20pedido%20en%20la%20agencia%20seleccionada" target="_blank" class="btn btn-primary btn-lg rounded-pill shadow fw-bold px-4 py-3" style="transition: transform 0.2s; font-size: 1.15rem; background-color:#25d366; border-color:#25d366;">
                    <i class="fab fa-whatsapp me-2"></i> Solicita tu enlace para registrar tu envio
                </a>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <br>
    <br>
</div>
@endsection