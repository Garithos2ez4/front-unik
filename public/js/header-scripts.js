/* resources/views/js/dynamic-scripts.blade.php */

/* ──────────────────────────────────────────────────────────────────────────
   CATEGORÍAS – Sub-menú inteligente con posicionamiento automático
   ────────────────────────────────────────────────────────────────────────── */

function mostrarCategories(id) {
    let divListCategories = document.getElementById('div-categories-' + id);

    if (divListCategories.style.display === 'block') {
        return; // Evita el parpadeo al no reposicionar continuamente si ya está visible
    }

    // Resetear posición y mostrar para poder medir
    divListCategories.style.top    = '0';
    divListCategories.style.bottom = 'auto';
    divListCategories.style.left   = '100%';
    divListCategories.style.right  = 'auto';
    divListCategories.style.display = 'block';

    // Smart positioning: detectar si el sub-menú se sale del viewport por abajo
    let rect = divListCategories.getBoundingClientRect();
    let windowHeight = window.innerHeight || document.documentElement.clientHeight;
    let windowWidth  = window.innerWidth  || document.documentElement.clientWidth;

    if (rect.bottom > windowHeight) {
        // Abrir hacia ARRIBA: anclar al bottom del item padre
        divListCategories.style.top    = 'auto';
        divListCategories.style.bottom = '0';
    }

    // Si se sale por la derecha, abrir hacia la izquierda
    rect = divListCategories.getBoundingClientRect();
    if (rect.right > windowWidth) {
        divListCategories.style.left  = 'auto';
        divListCategories.style.right = '100%';
    }
}

function ocultarCategories(id) {
    let divListCategories = document.getElementById('div-categories-' + id);
    if(divListCategories) {
        divListCategories.style.display = 'none';
    }
}

function verificarMouseCategories(id) {
    setTimeout(function() {
        let divListCategories = document.getElementById('div-categories-' + id);
        if (divListCategories) {
            let liElement = divListCategories.parentElement;
            if (!divListCategories.matches(':hover') && (!liElement || !liElement.matches(':hover'))) {
                ocultarCategories(id);
            }
        }
    }, 80);
}

/* ──────────────────────────────────────────────────────────────────────────
   CARGA DE PÁGINA
   ────────────────────────────────────────────────────────────────────────── */

window.addEventListener('load', function() {
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        window.scrollTo(0, scrollPosition);
    }

    // Ocultar loader con fade suave
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.transition = 'opacity 0.4s ease';
        loader.style.opacity = '0';
        setTimeout(function() {
            loader.style.display = 'none';
        }, 400);
    }

    const content = document.querySelector('.content');
    if (content) {
        content.style.display = 'block';
    }
});

/* ──────────────────────────────────────────────────────────────────────────
   CERRAR MENÚ MARCAS AL CLICK FUERA
   ────────────────────────────────────────────────────────────────────────── */

document.addEventListener('click', function(event) {
    var multiCollapseMarcas = document.getElementById('multiCollapseMarcas');
    if (!multiCollapseMarcas) return;

    var toggleButtons = document.querySelectorAll('[href="#multiCollapseMarcas"], [data-bs-target="#multiCollapseMarcas"]');
    var isClickInside = multiCollapseMarcas.contains(event.target);
    toggleButtons.forEach(function(btn) {
        if (btn.contains(event.target)) isClickInside = true;
    });

    if (!isClickInside) {
        var bsCollapse = bootstrap.Collapse.getInstance(multiCollapseMarcas);
        if (bsCollapse) {
            bsCollapse.hide();
        }
    }
});

