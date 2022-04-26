<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Link para boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link para iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
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
                    <a class="nav-link active boton-nav" aria-current="page" href="#">Inicio</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton-nav" href="#">Marcas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton-nav" href="#">Categorias</a>
                  </li>
                </ul>
                <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-heart"></i></a>
                <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-person"></i></a>    
              </div>
            </div>
          </nav>
    </header>

    <!-- BIENVENIDA -->
    <div class="container__welcome">
        <video src="{{ asset('videos/How to Light a MAKEUP ad.mp4') }}" autoplay muted loop></video>
        <div class="centered">
            <label class="title">HOLA, BIENVENIDO!</label>
            <label class="text">Busca tu producto de maquillaje clicando</label>
            <button class="boton-welcome">AQUÍ</button>
        </div>
    </div>
    <br>
    <!-- HERRAMIENTA DE BUSQUEDA -->
    <!-- <div class="input-group mb-1" style="width: 300px; margin-left: 4%;">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn boton-search my-2 my-sm-0" type="submit"><i class="bi bi-search"></i>
            </button>
        </div>
    </div> -->

    <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container--king">

      <!-- PRODUCTOS TOP -->
      <label class="title title--container">Productos mejor valorados</label>
      <div class="container__top">
        <div class="container__product">
          <img class="imgP" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">Nombre</label>
          <label for="" class="text--product">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facere </label>
          <div class="container--starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">xx</label>
          </div>
        </div>
      </div>

      <!-- INFORMACIÓN DE LA EMPRESA -->
      <label class="title title--container">Sobre nosotros</label>
      <div class="container__top">
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Est ipsam similique quo explicabo nam iure delectus accusantium dolorum consequuntur, nobis possimus vero! Laborum soluta similique adipisci, blanditiis est illum quasi!</p>
      </div>

      <p class="title title--question">¿De donde sacamos los productos?</p>
      <div class="container__top">
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Est ipsam similique quo explicabo nam iure delectus accusantium dolorum consequuntur, nobis possimus vero! Laborum soluta similique adipisci, blanditiis est illum quasi!</p>
      </div>
      <div class="continer--business">
        <img src="{{ asset('imagenes/logo-DRUNI.png') }}" alt="">
        <img src="{{ asset('imagenes/logo-primor.jpg') }}" alt="">
        <img src="{{ asset('imagenes/logo-lookfantastic.png') }}" alt="">
      </div>
    </div>



    <footer>
        <label class="labelFooter ">555 55 55 55</label>
        <a class="icono icono--footer" type="button" href="#"><i class="bi bi-instagram"></i></a>
        <label class="text labelFooter ">qmakeup@gmail.com</label>
    </footer>

    <!-- Script para funciones -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
