document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('#carouselExampleIndicators');
    
    if (carousel) {
        carousel.addEventListener('slid.bs.carousel', function() {
            const iframes = document.querySelectorAll('.yt-frame');
            iframes.forEach(iframe => {
                const src = iframe.getAttribute('src').split('?')[0];
                iframe.setAttribute('src', src); // Detener video
            });

            // Reproducir solo el iframe visible
            const active = carousel.querySelector('.carousel-item.active iframe');
            if (active) {
                const base = active.getAttribute('src').split('?')[0];
                active.setAttribute('src', base + '?autoplay=1&rel=0');
            }
        });
    }

    const addButtons = document.querySelectorAll('.add-to-cart-btn');
    addButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            
            // Show small loading state
            const originalHTML = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Agregando...';
            this.disabled = true;

            fetch(`/carrito/add/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                this.innerHTML = originalHTML;
                this.disabled = false;
                if(data.success) {
                    // Actualizar el contador del carrito
                    let badge = document.getElementById('cart-badge-count');
                    let wrapper = document.getElementById('cart-icon-wrapper');
                    
                    if (!badge && wrapper) {
                        badge = document.createElement('span');
                        badge.id = 'cart-badge-count';
                        badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                        badge.style.fontSize = '0.6rem';
                        wrapper.appendChild(badge);
                    }
                    if (badge) {
                        badge.innerText = data.cartCount;
                    }

                    // Rellenar datos del modal
                    if(data.product) {
                        document.getElementById('modal-cart-product-img').src = data.product.image;
                        document.getElementById('modal-cart-product-name').innerText = data.product.name;
                        document.getElementById('modal-cart-product-price').innerText = data.product.price;
                        document.getElementById('modal-cart-product-qty').innerText = data.product.quantity;
                    }

                    // Mostrar el modal
                    var cartModal = new bootstrap.Modal(document.getElementById('addedToCartModal'));
                    cartModal.show();
                } else {
                    alert(data.message || 'Hubo un error al agregar el producto.');
                }
            })
            .catch(err => {
                this.innerHTML = originalHTML;
                this.disabled = false;
                console.error(err);
            });
        });
    });
});
