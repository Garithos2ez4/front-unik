<div class="card_producto_individual">
    <div class="card w-100 mb-2" style="width: 16rem">
        <a href="{{ route('producto', [$producto->slugProducto]) }}">
            <img src="{{$producto->publicImages()[0]}}" class="card-img-top productimg" alt="...">
        </a>
        <div class="card-body">
            <div class="row" style="height:4rem">
                <a href="{{ route('producto', [$producto->slugProducto]) }}" class="text-decoration-none text-dark fw-bold text-center fs-card-text">
                    <h2 class="card-title letters truncar-tres-lineas" style="font-size: 1rem; margin-bottom: 0;">{{ $producto->nombreProducto }}</h2>
                </a>
            </div>
            <div class="row">
                <p class="card-text text-center {{ $producto->estadoColor() }} fs-card-text"><strong>{{ $producto->estadoProductoWeb }}</strong></p>
            </div>
            <div class="row">
                <div class="col-md-12 pt-2 text-start">
                    @php
                    $precioSolStrInd = $producto->precioTotalSol($preciosService);
                    $precioSolInd = floatval(str_replace(',', '', $precioSolStrInd));
                    $precioSolNormalStrInd = $producto->precioNormalSol($preciosService);
                    $precioSolNormalInd = floatval(str_replace(',', '', $precioSolNormalStrInd));
                    $descPorcInd = 0;
                    if ($precioSolNormalInd > $precioSolInd && $precioSolNormalInd > 0) {
                    $descPorcInd = round((($precioSolNormalInd - $precioSolInd) / $precioSolNormalInd) * 100);
                    }
                    @endphp
                    <p class="mb-0 fs-card-text"><strong style="color:{{ $empres->colorDos }}">Precio:</strong> <span style="{{ $descPorcInd > 0 ? 'color: #dc3545;' : '' }}">{{ ($producto->DetalleProducto && !$producto->DetalleProducto->mostrarPrecioWeb) || $precioSolInd < 1 ? 'Consultar' : 'S/.'.$precioSolStrInd }}</span>
                        <span class="fw-lighter">{{ ($producto->DetalleProducto && !$producto->DetalleProducto->mostrarPrecioWeb) || $producto->precioTotalDolar($preciosService) < 1 || !$producto->usar_tc_fijo ? '' : '($'.$producto->precioTotalDolar($preciosService).')'}}</span>
                        @if($descPorcInd > 0)
                        <span class="badge bg-danger ms-1" style="font-size: 1.1rem;">-{{ $descPorcInd }}%</span>
                        @endif
                    </p>
                    @if($descPorcInd > 0)
                    <p class="mb-0 fs-card-text text-muted text-decoration-line-through" style="font-size: 1.2rem;">S/.{{ $precioSolNormalStrInd }}</p>
                    @endif
                    <p class="mt-0 mb-0 fs-card-text "><strong style="color:{{ $empres->colorDos }}">Garantía:</strong> {{ $producto->garantia }}</p>
                </div>
            </div>
        </div>
    </div>
</div>