@props([
    'nombre' => '',
    'valor' => '',
    'etiqueta' => '',
])

@php
    // Build a unique ID from the name + value
    $checkboxId = 'filtro-' . \Illuminate\Support\Str::slug($nombre . '-' . $valor);

    // Determine if this checkbox should be checked based on current request
    $currentValues = request()->input(
        str_replace(['[', ']'], ['.', ''], rtrim($nombre, '[]')),
        []
    );
    if (!is_array($currentValues)) {
        $currentValues = [$currentValues];
    }
    $isChecked = in_array($valor, $currentValues);
@endphp

<div class="form-check filtro-checkbox-wrapper">
    <input
        class="form-check-input submit-filtros"
        type="checkbox"
        name="{{ $nombre }}"
        value="{{ $valor }}"
        id="{{ $checkboxId }}"
        @checked($isChecked)
    >
    <label class="form-check-label d-flex justify-content-between w-100" for="{{ $checkboxId }}">
        <span class="text-truncate me-2" title="{{ $etiqueta }}">{{ $etiqueta }}</span>
    </label>
</div>
