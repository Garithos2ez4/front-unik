{{--
    Vista de Filtros Refactorizada
    Mejoras: Semántica, Accesibilidad (a11y), UX/UI, Estados de Filtros, Feedback Visual
--}}
@props([
'filtros' => [
'disponibilidad' => [],
'marcas' => [],
'grupos' => [],
'caracteristicas' => [],
],
'empres' => null,
'totalProducts' => 0,
])

@php
$filtrosActivos = (object) request()->get('filtro', []);
$tieneFiltrosActivos = !empty(array_filter((array) $filtrosActivos));
@endphp

{{-- ==================== CONTENEDOR PRINCIPAL DEL FORMULARIO ==================== --}}
<form action="{{ url()->current() }}" method="GET" id="form-filtro-products" class="filtros-form">

    {{-- ==================== VERSIÓN ESCRITORIO (> sm) ==================== --}}
    <aside class="d-none d-lg-block filtros-sidebar" aria-label="Filtros de búsqueda">

        {{-- 1. CABECERA CON ORDENAMIENTO Y BOTÓN DE LIMPIAR --}}
        <div class="filtros-header">
            <h5 class="filtros-titulo">Filtros</h5>
            @if($tieneFiltrosActivos)
            <button type="button" class="btn btn-link btn-limpiar-filtros" title="Limpiar todos los filtros" aria-label="Limpiar filtros">
                <small>Limpiar todo</small>
            </button>
            @endif
        </div>

        {{-- 2. PILAS DE FILTROS ACTIVOS --}}
        <div class="filtros-activos" aria-label="Filtros activos"></div>

        {{-- 3. ORDENAMIENTO --}}
        <div class="mb-3">
            <label for="orden-desktop" class="form-label fw-bold mb-1">Ordenar por</label>
            <select class="form-select submit-filtros filtro-orden" id="orden-desktop" name="filtro[orden]">
                <option value="">Relevancia</option>
                <option value="asc" {{ request('filtro.orden') == 'asc' ? 'selected' : '' }}>Menor precio</option>
                <option value="desc" {{ request('filtro.orden') == 'desc' ? 'selected' : '' }}>Mayor precio</option>
            </select>
        </div>

        {{-- 4. ACORDEÓN DE FILTROS --}}
        <div class="accordion accordion-flush filtros-acordeon" id="accordionFiltrosDesktop">

            {{-- Disponibilidad --}}
            @if(count($filtros['disponibilidad']) > 0)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDispDesktop">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDispDesktop" aria-expanded="true" aria-controls="collapseDispDesktop">
                        Disponibilidad
                    </button>
                </h2>
                <div id="collapseDispDesktop" class="accordion-collapse collapse show" aria-labelledby="headingDispDesktop">
                    <div class="accordion-body">
                        @foreach ($filtros['disponibilidad'] as $dispo)
                        <x-filtro-checkbox nombre="filtro[dispo][]" valor="{{$dispo}}" etiqueta="{{$dispo}}" />
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Marca --}}
            @if(count($filtros['marcas']) > 0)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMarcasDesktop">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMarcasDesktop" aria-expanded="false" aria-controls="collapseMarcasDesktop">
                        Marca
                    </button>
                </h2>
                <div id="collapseMarcasDesktop" class="accordion-collapse collapse" aria-labelledby="headingMarcasDesktop">
                    <div class="accordion-body">
                        @foreach ($filtros['marcas'] as $marca)
                        @php $isMarcaObj = is_object($marca); @endphp
                        <x-filtro-checkbox nombre="filtro[marcas][]" valor="{{ $isMarcaObj ? $marca->idMarca : $marca['idMarca'] }}" etiqueta="{{ $isMarcaObj ? $marca->nombreMarca : $marca['nombreMarca'] }}" />
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Categoría --}}
            @if(count($filtros['grupos']) > 0)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCatDesktop">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCatDesktop" aria-expanded="false" aria-controls="collapseCatDesktop">
                        Categoría
                    </button>
                </h2>
                <div id="collapseCatDesktop" class="accordion-collapse collapse" aria-labelledby="headingCatDesktop">
                    <div class="accordion-body">
                        @foreach ($filtros['grupos'] as $grupo)
                        @php $isGrupoObj = is_object($grupo); @endphp
                        <x-filtro-checkbox nombre="filtro[grupos][]" valor="{{ $isGrupoObj ? $grupo->idGrupoProducto : $grupo['idGrupoProducto'] }}" etiqueta="{{ $isGrupoObj ? $grupo->nombreGrupo : $grupo['nombreGrupo'] }}" />
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Especificaciones --}}
            @if(count($filtros['caracteristicas']) > 0)
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSpecsDesktop">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecsDesktop" aria-expanded="false" aria-controls="collapseSpecsDesktop">
                        Especificaciones
                    </button>
                </h2>
                <div id="collapseSpecsDesktop" class="accordion-collapse collapse" aria-labelledby="headingSpecsDesktop">
                    <div class="accordion-body p-0">
                        <div class="accordion accordion-flush" id="subAccordionSpecsDesktop">
                            @foreach ($filtros['caracteristicas'] as $indice => $caracteristica)
                            @if($caracteristica['tipo'] == 'FILTRO')
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="subHeadingSpec-{{$indice}}">
                                    <button class="accordion-button collapsed py-2 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#subCollapseSpec-{{$indice}}" aria-expanded="false" aria-controls="subCollapseSpec-{{$indice}}">
                                        <small class="fw-bold">{{$caracteristica['nombre']}}</small>
                                    </button>
                                </h2>
                                <div id="subCollapseSpec-{{$indice}}" class="accordion-collapse collapse" aria-labelledby="subHeadingSpec-{{$indice}}">
                                    <div class="accordion-body pt-0 pb-0">
                                        @foreach ($caracteristica['especificaciones'] as $spect)
                                        <x-filtro-checkbox nombre="filtro[caracteristicas][]" valor="{{$spect->caracteristicaProducto}}" etiqueta="{{$spect->caracteristicaProducto}}" />
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </aside>

    {{-- ==================== OVERLAY DE CARGA ==================== --}}
    <div id="filtro-loading-overlay" class="filtro-overlay" style="display: none;">
        <div class="spinner-border text-empresa-uno" role="status">
            <span class="visually-hidden">Aplicando filtros...</span>
        </div>
    </div>

    {{-- ==================== VERSIÓN MÓVIL (OFFCANVAS) ==================== --}}
    <div class="row align-items-center pt-2 pb-2 bg-body d-lg-none sticky-top shadow-sm">
        <div class="col-6">
            <span class="fw-light"><span id="recount">{{$totalProducts}}</span> productos</span>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="offcanvas" href="#offcanvasFiltrosMobile" aria-controls="offcanvasFiltrosMobile">
                <i class="bi bi-funnel me-1"></i> Filtros
                @if($tieneFiltrosActivos)
                <span class="badge bg-dark ms-1"><span id="contador-filtros-mobile">0</span></span>
                @endif
            </button>
        </div>
    </div>

    {{-- Offcanvas Móvil --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFiltrosMobile" aria-labelledby="offcanvasFiltrosLabelMobile" style="width: 85% !important;">
        <div class="offcanvas-header" style="background-color: var(--bs-dark, #212529); color: white;">
            <h5 class="offcanvas-title" id="offcanvasFiltrosLabelMobile">
                Filtros
                @if($tieneFiltrosActivos)
                <small class="fw-light">(<span id="contador-filtros-mobile-offcanvas">0</span> activos)</small>
                @endif
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">

            {{-- Contenedor Scrollable para los filtros --}}
            <div class="flex-grow-1 overflow-auto">
                {{-- Ordenar por (móvil) — sin name para evitar duplicado con el de desktop --}}
                <div class="mb-3">
                    <label for="orden-mobile" class="form-label fw-bold">Ordenar por</label>
                    <select class="form-select form-select-sm filtro-orden" id="orden-mobile">
                        <option value="">Relevancia</option>
                        <option value="asc" {{ request('filtro.orden') == 'asc' ? 'selected' : '' }}>Menor precio</option>
                        <option value="desc" {{ request('filtro.orden') == 'desc' ? 'selected' : '' }}>Mayor precio</option>
                    </select>
                </div>

                {{-- Disponibilidad Móvil --}}
                @if(count($filtros['disponibilidad']) > 0)
                <div class="card card-filtro-mobile mb-2">
                    <button class="btn btn-toggle-filtro text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDispMobile" aria-expanded="false" aria-controls="collapseDispMobile">
                        Disponibilidad <i class="bi bi-chevron-down float-end"></i>
                    </button>
                    <div class="collapse" id="collapseDispMobile">
                        <div class="card-body pt-0">
                            @foreach ($filtros['disponibilidad'] as $dispo)
                            <x-filtro-checkbox nombre="filtro[dispo][]" valor="{{$dispo}}" etiqueta="{{$dispo}}" />
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Marcas Móvil --}}
                @if(count($filtros['marcas']) > 0)
                <div class="card card-filtro-mobile mb-2">
                    <button class="btn btn-toggle-filtro text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMarcasMobile" aria-expanded="false" aria-controls="collapseMarcasMobile">
                        Marca <i class="bi bi-chevron-down float-end"></i>
                    </button>
                    <div class="collapse" id="collapseMarcasMobile">
                        <div class="card-body pt-0">
                            @foreach ($filtros['marcas'] as $marca)
                            @php $isMarcaObj = is_object($marca); @endphp
                            <x-filtro-checkbox nombre="filtro[marcas][]" valor="{{ $isMarcaObj ? $marca->idMarca : $marca['idMarca'] }}" etiqueta="{{ $isMarcaObj ? $marca->nombreMarca : $marca['nombreMarca'] }}" />
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Categorías Móvil --}}
                @if(count($filtros['grupos']) > 0)
                <div class="card card-filtro-mobile mb-2">
                    <button class="btn btn-toggle-filtro text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCatMobile" aria-expanded="false" aria-controls="collapseCatMobile">
                        Categoría <i class="bi bi-chevron-down float-end"></i>
                    </button>
                    <div class="collapse" id="collapseCatMobile">
                        <div class="card-body pt-0">
                            @foreach ($filtros['grupos'] as $grupo)
                            @php $isGrupoObj = is_object($grupo); @endphp
                            <x-filtro-checkbox nombre="filtro[grupos][]" valor="{{ $isGrupoObj ? $grupo->idGrupoProducto : $grupo['idGrupoProducto'] }}" etiqueta="{{ $isGrupoObj ? $grupo->nombreGrupo : $grupo['nombreGrupo'] }}" />
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Especificaciones Móvil --}}
                @if(count($filtros['caracteristicas']) > 0)
                <div class="card card-filtro-mobile mb-2">
                    <button class="btn btn-toggle-filtro text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecsMobile" aria-expanded="false" aria-controls="collapseSpecsMobile">
                        Especificaciones <i class="bi bi-chevron-down float-end"></i>
                    </button>
                    <div class="collapse" id="collapseSpecsMobile">
                        <div class="card-body pt-0">
                            @foreach ($filtros['caracteristicas'] as $indice => $caracteristica)
                            @if($caracteristica['tipo'] == 'FILTRO')
                                <p class="fw-bold mb-1 mt-2"><small>{{$caracteristica['nombre']}}</small></p>
                                @foreach ($caracteristica['especificaciones'] as $spect)
                                <x-filtro-checkbox nombre="filtro[caracteristicas][]" valor="{{$spect->caracteristicaProducto}}" etiqueta="{{$spect->caracteristicaProducto}}" />
                                @endforeach
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- Footer del Offcanvas con acciones --}}
            <div class="offcanvas-footer pt-3 border-top">
                <div class="d-grid gap-2 d-flex">
                    <button type="button" class="btn btn-outline-secondary flex-grow-1 btn-limpiar-filtros">Limpiar</button>
                    <button type="button" class="btn btn-dark flex-grow-1 btn-aplicar-mobile" data-bs-dismiss="offcanvas">Ver <span id="recount-mobile">{{$totalProducts}}</span> resultados</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- ==================== ESTILOS CSS ==================== --}}
