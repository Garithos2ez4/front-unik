/* resources/views/css/dynamic-styles.blade.php */

body {
    background-color: #f8f9fa;
}
.bg-body{
    background-color: #f8f9fa !important;
}

.group-hover:hover{
    border:0.5px solid {{$empresa->colorDos}}!important;
}

.group-selected{
    border:0.5px solid {{$empresa->colorDos}}!important;
}

#loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: {{$empresa->colorTres}};
        display: flex;
        justify-content: center;
        align-items: center;
    }
.dropdown-menu .dropdown-item:focus,
.dropdown-menu .dropdown-item:active {
    background-color: black !important;
    color: white !important;
}
html, body {
height: 100%;
}
.content {
  flex: 1;
}
.wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
.productimg:hover{
    filter: contrast(50%);
}

.letters:hover{
    color:{{$empresa->colorDos}};
}
.letters.active {
    color: {{$empresa->colorDos}}; 
}

.form-check-input:checked {
    background-color: {{$empresa->colorUno}};
  }
  
.retract{
    display:none;
}

.bg-opacity{
    opacity:0.5 ;
}

.text-empresa-uno{
    color:{{$empresa->colorUno}} !important;
}

.text-empresa-dos{
    color:{{$empresa->colorDos}} !important;
}

.text-empresa-tres{
    color:{{$empresa->colorTres}} !important;
}

.text-agotado{
    color: #a30048;
}

.text-oferta{
    color: #eb0000;
}

.text-exclusivo{
    color: #e5b100;
}

.bg-empresa-uno{
    background-color:{{$empresa->colorUno}};
}

.bg-empresa-dos{
    background-color:{{$empresa->colorDos}};
}

.bg-empresa-tres{
    background-color:{{$empresa->colorTres}};
}

.border-empresa-uno{
    border: 1px solid {{$empresa->colorUno}};
}
.border-empresa-dos{
    border: 1px solid {{$empresa->colorDos}};
}

.border-empresa-tres{
    border: 1px solid {{$empresa->colorTres}};
}

.border-color-empresa-uno{
    border-color:{{$empresa->colorUno}} !important;
}

.border-color-empresa-dos{
    border-color:{{$empresa->colorDos}} !important;
}

.border-color-empresa-tres{
    border-color:{{$empresa->colorTres}} !important;
}

.product-hover:hover{
    color:gray;
}

.hover-dark:hover{
    color:gray;
}

.truncar-tres-lineas {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.truncar-one-lineas {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.custom-pagination .page-item .page-link:hover {
    color: {{$empresa->colorTres}};
    background-color: {{$empresa->colorUno}};
    
}

.custom-pagination .page-item.active .page-link {
    z-index: 1;
    color: {{$empresa->colorDos}};
    background-color: {{$empresa->colorTres}};
    border-color: {{$empresa->colorDos}};
}

.custom-pagination .page-item .page-link:focus {
    box-shadow: none;
    outline: none;
}

.custom-pagination .page-item .page-link:active {
    background-color:{{$empresa->colorDos}} !important;
    border-color:{{$empresa->colorDos}} !important;
}

.fs-card-text {
 font-size:80%;   
}

.btn:focus {
    outline: none !important;
    box-shadow: none !important;
}


.form-check-input:focus {
    outline: none !important;
    box-shadow: none !important;
}

 input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none; /* Eliminar estilos predeterminados de Safari */
    appearance: none; /* Eliminar estilos predeterminados */
    width: 20px;
    height: 20px; 
    background-color: {{$empresa->colorUno}};
    border: 2px solid {{$empresa->colorUno}}; 
    border-radius: 50%;
    cursor: pointer;
}

.no-border {
    border: none;
    box-shadow: none;
}

.empresa-hover:hover{
    background-color:{{$empresa->colorDos}};
    color:{{$empresa->colorTres}};
}

.enlace-hover:hover{
    text-decoration: underline !important;
}

.listener-hover {
    position: relative;
}

.listener-hover::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(211, 211, 211, 0.5); /* Gris claro con 50% de opacidad */
    z-index: 1;
    opacity: 0; /* Inicialmente invisible */
    transition: opacity 0.3s; /* Transición suave */
}

.listener-hover:hover::before {
    opacity: 1; /* Asegura que el pseudo-elemento sea visible al hacer hover */
}

.sticky-div {
    z-index: 1000;
    background-color: {{$empresa->colorTres}};
    position: -webkit-sticky; /* Para compatibilidad con Safari */
    position: sticky;
    top: 0;
}

.background-mediodepago {
    background-image: url('{{ asset('storage/cuentasbancarias.jpg') }}');
    background-size: contain; /* Ajusta la imagen al alto y ancho del contenedor */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* No repite la imagen */
}

.overlay {
    position: relative;
    z-index: 1; /* Asegura que los elementos estén encima del fondo */
    width: 100%;
    height: 100%;
}

.form-select:focus {
    box-shadow: none; /* Elimina la sombra del borde */
    outline: none; /* Elimina el contorno */
}

