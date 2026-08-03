@props([
    'storage' => null,
    'colsmall' => 6,
    'colmedio' => 3,
    'empres' => null,
    'cantCards' => 16,
    'filtros' => null,
    'dolar' => null,
])
<div class="card_producto_medio">
  <div class="row">
    <div class="col-md-2">
      <x-partials.filtro-productos :filtros="$filtros" :colmedio="$colmedio" :empres="$empres" :totalProducts="$storage ? (method_exists($storage, 'total') ? $storage->total() : $storage->count()) : 0" :colsmall="$colsmall"/>
    </div>
    <div class="col-md-10">
      <div class="container" style="position: relative">
        @if(!$storage || (method_exists($storage, 'total') ? $storage->total() : $storage->count()) < 1)
        <div class="row d-none d-sm-block">
          <div class="col-6 col-md-3 ">
              <h6 class=" pt-4 fw-light" id="recountMedio">{{$storage ? (method_exists($storage, 'total') ? $storage->total() : $storage->count()) : 0}} productos encontrados</h6>
          </div>
        </div>
        <div class="row" style="height:20vh">
        </div>
        <x-aviso_no_encontrado :nameProduct="''" />
        <div class="row" style="height:20vh">
        </div>
        @else
        <div class="row h-100 w-100 justify-content-center" id="loader-list-products" style="background-color:rgba(255, 255, 255, 0.699);position: absolute;z-index:800;margin-left:-1.5rem;display:none">
          <div class="spinner-border" style="width: 3rem; height: 3rem;margin-top:25vh;margin-bottom:25vh;position: sticky; top: 50%;" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div class="page-content" id="products-medio" >
          <x-partials.lista-productos :productos="$storage" :colmedio="$colmedio" :empres="$empres" :colsmall="$colsmall"/>
        </div>
        @endif
  </div>
    </div>
  </div>
    
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var buttonsFilter = document.querySelectorAll('.submit-filtros');
    
    function loadProducts(url) {
        let loader = document.getElementById('loader-list-products');
        const params = new URLSearchParams({
            colmedio: {{$colmedio ?? 3}},
            colsmall: {{$colsmall ?? 6}}
        });
        const fullUrl = `${url}&${params.toString()}`;
        loader.style.display = 'flex';
        buttonsFilter.forEach(function(x){
          x.disabled = true;
        });

        fetch(fullUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.querySelector('.page-content').innerHTML = data.html;
                document.getElementById('recount').textContent = data.paginas;
                
                // Hide local loader and sidebar overlay
                loader.style.display = 'none';
                const overlay = document.getElementById('filtro-loading-overlay');
                if (overlay) overlay.style.display = 'none';
                
                buttonsFilter.forEach(function(x){
                  x.disabled = false;
                });
                
                assignPaginationLinks();
            })
            .catch(error => console.error('Error al cargar los productos:', error));
    }

    function assignPaginationLinks() {
        document.querySelectorAll('#pagination a.page-link').forEach(link => {
            link.addEventListener('contextmenu', (event) => {
                event.preventDefault(); // Evita el menú contextual
            });

            link.addEventListener('dragstart', function(event) { 
              event.preventDefault(); 
            });

            link.addEventListener('click', function(event) {
                event.preventDefault();
                
                const form = document.getElementById('form-filtro-products');
                let params = '';
                if (form) {
                    const formData = new FormData(form);
                    params = new URLSearchParams(formData).toString();
                }
                
                let url = this.getAttribute('href');
                if (params) {
                    url += url.includes('?') ? `&${params}` : `?${params}`;
                }
                
                if (url !== 'javascript:void(0)') {
                    window.history.pushState({path: url}, '', url);
                    loadProducts(url);
                }
            });
        });
    }

    function filterSubmit() {
        const form = document.getElementById('form-filtro-products');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        
        const baseUrl = form.action;
        const fullUrl = `${baseUrl}?${params}`;

        window.history.pushState({path: fullUrl}, '', fullUrl);
        loadProducts(fullUrl);
    }

    // Manejar el botón de retroceso/adelante del navegador
    window.addEventListener('popstate', function(event) {
        const url = location.href;
        loadProducts(url);
    });

    buttonsFilter.forEach(function(x){
      x.addEventListener('change',filterSubmit);
    });

    assignPaginationLinks();
});
</script>