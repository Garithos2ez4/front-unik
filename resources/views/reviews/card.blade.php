<div class="col-sm-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; background-color: rgba(255, 255, 255, 0.9);">
        <!-- Imagen del Setup -->
        @if($review->imagen_setup)
            <img src="{{ asset('storage/' . $review->imagen_setup) }}" class="card-img-top" alt="Setup de {{ $review->nombre_cliente }}" style="object-fit: cover; height: 250px;">
        @endif
        
        <div class="card-body p-4">
            <!-- Calificación -->
            <div class="mb-2">
                @for($i = 0; $i < 5; $i++)
                    @if($i < $review->calificacion)
                        <i class="bi bi-star-fill text-warning"></i>
                    @else
                        <i class="bi bi-star text-warning"></i>
                    @endif
                @endfor
            </div>
            
            <!-- Comentario -->
            <p class="card-text fw-bold fs-5" style="color: {{$empresa->colorUno}}">"{{ $review->comentario }}"</p>
            
            @if($review->producto)
                <div class="mb-3">
                    <span class="badge" style="background-color: {{$empresa->colorDos}}">
                        <i class="bi bi-box-seam"></i> Producto: {{ Str::limit($review->producto->nombreProducto, 40) }}
                    </span>
                </div>
            @endif
            
            <hr class="text-muted">
            
            <!-- Info del cliente -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $review->cliente->nombre ?? 'Cliente' }} {{ $review->cliente->apellidoPaterno ?? '' }}</h6>
                        <small class="text-muted"><i class="bi bi-check-circle-fill text-success"></i> Comprador verificado</small>
                    </div>
                </div>
                <div>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
