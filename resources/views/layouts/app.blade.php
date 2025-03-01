<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="En {{$empresa->nombreComercial}}, ofrecemos una amplia gama de productos tecnológicos como monitores, laptops, componentes y accesorios. Encuentra lo que necesitas para estar a la vanguardia en el mundo digital.">
    <title>@yield('title', $empresa->nombreComercial)</title>
    <meta property="og:title" content="@yield('og_title', $empresa->nombreComercial)">
    <meta property="og:description" content="En {{$empresa->nombreComercial}}, ofrecemos una amplia gama de productos tecnológicos como monitores, laptops, componentes y accesorios. Encuentra lo que necesitas para estar a la vanguardia en el mundo digital.">
    <meta property="og:image" content="@yield('og_image', 'https://www.tusitio.com/imagen.jpg')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="@yield('og_site_name', $empresa->nombreComercial)">
    
    <link rel="icon" href="{{asset('storage/'.$empresa->icon)}}" type="image/webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ route('css.dynamic-styles') }}">
    <script src="{{ route('js.header-scripts') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="loader">
        <div class="spinner-border text-empresa-uno" style="width: 3rem; height: 3rem;" role="status" >
        </div>
    </div>
    <div class="wrapper">
    <header class="mb-2">
        <form action="{{ route('buscar') }}" method="GET" >
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-4 text-start d-block d-md-none ">
                    <a data-bs-toggle="offcanvas" href="#offcanvasnav" role="button" aria-controls="offcanvasnav" style="color:{{$empresa->colorUno}}">
                        <i class="bi bi-list" style="font-size: 3rem"></i>
                    </a>
                </div>
              <div class="col-8 col-md-2 text-end">
                <a href="{{$empresa->linkPaginaWeb}}"><img src="{{asset('storage/'.$empresa->logo)}}" alt="{{$empresa->nombreComercial}}" width="100" height="100"></a>
              </div>
              <div class="col-12 col-md-9 align-middle">
                  <div class="input-group align-middle">
                      <div class="input-group-text bg-empresa-uno text-white"><button type="submit" style="border: none; background-color: transparent;"><i class='bx bx-search-alt bx-md text-empresa-tres'></button></i></div>
                            <input type="text" style="position:relative" name="header" id="search" class="form-control" placeholder="Busca un producto modelo o part Number!!" aria-label="Last name" value="{{request('header')}}">
                            <ul class="list-group" id="suggestions" style="position:absolute;top: 100%; left: 0; width: 100%;z-index:1100"></ul>
                    </div>                   
              </div>
              <div class="col-md-1 text-center d-none d-md-block">
                  <h6>{{$tipoCambio}}</h6>
                  <h6>PEN</h6>
              </div>
            </div>
        </div>
        </form>
    </header>
    <nav>
        <div class="offcanvas offcanvas-start w-75" tabindex="-1" id="offcanvasnav" aria-labelledby="offcanvasnav1" >
          <div class="offcanvas-header d-flex text-center" style="background-color:{{$empresa->colorUno}}">
            <h5 class="offcanvas-title text-center" style="color:{{$empresa->colorTres}}" id="offcanvasnav">MENÚ <span class="text-white-50 fs-6">PEN {{$tipoCambio}}</span></h5>
            <button type="button " class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body" style="background-color:{{$empresa->colorTres}}">
            <div >
                <ul class="list-group list-group-flush" >
                  <li class="list-group-item fw-bold" style="background-color:{{$empresa->colorTres}}"><a href="{{$empresa->linkPaginaWeb}}" class="text-decoration-none" style="color:{{$empresa->colorUno}}">Inicio</a></li>
                  <li class="list-group-item" style="background-color:{{$empresa->colorTres}}">
                    <a class="text-decoration-none fw-bold" style="color:{{$empresa->colorUno}}" data-bs-toggle="collapse" href="#collapseCategoryOffCanva" role="button" aria-expanded="false" aria-controls="collapseCategoryOffCanva">Categorias</a>
                    <div class="row">
                      <div class="col">
                        <div class="collapse multi-collapse" id="collapseCategoryOffCanva">
                          <ul class="list-group list-group-flush pt-1">
                               @foreach($categorias as $categoria)
                                <li class="list-group-item" >
                                    <a class="text-decoration-none" style="color:{{$empresa->colorUno}}" href="{{ route('categoria', [$categoria->slugCategoria, $categoria->GrupoProducto->first()->slugGrupo]) }}">{{ $categoria->nombreCategoria }}</a>
                                </li>
                                @endforeach
                          </ul>
                        </div>
                      </div>
                  </li>
                  <li class="list-group-item " style="background-color:{{$empresa->colorTres}}">
                      <a class="fw-bold" style="color:{{$empresa->colorDos}}" href="{{route('ofertas')}}">Ofertas</a>
                  </li>
                  <li class="list-group-item" style="background-color:{{$empresa->colorTres}}">
                    <a class="text-decoration-none fw-bold" style="color:{{$empresa->colorUno}}" data-bs-toggle="collapse" href="#collapseMarcasOffCanva" role="button" aria-expanded="false" aria-controls="collapseMarcasOffCanva">Marcas</a>
                    <div class="row">
                      <div class="col">
                        <div class="collapse multi-collapse" id="collapseMarcasOffCanva">
                            <div class="row">
                               @foreach($marcas as $marca)
                                   <div class="col-6 mb-1">
                                        <a class="text-decoration-none" style="color:{{$empresa->colorUno}}" href="{{route('marca', [$marca->slugMarca])}}">{{ $marca->nombreMarca }}</a>
                                   </div>
                                @endforeach
                            </div>
                        </div>
                      </div>
                  </li>
                  <li class="list-group-item fw-bold" style="background-color:{{$empresa->colorTres}}"><a href="{{route('mediodepago')}}" class="text-decoration-none" style="color:{{$empresa->colorUno}}">Medios de pago</a></li>
                  <li class="list-group-item fw-bold" style="background-color:{{$empresa->colorTres}}"><a href="{{route('envios')}}" class="text-decoration-none" style="color:{{$empresa->colorUno}}">Envios</a></li>
                  <li class="list-group-item fw-bold" style="background-color:{{$empresa->colorTres}}"><a href="" class="text-decoration-none" style="color:{{$empresa->colorUno}}">Blogs</a></li>
                  <li class="list-group-item fw-bold" style="background-color:{{$empresa->colorTres}}"><a href="" class="text-decoration-none" style="color:{{$empresa->colorUno}}">Reviews</a></li>
                </ul>
            </div>
            
          </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark fs-5 fw-bold justify-content-center d-none d-sm-block" style="background-color:{{$empresa->colorUno}}">
          <div class="container-md">
            <a class="navbar-brand" href="{{$empresa->linkPaginaWeb}}" >Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-white" id="navbarNavDarkDropdown" >
              <ul class="navbar-nav " >
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" type="button" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                      <i class='bx bx-menu'></i>
                    Categorias
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     @foreach($categorias as $categoria)
                    <li style="position:relative" onmouseover="mostrarCategories({{$categoria->idCategoria}})" onmouseout="verificarMouseCategories({{$categoria->idCategoria}})">
                        <a class="dropdown-item" href="#">{{ $categoria->nombreCategoria }}</a>
                        <div style="position:absolute;top:0;left:100%;display:none" id="div-categories-{{$categoria->idCategoria}}">
                            <ul class="list-group">
                                @foreach($categoria->GrupoProducto as $grupito)
                                <li class="list-group-item ps-0 pe-0 pt-0 pb-0"><a class="dropdown-item" href="{{route('categoria', [$categoria->slugCategoria, $grupito->slugGrupo])}}">{{ $grupito->nombreGrupo }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endforeach
                  </ul>
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link text-decoration-underline"  style="color:{{$empresa->colorDos}}" href="{{route('ofertas')}}">Ofertas</a> 
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#multiCollapseMarcas" role="button" aria-expanded="false" aria-controls="multiCollapseMarcas">Marcas</a>
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link"  href="{{route('mediodepago')}}">Medios de pago</a> 
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link" href="{{route('envios')}}">Envíos</a> 
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link"   href="#">Blog</a> 
                </li>
                <li class="nav-item" aria-labelledby="navbarDropdownMenuLink"> 
                    <a class="nav-link"  href="#">Reviews</a> 
                </li>
              </ul>
            </div>
          </div>
          
        </nav>
        <div class="container">
            <div class="position-relative">
                <div class="collapse multi-collapse position-absolute" id="multiCollapseMarcas" style="transition: height 0s ease">
                    <div class="card" style="z-index:1000" >
                        <div class="card-body"  >
                            <div class="row ">
                                @foreach($marcas as $marca)
                                    <div class="col-6 col-md-2">
                                        <a href="{{route('marca', [$marca->slugMarca])}}" class="text-decoration-none fs-6 fw-normal enlace-hover" style="color:{{$empresa->colorUno}}">{{ $marca->nombreMarca }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
     <div style="position:fixed;right:2%;bottom:10%;z-index:3000">
         <div style="width:60px">
                <a href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace}}?text=Hola,%20estoy%20interesado%20en%20adquirir%20productos%20de%20su%20pagina%20web" target="_blank" rel="noopener noreferrer">
                <img src="{{asset('storage/redsocial/whatsapplogo.webp')}}" alt="" width="60" height="60">
            </a>
        </div>
    </div>
    <main class="content">
        @yield('content')
    </main>

    <footer class="text-white mt-auto" style="background-color:{{$empresa->colorUno}}">
        <div class="container">
            <br>
            <div class="row align-items-top text-start">
                <div class="d-none d-md-block col-sm-12 col-md-3 text-center">
                    <img src="{{asset('storage/'.$empresa->icon)}}" alt="" width="90%" height="90%" loading="lazy">
                </div>
                <div class="col-12 col-sm-12 col-md-3">
                    <h3>Conocenos</h3>
                    <br>
                    <ul class="list-unstyled ">
                        <li class="mb-4"><i class='bx bx-user'></i>  <a href="{{route('nosotros')}}" class="text-decoration-none text-white">Sobre nosotros</a></li>
                        <li class="mb-4"><i class='bx bx-envelope'></i>  <a href="mailto:{{$empresa->correoEmpresa}}" class="text-decoration-none text-white">{{$empresa->correoEmpresa}}</a></li>
                        <li class="mb-4"><i class='bx bx-phone'></i>
                            <a href="{{$empresa->EmpresaRedSocial->where('idRedSocial',5)->first()->enlace}}?text=Hola,%20estoy%20interesado%20en%20adquirir%20productos%20de%20su%20pagina%20web}}" class="text-decoration-none text-white">+51 959062011</a>
                        </li>
                        <li class="mb-4"><i class='bx bx-map'></i>  <a href="{{$empresa->ubicacionLink}}" class="text-decoration-none text-white">{{$empresa->ubicacion}}</a></li>
                    </ul>
                </div>
                <div class="col-12 col-sm-12 col-md-3">
                    <h3 >Politicas y condiciones</h3>
                    <br>
                    <ul class="list-unstyled ">
                        <li class="mb-4"><a href="{{route('privacidad')}}" class="text-decoration-none text-white">Politica de privacidad</a></li>
                        <li class="mb-4"><a href="{{route('garantia')}}" class="text-decoration-none text-white">Condiciones de garantia</a></li>
                        <li class="mb-4"><a href="{{route('mediodepago')}}" class="text-decoration-none text-white">Modalidades de pago</a></li>
                        <li class="mb-4"><a href="{{route('envios')}}" class="text-decoration-none text-white">Envios a provincia</a></li>
                        <li class="mb-4"><a href="{{route('libroreclamacion')}}" class="text-decoration-none text-white"><i class="bi bi-book-fill"></i> Libro de reclamaciones</a></li>
                    </ul>
                </div>
                <div class="col-12 col-sm-12 col-md-3">
                    <h3>Nuestras Redes</h3>
                    <br>
                    <div class="align-items-center text-start ps-3">
                        @foreach($empresa->EmpresaRedSocial as $red)
                            @if($red->idRedSocial != 5)
                            <img src="{{asset('storage/'.$red->imagen)}}" alt="{{$red->RedSocial->plataforma}}" width="25" height="25" class="rounded-3">
                            <a href="{{$red->enlace}}" target="_blank" rel="noopener noreferrer" class="text-light text-decoration-none">{{$red->RedSocial->plataforma}}</a>
                            <br> 
                            <br> 
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <br>
        <p class="text-center text-secondary">Copyright  {{ date('Y') }} &copy; {{$empresa->razonSocial}}. Todos los derechos reservados</p>
        <br>
        <p class="text-end pe-2"><small class="text-hidden">By Leonardo.M.H.</small></p>  
    </footer>
    </div>
    @stack('scripts')
</body>
</html>