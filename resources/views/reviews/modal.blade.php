<!-- Modal para Dejar Reseña -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: {{$empresa->colorUno}}; color: white;">
        <h5 class="modal-title" id="reviewModalLabel"><i class="bi bi-star-fill text-warning"></i> Deja tu reseña</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:none !important;" aria-hidden="true">
            <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
        </div>
        <div class="modal-body">
            <h6 class="border-bottom pb-2 mb-3 text-muted">Tus Datos</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tipo Documento</label>
                    <select name="idTipoDocumento" class="form-select" required>
                        @foreach($tipoDocumentos as $td)
                            <option value="{{ $td->idTipoDocumento }}"
                                {{ old('idTipoDocumento') == $td->idTipoDocumento ? 'selected' : '' }}>
                                {{ $td->descripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Número de Documento</label>
                    <input type="text" class="form-control" name="numeroDocumento" required maxlength="15"
                           value="{{ old('numeroDocumento') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="nombre" maxlength="100"
                           value="{{ old('nombre') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidoPaterno" maxlength="100"
                           value="{{ old('apellidoPaterno') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="correo" maxlength="100"
                           value="{{ old('correo') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono/Celular</label>
                    <input type="text" class="form-control" name="telefono" maxlength="15"
                           value="{{ old('telefono') }}">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3 text-muted">Tu Reseña</h6>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">¿De qué producto opinas? (Opcional)</label>
                    <select name="idProducto" id="idProducto" class="form-select" placeholder="Busca un producto...">
                        <option value="">Reseña general de la tienda</option>
                        @foreach($productos as $prod)
                            <option value="{{ $prod->idProducto }}"
                                {{ old('idProducto') == $prod->idProducto ? 'selected' : '' }}>
                                {{ $prod->nombreProducto }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label d-block">Calificación</label>
                    <div class="rating-stars fs-3 text-warning" style="cursor: pointer;">
                        <i class="bi bi-star-fill star-rating" data-value="1"></i>
                        <i class="bi bi-star-fill star-rating" data-value="2"></i>
                        <i class="bi bi-star-fill star-rating" data-value="3"></i>
                        <i class="bi bi-star-fill star-rating" data-value="4"></i>
                        <i class="bi bi-star-fill star-rating" data-value="5"></i>
                    </div>
                    <input type="hidden" name="calificacion" id="calificacionInput" value="{{ old('calificacion', 5) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Comentario</label>
                    <textarea class="form-control" name="comentario" rows="3" required maxlength="1000" placeholder="Cuéntanos tu experiencia...">{{ old('comentario') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Foto de tu setup / producto (Opcional)</label>
                    <input type="file" class="form-control" name="imagen_setup" accept="image/*">
                </div>

                {{-- ===== CAPTCHA ===== --}}
                <div class="col-md-12 mt-3">
                    <label class="form-label fw-semibold">Verificación de seguridad <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="captcha-img-wrapper" style="border-radius:8px; overflow:hidden; border: 2px solid #dee2e6;">
                            {!! captcha_img('flat') !!}
                        </div>
                        <button type="button" id="refresh-captcha" class="btn btn-outline-secondary btn-sm" title="Generar nuevo captcha">
                            <i class="bi bi-arrow-clockwise"></i> Refrescar
                        </button>
                    </div>
                    <input type="text"
                           class="form-control mt-2 @error('captcha') is-invalid @enderror"
                           name="captcha"
                           id="captcha"
                           placeholder="Escribe los caracteres que ves arriba"
                           required
                           autocomplete="off"
                           maxlength="10">
                    @error('captcha')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                    @enderror
                    <small class="text-muted">Ingresa exactamente los caracteres que aparecen en la imagen.</small>
                </div>
                {{-- ===== /CAPTCHA ===== --}}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn fw-bold" style="background-color: {{$empresa->colorDos}}; color: white;">Enviar Reseña</button>
        </div>
      </form>
    </div>
  </div>
</div>
