<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QmakeUp</title>
    <!-- Link para boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link para iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Link para los iconos de la valoracion -->
    <!-- Link para el style -->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">


</head>
<body>
  <!-- HEADER -->
  <header>
    <nav class="navbar navbar-expand-lg navbar--barra">
            <div class="container-fluid">
              <img class="navbar-brand navbar--logo" src="{{ asset('imagenes/Logo_projecto_final.PNG') }}" alt="">
              <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active boton__nav" aria-current="page" href="{{ route('top') }}">Inicio</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton__nav" href="{{ route('marcas') }}">Marcas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton__nav" href="{{ route('categorias') }}">Categorias</a>
                  </li>
                </ul>

                @if(Auth::user()!=null)
                  <a class="icono icono--navbar" type="button" href="{{ route('perfil') }}"><i class="bi bi-person"></i></a>
                  <form method="POST" action="{{ route('logout') }}" class="form--navbar">
                        @csrf

                        <a class="icono icono--navbar" type="button" :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="bi bi-door-open"></i>
                        </a>
                    </form>
                @else
                  <a class="nav-link boton__nav boton__nav--secondary" href="{{ route('login') }}">Iniciar sesión</a>
                  <a class="nav-link boton__nav boton__nav--secondary" href="{{ route('register') }}">Registrase</a>
                @endif

              </div>
            </div>
          </nav>
  </header>
  <div class="principal">
    @include('flash')

    @yield('content')
  </div>

  <footer>
      <label class="labelFooter ">555 55 55 55</label>
      <a class="icono icono--footer" type="button" href="https://www.instagram.com/"><i class="bi bi-instagram"></i></a>
      <label class="text labelFooter ">qmakeup@gmail.com</label>
  </footer>

  <!-- Script para funciones -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/ae5bc7e69c.js" crossorigin="anonymous"></script>
</body>
</html>
