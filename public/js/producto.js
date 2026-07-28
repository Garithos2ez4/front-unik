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
                    alert(data.message);
                    window.location.reload(); // To update the cart icon count on header
                } else {
                    alert('Hubo un error al agregar el producto.');
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