.form-control:focus {
    border-color: gray; /* Elimina el color del borde */
    box-shadow: none; /* Elimina la sombra del borde */
    outline: none; /* Elimina el contorno */
}

.link-select {
    appearance: none; /* Quitar estilo predeterminado */
    background: none;
    border: none;
    color: #007bff; /* Color similar a enlaces */
    font-size: 1rem; /* Ajusta según tus necesidades */
    cursor: pointer; /* Cambia el cursor a puntero */
    text-decoration: underline; /* Simula un enlace subrayado */
}

.link-select:focus {
    outline: none; /* Quita el borde de enfoque */
}

/* Estilo para el borde del select cuando está enfocado */ 
.form-select:focus { 
    border-color: {{$empresa->colorDos}}; /* Cambia el color del borde al enfocar */ 
    box-shadow: {{$empresa->colorDos}}; /* Añade una sombra al enfocar */ 
} 

.form-select option:hover { 
    background-color: {{$empresa->colorDos}} !important; /* Cambia el color de fondo al pasar sobre las opciones */
    color: {{$empresa->colorTres}}; /* Cambia el color del texto al pasar sobre las opciones */ 
}

.img-vertical{
    width: 80%;
    height: auto;
}

@media (min-width:768px){
    .img-vertical{
        width: 100%;
        height: auto;
    }
}

.text-hidden{
    color: {{$empresa->colorTres}};
    opacity: 0.05;
}

/* Estilo de los botones del acordeón (los que abren y cierran los ítems) */
{{-- .accordion-button {
    background-color: {{$empresa->colorDos}};  /* Fondo azul */
    color: white;               /* Color de texto blanco */
    font-weight: bold;          /* Texto en negrita */
    border-radius: 5px;         /* Bordes redondeados */
} --}}

/* Estilo cuando el botón del acordeón está activo (cuando está abierto) */
.accordion-button:not(.collapsed) {
    background-color: {{$empresa->colorTres}};
    color: {{$empresa->colorUno}};
    box-shadow: 0;
}

/* ══════════════════════════════════════════════════════════════════════════
   NAVBAR – Sub-menú de categorías con posicionamiento inteligente
   ══════════════════════════════════════════════════════════════════════════ */

/* Contenedor base del sub-menú */
.nav-submenu-container {
    position: absolute;
    top: 0;
    left: 100%;
    display: none;          /* JS lo cambia a block */
    min-width: 220px;
    max-height: 80vh;       /* nunca supera el 80% de la pantalla */
    overflow-y: auto;       /* scrollable si hay muchos items */
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0,0,0,0.18);
    border-radius: 8px;
    background: #fff;
}

/* Scrollbar delgado y elegante dentro del sub-menú */
.nav-submenu-container::-webkit-scrollbar {
    width: 4px;
}
.nav-submenu-container::-webkit-scrollbar-track {
    background: transparent;
}
.nav-submenu-container::-webkit-scrollbar-thumb {
    background-color: {{$empresa->colorUno}};
    border-radius: 4px;
    opacity: 0.5;
}

/* Items del sub-menú */
.nav-submenu-container .list-group-item {
    border-left: none;
    border-right: none;
    transition: background-color 0.15s ease, padding-left 0.15s ease;
}
.nav-submenu-container .list-group-item:first-child {
    border-top: none;
    border-radius: 8px 8px 0 0;
}
.nav-submenu-container .list-group-item:last-child {
    border-bottom: none;
    border-radius: 0 0 8px 8px;
}
.nav-submenu-container .dropdown-item {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    white-space: normal;
    line-height: 1.3;
    transition: background-color 0.15s ease, color 0.15s ease, padding-left 0.15s ease;
}
.nav-submenu-container .dropdown-item:hover {
    background-color: {{$empresa->colorUno}};
    color: {{$empresa->colorTres}} !important;
    padding-left: 1.4rem;
}

/* Animación del dropdown principal de categorías */
.nav-item.dropdown > .dropdown-menu {
    animation: dropdownFadeIn 0.18s ease forwards;
    border-radius: 8px;
    border: none;
    box-shadow: 0 8px 28px rgba(0,0,0,0.15);
    overflow: visible !important;  /* NO poner hidden: recorta el sub-menú */
    padding: 0.25rem 0;
}
@keyframes dropdownFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Item del dropdown principal al hover */
.nav-item.dropdown .dropdown-menu > li > .dropdown-item {
    transition: background-color 0.15s ease, padding-left 0.15s ease;
    padding: 0.55rem 1.2rem;
    font-size: 0.9rem;
}
.nav-item.dropdown .dropdown-menu > li > .dropdown-item:hover {
    background-color: {{$empresa->colorUno}};
    color: {{$empresa->colorTres}} !important;
    padding-left: 1.5rem;
}
/* Indicador visual de que tiene sub-menú */
.nav-item.dropdown .dropdown-menu > li > .dropdown-item::after {
    content: '›';
    float: right;
    opacity: 0.4;
    transition: opacity 0.15s, transform 0.15s;
}
.nav-item.dropdown .dropdown-menu > li:hover > .dropdown-item::after {
    opacity: 1;
    transform: translateX(3px);
}