@push('styles')
<style>
    /* 1. Estilos Generales del Sidebar */
    .filtros-sidebar {
        padding: 1rem 1.25rem;
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 0.5rem;
        background-color: #fff;
        position: sticky;
        top: 1rem;
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
    }

    /* 2. Cabecera */
    .filtros-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .filtros-titulo {
        margin-bottom: 0;
        font-size: 1.1rem;
    }

    .btn-limpiar-filtros {
        color: #dc3545;
        text-decoration: none;
        padding: 0;
    }

    /* 3. Píldoras de Filtros Activos */
    .filtros-activos:empty {
        display: none;
    }

    /* 4. Acordeón Refinado */
    .filtros-acordeon .accordion-button {
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 0;
        background-color: transparent;
        box-shadow: none;
    }

    .filtros-acordeon .accordion-button:not(.collapsed) {
        color: inherit;
        background-color: transparent;
    }

    .filtros-acordeon .accordion-body {
        padding: 0 0 0.5rem 1.5rem;
    }

    /* 5. Checkbox Custom */
    .filtro-checkbox-wrapper {
        padding: 0.3rem 0;
        margin-bottom: 0;
        border-radius: 0.25rem;
        transition: background-color 0.15s ease-in-out;
    }

    .filtro-checkbox-wrapper:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .filtro-checkbox-wrapper .form-check-label {
        cursor: pointer;
        font-size: 0.85rem;
        color: #495057;
    }

    /* 6. Overlay de Carga */
    .filtro-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 1055;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(2px);
    }

    /* 7. Estilos Móviles */
    .card-filtro-mobile {
        border: none;
        border-bottom: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 0;
    }

    .btn-toggle-filtro {
        background: none;
        border: none;
        width: 100%;
        padding: 0.75rem 0;
        font-weight: 600;
        color: #212529;
    }

    .card-filtro-mobile .card-body {
        max-height: 200px;
        overflow-y: auto;
        padding-left: 1.5rem;
    }

    .offcanvas-footer {
        background-color: #fff;
    }
