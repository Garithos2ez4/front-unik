<div class="card_producto_individual">
    <div class="card w-100 mb-2" style="width: 16rem">
        <a href="{{ route('producto', [$producto->slugProducto]) }}">
            <img src="{{$producto->publicImages()[0]}}" class="card-img-top productimg" alt="...">
        </a>
        <div class="card-body">
            <div class="row" style="height:4rem">
                <a href="{{ route('producto', [$producto->slugProducto]) }}" class="text-decoration-none text-dark fw-bold text-center fs-card-text">
                    <h7 class="card-title letters truncar-tres-lineas">{{ $producto->nombreProducto }}</h7>
                </a>
            </div>
            <div class="row">
                <p class="card-text text-center {{ $producto->estadoColor() }} fs-card-text"><strong>{{ $producto->estadoProductoWeb }}</strong></p>
            </div>
            <div class="row">
                <div class="col-md-12 pt-2 text-start">
                    <p class="mb-0 fs-card-text truncar-one-lineas"><strong style="color:{{ $empres->colorDos }}">Precio:</strong> {{ ($producto->DetalleProducto && !$producto->DetalleProducto->mostrarPrecioWeb) || $producto->precioTotalSol($preciosService) < 1 ? 'Consultar' : 'S/.'.$producto->precioTotalSol($preciosService) }} 
                    <span class="fw-lighter">{{ ($producto->DetalleProducto && !$producto->DetalleProducto->mostrarPrecioWeb) || $producto->precioTotalDolar($preciosService) < 1 || !$producto->usar_tc_fijo ? '' : '($'.$producto->precioTotalDolar($preciosService).')'}}</span></p>
                    <p class="mt-0 mb-0 fs-card-text "><strong style="color:{{ $empres->colorDos }}">Garantía:</strong> {{ $producto->garantia }}</p>
                </div>
            </div>
        </div>
    </div>
</div>