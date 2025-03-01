/* resources/views/js/dynamic-scripts.blade.php */

function mostrarCategories(id) {
    let divListCategories = document.getElementById('div-categories-' + id);
    divListCategories.style.display = 'block';
}

function ocultarCategories(id) {
    let divListCategories = document.getElementById('div-categories-' + id);
    divListCategories.style.display = 'none';
}

function verificarMouseCategories(id) {
    setTimeout(function() {
        let divListCategories = document.getElementById('div-categories-' + id);
        if (!divListCategories.matches(':hover')) {
            divListCategories.style.display = 'none';
        }
    }, 1);
}

window.addEventListener('load', function() {
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        window.scrollTo(0, scrollPosition);
    }
    
    document.getElementById('loader').style.display = 'none';
    document.querySelector('.content').style.display = 'block';
});

document.addEventListener('click', function(event) {
    var multiCollapseMarcas = document.getElementById('multiCollapseMarcas');
    var button = document.querySelector('[data-bs-toggle="collapse"]');
    var isClickInside = multiCollapseMarcas.contains(event.target) || button.contains(event.target);

    if (!isClickInside) {
        var bsCollapse = bootstrap.Collapse.getInstance(multiCollapseMarcas);
        if (bsCollapse) {
            bsCollapse.hide();
        }
    }
});

document.addEventListener('click', function(event) {
    var collapseCategoryOffCanva = document.getElementById('collapseCategoryOffCanva');
    var button = document.querySelector('[data-bs-toggle="collapse"]');
    var isClickInside = multiCollapseMarcas.contains(event.target) || button.contains(event.target);

    if (!isClickInside) {
        var bsCollapse = bootstrap.Collapse.getInstance(collapseCategoryOffCanva);
        if (bsCollapse) {
            bsCollapse.hide();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    let searchInput = document.getElementById('search');
    let suggestions = document.getElementById('suggestions');

    searchInput.addEventListener('input', function() {
        let query = this.value;

        if (query.length > 2) { // Comenzar la búsqueda después de 3 caracteres
            let xhr = new XMLHttpRequest();
            xhr.open('GET', `/buscar/search?query=${query}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let data = JSON.parse(xhr.responseText);
                    suggestions.innerHTML = '';

                    data.forEach(item => {
                        let li = document.createElement('li');
                        let row = document.createElement('div');
                        let colImg = document.createElement('div');
                        let colLink = document.createElement('div');
                        let a = document.createElement('h6');
                        let br = document.createElement('br');
                        let b = document.createElement('p');
                        let img = document.createElement('img');
                        
                        a.textContent = `${item.nombreProducto} (${item.partNumber})`;
                        b.innerHTML = '<span><strong class="text-danger">Precio</strong>: ' + ((item.precioTotalDolar < 1) ? 'Consultar' : '$' + item.precioTotalDolar) + ' <em class="text-secondary">' + ((item.precioTotalSol < 1) ? '' : 'S/.' + item.precioTotalSol) +'</em></span>';                        
                        if (item.imageUrls.length > 0) {
                            img.src = item.imageUrls[0]; // Usa la primera imagen del array
                            img.classList.add('me-0');
                            img.alt = item.nombreProducto;
                            img.style.width = '100%'; // Ajusta el tamaño según tu diseño
                            img.style.height = 'auto'; // Mantiene la proporción de la imagen
                        }
                        
                        colLink.appendChild(a);
                        colLink.appendChild(b);
                        colLink.classList.add('col-md-11');
                        colLink.classList.add('col-10');
                        //colLink.classList.add('d-flex');
                        colLink.classList.add('align-items-center','pt-2');
                        
                        colImg.appendChild(img);
                        colImg.classList.add('col-md-1');
                        colImg.classList.add('col-2','pe-0','ps-0');
                        
                        row.appendChild(colImg);
                        row.appendChild(colLink);
                        row.classList.add('row');
                        
                        li.appendChild(row);
                        li.classList.add('list-group-item','listener-hover','pt-0','pb-0');
                        li.style.cursor = "pointer";
                        
                        

                        li.addEventListener('click', function() {
                            searchInput.value = this.textContent;
                            suggestions.innerHTML = ''; // Limpiar sugerencias después de seleccionar una
                            window.location.href = `/producto/${(item.slugProducto)}`;
                        });

                        suggestions.appendChild(li);
                    });

                    // Mostrar las sugerencias
                    suggestions.style.display = 'block';
                }
            };
            xhr.send();
        } else {
            suggestions.innerHTML = ''; // Limpiar si hay menos de 3 caracteres
            suggestions.style.display = 'none'; // Ocultar sugerencias
        }
    });

    searchInput.addEventListener('focus', function() {
        this.select();
    });

    // Manejar el clic fuera del campo de búsqueda
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !suggestions.contains(event.target)) {
            suggestions.innerHTML = ''; // Limpiar sugerencias
            suggestions.style.display = 'none'; // Ocultar sugerencias
        }
    });

    // Mostrar sugerencias cuando el campo de búsqueda tenga foco
    searchInput.addEventListener('focus', function() {
        if (this.value.length > 2) {
            suggestions.style.display = 'block';
        }
    });
});
