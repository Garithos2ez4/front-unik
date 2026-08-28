@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="fw-bold" style="color:{{$empresa->colorDos}}">Trabaja con Nosotros</h1>
                        <p class="text-muted fs-5">Únete al equipo de Unikstore y crece profesionalmente con nosotros</p>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="text-center">
                                <i class="bi bi-person-workspace display-4 mb-3" style="color:{{$empresa->colorDos}}"></i>
                                <h4 class="fw-semibold">Desarrollo Profesional</h4>
                                <p class="text-muted">Buscamos talento apasionado por la tecnología. Aquí encontrarás un ambiente dinámico donde podrás aprender, aportar tus ideas y desarrollarte en el área e-commerce y retail.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="text-center">
                                <i class="bi bi-people display-4 mb-3" style="color:{{$empresa->colorDos}}"></i>
                                <h4 class="fw-semibold">Un Gran Equipo</h4>
                                <p class="text-muted">Formarás parte de un equipo comprometido con la excelencia y la innovación constante. Valoramos el talento humano y el trabajo colaborativo.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center bg-light p-4 rounded-4">
                        <h5 class="fw-bold mb-3">¿Quieres ser parte de Unikstore?</h5>
                        <p class="mb-4">Envíanos un mensaje por WhatsApp adjuntando tu CV y cuéntanos por qué te gustaría trabajar con nosotros.</p>

                        <a href="https://wa.me/51902562085?text=Hola,%20quisiera%20postular%20para%20trabajar%20en%20Unikstore.%20Adjunto%20mi%20CV." target="_blank" class="btn btn-lg fw-bold" style="background-color: #25D366; color: white;">
                            <i class="bi bi-whatsapp me-2"></i> Enviar mi CV por WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection