@extends('layouts.app')


@section('content')
{{-- ---------------------------------------BANNERS SOLO A 1920 * 740p--------------------------------------- --}}
<br>
<br>
<div class="container" >
  <div class="row">
    <div id="corruselHome" class="carousel slide w-100" data-bs-ride="carousel">
      <div class="carousel-inner">
          @php $contador=0 @endphp
          @foreach($banners->where('tipoPublicidad','BANNER') as $banner)
            @if($contador == 0)
                <div class="carousel-item active">
                  {{-- <img src="{{asset('storage/'.$banner->imagenPublicidad)}}" alt="" width="90% " height="10%" class="rounded-3 d-block w-100" > --}}
                </div>
                @php $contador++;  @endphp
            @else
                <div class="carousel-item">
                 {{-- <img src="{{asset('storage/'.$banner->imagenPublicidad)}}" alt="" width="90% " height="10%" class="rounded-3 d-block w-100" > --}}
                </div>
                @php $contador++;  @endphp
            @endif
          @endforeach
      </div>
    </div>
  </div>
  <div class="row pt-2 text-center">
      <div class="col-12">
          @php $contador=0 @endphp
          @foreach($banners->where('tipoPublicidad','BANNER') as $banner)
            @if($contador == 0)
                <button type="button" data-bs-target="#corruselHome" data-bs-slide-to="{{$contador}}" class="btn letters active" aria-current="true" aria-label="Slide {{$contador + 1}}"> <i class="bi bi-circle-fill"></i></button>
                @php $contador++;  @endphp
            @else
                <button type="button" data-bs-target="#corruselHome" data-bs-slide-to="{{$contador}}" class="btn letters" aria-label="Slide {{$contador + 1}}"><i class="bi bi-circle-fill"></i></button>
                @php $contador++;  @endphp
            @endif
          @endforeach
      </div>
  </div>
  <br>
  <br>
  <div class="row" >
    <div class="col-12">
        <x-slider_medio :producto="$monitores" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Monitores'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="route('categoria', ['monitores', 'monitor-fhd'])"/>
    </div>
 </div>
  <br>
  <br>
  <div class="row " >
        <div class="col-12 col-md-3 d-flex justify-content-center align-items-end pb-2 pe-0 mb-4">
            <div class="text-center pe-2">
                {{--<img src="{{asset('storage/'.$banners->where('descripcionPublicidad','Banner6')->first()->imagenPublicidad)}}" alt="" class="rounded-3 border shadow img-vertical" > --}}
            </div>
        </div>
        <div class="col-12 col-md-9 mb-4">
            <x-slider_medio :producto="$exclusivos" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Exclusivos y Ofertas'" :sizeCardMed="'25%'" :slideMedio="3" :slideSmall="4" :link="route('ofertas')"/>
        </div>
    </div>
  <br>
  @if(!empty($banners->where('descripcionPublicidad','Banner9')->first()?->imagenPublicidad))
  <div class="row">
      <img src="{{asset('storage/'.$banners->where('descripcionPublicidad','Banner9')->first()->imagenPublicidad)}}" alt="" class="rounded-3 border shadow" >
  </div>
  @endif
  <br>
 <div class="row" >
    <div class="col-12">
        <x-slider_medio :producto="$impresoras" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Impresoras'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="route('categoria', ['impresoras','impresora-tinta'])"/>
    </div>
 </div>
  <br>
  <div class="row" >
      <div class="col-12 col-md-9">
        <x-slider_medio :producto="$accesorios" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Accesorios'" :sizeCardMed="'25%'" :slideMedio="13" :slideSmall="8" :link="route('categoria', ['perifericos','mouses'])"/>
    </div>
      <div class="col-12 col-md-3  d-flex justify-content-center align-items-end pb-2 ps-0">
          <div class="text-center ps-2">
           {{-- <img src="{{asset('storage/'.$banners->where('descripcionPublicidad','Banner8')->first()->imagenPublicidad)}}" alt="" class="rounded-3 border shadow img-vertical" > --}}
          </div>
      </div>
  </div>
  <br>

 <div class="row">
     <div class="col-12">
         <x-slider_medio :producto="$lapGamer" :empre="$empresa" :cambio="$tipoCambio" :titulo="'Laptops Gamer'" :sizeCardMed="'20%'" :slideMedio="5" :slideSmall="8" :link="route('categoria', ['laptops','laptop-gamer'])"/>
     </div>
 </div>
 <br>
<br>
    <x-carrusel_marcas :marcas="$marcas->shuffle()"/>
<br>
<br>
</div>
<script>
      document.getElementById('corruselHome').addEventListener('slide.bs.carousel', function (e) {
            var activeIndex = Array.from(e.target.querySelectorAll('.carousel-item')).indexOf(e.relatedTarget);
            var buttons = document.querySelectorAll('.letters');
            buttons.forEach(function (button, index) {
                if (index === activeIndex) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        });
</script>

@endsection