</style>
@endpush

{{-- ==================== JAVASCRIPT ==================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-filtro-products');
        if (!form) return;

        const overlay = document.getElementById('filtro-loading-overlay');

        // Función para mostrar el overlay (el AJAX se encarga del fetch en card_producto_medio)
        const aplicarFiltrosConFeedback = () => {
            if (overlay) overlay.style.display = 'flex';
            // El submit real se maneja vía fetch() en card_producto_medio.blade.php
            // al escuchar el evento 'change' en los inputs.
        };

        // Event Listeners para Checkboxes de envío automático (solo en desktop)
        // En móvil el usuario aplica al cerrar el offcanvas con el botón "Ver resultados"
        form.querySelectorAll('.filtros-sidebar .submit-filtros[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', aplicarFiltrosConFeedback);
        });

        // Selects de orden en desktop: submit automático
        const ordenDesktop = document.getElementById('orden-desktop');
        if (ordenDesktop) {
            ordenDesktop.addEventListener('change', aplicarFiltrosConFeedback);
        }

        // Sincronizar los selects de orden entre desktop y mobile
        const ordenMobile = document.getElementById('orden-mobile');
        if (ordenMobile && ordenDesktop) {
            ordenMobile.addEventListener('change', function() {
                ordenDesktop.value = this.value;
            });
        }

        // Botón "Ver resultados" en móvil: sincronizar orden y enviar
        const btnAplicarMobile = form.querySelector('.btn-aplicar-mobile');
        if (btnAplicarMobile) {
            btnAplicarMobile.addEventListener('click', function() {
                // Sincronizar el valor del orden mobile al input desktop (que tiene el name real)
                if (ordenMobile && ordenDesktop) {
                    ordenDesktop.value = ordenMobile.value;
                }
                aplicarFiltrosConFeedback();
                const firstInput = form.querySelector('.submit-filtros');
                if(firstInput) firstInput.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }

        // Funcionalidad para el botón "Limpiar todo"
        document.querySelectorAll('.btn-limpiar-filtros').forEach(btn => {
            btn.addEventListener('click', function() {
                form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                form.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
                aplicarFiltrosConFeedback();
                const firstInput = form.querySelector('.submit-filtros');
                if(firstInput) firstInput.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });

        // Lógica para contador de filtros activos (móvil)
        function actualizarContadorFiltros() {
            const totalActivos = form.querySelectorAll('input[type="checkbox"]:checked').length;
            document.querySelectorAll('#contador-filtros-mobile, #contador-filtros-mobile-offcanvas').forEach(el => {
                if (el) el.textContent = totalActivos;
            });
        }

        actualizarContadorFiltros();
        form.addEventListener('change', actualizarContadorFiltros);
    });
</script>
@endpush