/* ──────────────────────────────────────────────────────────────────────────
   BUSCADOR CON AUTOCOMPLETE
   ────────────────────────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', function() {
    let searchInput = document.getElementById('search');
    let suggestions = document.getElementById('suggestions');
    if (!searchInput || !suggestions) return;

    let debounceTimer = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        let query = this.value.trim();

        if (query.length > 2) {
            debounceTimer = setTimeout(function() {
                fetchSuggestions(query);
            }, 220);
        } else {
            clearSuggestions();
        }
    });

    function fetchSuggestions(query) {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/buscar/search?query=${encodeURIComponent(query)}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                renderSuggestions(data);
            }
        };
        xhr.send();
    }

    function renderSuggestions(data) {
        suggestions.innerHTML = '';

        if (data.length === 0) {
            clearSuggestions();
            return;
        }

        data.forEach(item => {
            let li = document.createElement('li');
            li.classList.add('search-suggestion-item');
            li.style.cursor = 'pointer';

            let imgSrc = (item.imageUrls && item.imageUrls.length > 0) ? item.imageUrls[0] : '';

            // Parsear precios correctamente aunque vengan con coma de miles ("1,616.50")
            const precioDolarNum = parseFloat(String(item.precioTotalDolar || '0').replace(/,/g, ''));
            const precioSolNum   = parseFloat(String(item.precioTotalSol   || '0').replace(/,/g, ''));
            const mostrarPrecioWeb = (item.mostrarPrecioWeb !== undefined && item.mostrarPrecioWeb !== null) ? Boolean(item.mostrarPrecioWeb) : true;

            let precio = '';
            if (mostrarPrecioWeb && precioDolarNum >= 1) {
                precio = `<span class="suggestion-price-dolar">$${item.precioTotalDolar}</span>`;
                if (precioSolNum >= 1) {
                    precio += ` <span class="suggestion-price-sol">S/.${item.precioTotalSol}</span>`;
                }
            } else {
                precio = `<span class="suggestion-price-consultar">Consultar precio</span>`;
            }


            li.innerHTML = `
                <div class="suggestion-inner">
                    <div class="suggestion-img-wrap">
                        ${imgSrc ? `<img src="${imgSrc}" alt="${item.nombreProducto}" class="suggestion-img">` : '<div class="suggestion-img-placeholder"><i class="bi bi-box-seam"></i></div>'}
                    </div>
                    <div class="suggestion-info">
                        <div class="suggestion-name">${item.nombreProducto}</div>
                        <div class="suggestion-part">${item.partNumber || ''}</div>
                        <div class="suggestion-price">${precio}</div>
                    </div>
                    <div class="suggestion-arrow"><i class="bi bi-chevron-right"></i></div>
                </div>`;

            li.addEventListener('click', function() {
                searchInput.value = item.nombreProducto;
                clearSuggestions();
                window.location.href = `/producto/${item.slugProducto}`;
            });

            li.addEventListener('mouseenter', function() { this.classList.add('suggestion-hovered'); });
            li.addEventListener('mouseleave', function() { this.classList.remove('suggestion-hovered'); });

            suggestions.appendChild(li);
        });

        suggestions.style.display = 'block';
    }

    function clearSuggestions() {
        suggestions.innerHTML = '';
        suggestions.style.display = 'none';
    }

    searchInput.addEventListener('focus', function() {
        this.select();
        if (this.value.trim().length > 2) {
            fetchSuggestions(this.value.trim());
        }
    });

    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !suggestions.contains(event.target)) {
            clearSuggestions();
        }
    });

    // Navegación con teclado en sugerencias
    searchInput.addEventListener('keydown', function(e) {
        let items = suggestions.querySelectorAll('.search-suggestion-item');
        let current = suggestions.querySelector('.suggestion-hovered');
        let idx = Array.from(items).indexOf(current);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (current) current.classList.remove('suggestion-hovered');
            let next = items[idx + 1] || items[0];
            if (next) next.classList.add('suggestion-hovered');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (current) current.classList.remove('suggestion-hovered');
            let prev = items[idx - 1] || items[items.length - 1];
            if (prev) prev.classList.add('suggestion-hovered');
        } else if (e.key === 'Enter' && current) {
            e.preventDefault();
            current.click();
        } else if (e.key === 'Escape') {
            clearSuggestions();
        }
    });
});