/* ══════════════════════════════════════════════════════════════════════════
   BUSCADOR – Autocomplete premium
   ══════════════════════════════════════════════════════════════════════════ */

#suggestions {
    background: #fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-top: none;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.14);
    max-height: 420px;
    overflow-y: auto;
    padding: 0.25rem 0;
    list-style: none;
    display: none;
}
#suggestions::-webkit-scrollbar { width: 4px; }
#suggestions::-webkit-scrollbar-thumb {
    background-color: {{$empresa->colorUno}};
    border-radius: 4px;
}

/* Cada item de sugerencia */
.search-suggestion-item {
    padding: 0;
    border: none;
    list-style: none;
    transition: background-color 0.14s ease;
}
.search-suggestion-item + .search-suggestion-item {
    border-top: 1px solid rgba(0,0,0,0.05);
}

.suggestion-inner {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    transition: background-color 0.14s ease;
}

.search-suggestion-item.suggestion-hovered .suggestion-inner,
.search-suggestion-item:hover .suggestion-inner {
    background-color: rgba(0,0,0,0.04);
}

/* Imagen del producto en sugerencia */
.suggestion-img-wrap {
    flex: 0 0 48px;
    width: 48px;
    height: 48px;
    border-radius: 6px;
    overflow: hidden;
    background: #f4f4f4;
    display: flex;
    align-items: center;
    justify-content: center;
}
.suggestion-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.suggestion-img-placeholder {
    font-size: 1.4rem;
    color: #bbb;
}

/* Texto del producto en sugerencia */
.suggestion-info {
    flex: 1;
    min-width: 0;
}
.suggestion-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: #222;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}
.suggestion-part {
    font-size: 0.72rem;
    color: #888;
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.suggestion-price {
    margin-top: 3px;
    font-size: 0.78rem;
}
.suggestion-price-dolar {
    font-weight: 700;
    color: {{$empresa->colorUno}};
}
.suggestion-price-sol {
    color: #666;
    margin-left: 4px;
}
.suggestion-price-consultar {
    color: #aaa;
    font-style: italic;
}
.suggestion-arrow {
    color: #ccc;
    font-size: 0.75rem;
    flex: 0 0 auto;
    transition: color 0.14s, transform 0.14s;
}
.search-suggestion-item.suggestion-hovered .suggestion-arrow,
.search-suggestion-item:hover .suggestion-arrow {
    color: {{$empresa->colorUno}};
    transform: translateX(3px);
}

/* Campo de búsqueda con foco premium */
#search:focus {
    border-color: {{$empresa->colorUno}} !important;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.06) !important;
}

/* ══════════════════════════════════════════════════════════════════════════
   HEADER – Micro-animaciones: Carrito y Perfil
   ══════════════════════════════════════════════════════════════════════════ */

/* Carrito */
header .bi-cart3 {
    display: inline-block;
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.15s ease;
}
header a:hover .bi-cart3 {
    transform: scale(1.22) rotate(-8deg);
}

/* Perfil */
header .bi-person,
header .bi-person-circle {
    display: inline-block;
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
}
header a:hover .bi-person,
header a:hover .bi-person-circle {
    transform: scale(1.18);
}

/* Botón de búsqueda */
header .bx-search-alt {
    display: inline-block;
    transition: transform 0.2s ease;
}
header button:hover .bx-search-alt {
    transform: scale(1.15);
}

/* Badge carrito – pulse cuando se agrega algo */
@keyframes cartBadgePop {
    0%   { transform: translate(-50%, -50%) scale(1); }
    40%  { transform: translate(-50%, -50%) scale(1.5); }
    100% { transform: translate(-50%, -50%) scale(1); }
}
.badge.rounded-pill {
    animation: cartBadgePop 0.5s ease;
}

/* ══════════════════════════════════════════════════════════════════════════
   NAVBAR – Hover en links de navegación principal
   ══════════════════════════════════════════════════════════════════════════ */
nav.navbar .nav-link {
    position: relative;
    transition: color 0.2s ease;
}
nav.navbar .nav-link::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: {{$empresa->colorDos}};
    border-radius: 2px;
    transform: translateX(-50%);
    transition: width 0.22s ease;
}
nav.navbar .nav-link:hover::after {
    width: 60%;
}
/* Quitar subrayado animado del toggle de collapse que usa dropdown */
nav.navbar .nav-link.dropdown-toggle::after {
    display: none;
}

/* WhatsApp button hover */
div[style*="position:fixed"] a img {
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.2s ease;
}
div[style*="position:fixed"] a:hover img {
    transform: scale(1.15);
    filter: drop-shadow(0 4px 10px rgba(37, 211, 102, 0.5));
}

