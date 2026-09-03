{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('ofertas') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('novedades') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('liquidacion') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($productos as $producto)
    <url>
        <loc>{{ route('producto', $producto->slugProducto) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    @foreach ($categorias as $categoria)
    <url>
        <loc>{{ route('categoria', [$categoria->slugCategoria, 'todos']) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach ($marcas as $marca)
    <url>
        <loc>{{ route('marca', $marca->slugMarca ?? $marca->idMarca) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
