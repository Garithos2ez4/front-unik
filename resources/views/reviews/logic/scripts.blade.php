<style>
#captcha-img-wrapper img { display: block; max-width: 100%; height: auto; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar TomSelect para el select de productos
        const tsProducto = new TomSelect('#idProducto', {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Revisar si venimos de la pagina de un producto
        const urlParams = new URLSearchParams(window.location.search);
        const prodId = urlParams.get('prod');
        if (prodId) {
            tsProducto.setValue(prodId);
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        }

        // Re-abrir el modal si hubo errores de validación (captcha incorrecto, etc.)
        @if ($errors->any())
            (function() {
                const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
                reviewModal.show();
                // Refrescar imagen del captcha para que el usuario tenga un nuevo intento limpio
                const img = document.querySelector('#captcha-img-wrapper img');
                if (img) {
                    const src = img.src.split('?')[0];
                    img.src = src + '?' + Date.now();
                }
            })();
        @endif

        const stars = document.querySelectorAll('.star-rating');
        const calificacionInput = document.getElementById('calificacionInput');

        // Función para actualizar visualmente las estrellas
        function updateStars(value) {
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-value')) <= parseInt(value)) {
                    s.classList.remove('bi-star');
                    s.classList.add('bi-star-fill');
                } else {
                    s.classList.remove('bi-star-fill');
                    s.classList.add('bi-star');
                }
            });
        }

        // Restaurar calificación guardada (old value)
        const savedCalificacion = calificacionInput.value || '5';
        updateStars(savedCalificacion);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                calificacionInput.value = value;
                updateStars(value);
            });
        });

        // Restaurar producto seleccionado en TomSelect (old value)
        @if(old('idProducto'))
            tsProducto.setValue('{{ old('idProducto') }}');
        @endif

        // Autocomplete client data
        const inputDocumento = document.querySelector('input[name="numeroDocumento"]');
        const inputTipo = document.querySelector('select[name="idTipoDocumento"]');
        const inputNombre = document.querySelector('input[name="nombre"]');
        const inputApellido = document.querySelector('input[name="apellidoPaterno"]');
        const inputCorreo = document.querySelector('input[name="correo"]');
        const inputTelefono = document.querySelector('input[name="telefono"]');

        inputDocumento.addEventListener('blur', function() {
            const documento = this.value.trim();
            if(documento.length > 5) {
                fetch(`{{ url('/reviews/cliente') }}/${documento}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.encontrado) {
                            // Rellenar datos
                            inputTipo.value = data.datos.idTipoDocumento || 1;
                            inputNombre.value = data.datos.nombre || '';
                            inputApellido.value = data.datos.apellidoPaterno || '';
                            inputCorreo.value = data.datos.correo || '';
                            inputTelefono.value = data.datos.telefono || '';

                            // Bloquear campos
                            inputTipo.style.pointerEvents = 'none';
                            inputTipo.style.opacity = '0.7';
                            
                            if (inputNombre.value) inputNombre.setAttribute('readonly', true);
                            if (inputApellido.value) inputApellido.setAttribute('readonly', true);
                            if (inputCorreo.value) inputCorreo.setAttribute('readonly', true);
                            if (inputTelefono.value) inputTelefono.setAttribute('readonly', true);
                        } else {
                            // Limpiar y desbloquear si no existe y estaban bloqueados
                            if (inputNombre.hasAttribute('readonly')) {
                                inputTipo.style.pointerEvents = 'auto';
                                inputTipo.style.opacity = '1';
                                inputNombre.removeAttribute('readonly');
                                inputApellido.removeAttribute('readonly');
                                inputCorreo.removeAttribute('readonly');
                                inputTelefono.removeAttribute('readonly');
                                
                                inputNombre.value = '';
                                inputApellido.value = '';
                                inputCorreo.value = '';
                                inputTelefono.value = '';
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching cliente:', error));
            }
        });

        // ===== Refresh CAPTCHA =====
        const refreshBtn = document.getElementById('refresh-captcha');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const wrapper = document.getElementById('captcha-img-wrapper');
                // Add a timestamp to bust cache
                fetch('{{ url('/captcha/flat') }}?' + Date.now(), { cache: 'no-cache' })
                    .then(response => response.text())
                    .then(() => {
                        // Reload the captcha image by replacing the img src
                        const img = wrapper.querySelector('img');
                        if (img) {
                            const src = img.src.split('?')[0];
                            img.src = src + '?' + Date.now();
                        }
                        // Clear the input
                        const captchaInput = document.getElementById('captcha');
                        if (captchaInput) captchaInput.value = '';
                    })
                    .catch(() => {
                        // Fallback: just reload the img src
                        const img = wrapper.querySelector('img');
                        if (img) {
                            const src = img.src.split('?')[0];
                            img.src = src + '?' + Date.now();
                        }
                    });
            });
        }
        // ===== /Refresh CAPTCHA =====
    });
</script>
