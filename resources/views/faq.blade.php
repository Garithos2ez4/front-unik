@extends('layouts.app')

@section('title', 'Preguntas Frecuentes - ' . $empresa->nombreComercial)
@section('description', 'Encuentra respuestas a las preguntas más frecuentes sobre envíos, garantías, pagos y más en ' . $empresa->nombreComercial)

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $index => $faq)
    {
      "@type": "Question",
      "name": "{{ $faq['pregunta'] }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{!! strip_tags($faq['respuesta_texto']) !!}"
      }
    }{{ $index < count($faqs) - 1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4 text-center">
        <div class="col-12">
            <h2 class="fw-bold" style="color: {{$empresa->colorUno}}">Preguntas Frecuentes (FAQ) <i class="bi bi-question-circle"></i></h2>
            <p class="text-muted">Encuentra respuestas rápidas a las dudas más comunes de nuestros clientes.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion shadow-sm" id="accordionFAQ">
                
                @foreach($faqs as $index => $faq)
                <div class="accordion-item border-0 {{ $index < count($faqs) - 1 ? 'border-bottom' : '' }}">
                    <h2 class="accordion-header" id="heading{{ $index }}">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                            <i class="bi {{ $faq['icono'] }} me-2 text-primary"></i> {{ $faq['pregunta'] }}
                        </button>
                    </h2>
                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-muted">
                            {!! $faq['respuesta'] !!}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
    
    <div class="row mt-5 text-center">
        <div class="col-12">
            <p class="text-muted mb-2">¿Aún tienes dudas?</p>
            @php $whatsappLink = optional($empresa->EmpresaRedSocial->where('idRedSocial', 5)->first())->enlace ?? 'https://wa.me/51959062011'; @endphp
            <a href="{{ $whatsappLink }}?text=Hola,%20tengo%20una%20consulta" target="_blank" class="btn btn-outline-success rounded-pill fw-bold px-4">
                <i class="bi bi-whatsapp"></i> Escríbenos por WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
