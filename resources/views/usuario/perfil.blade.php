@extends('layouts.app')

@section('content')

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
      <link rel="stylesheet" href="{{ asset('css/home.css') }}">

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
                    <a class="nav-link active boton--nav" aria-current="page" href="#">Inicio</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton--nav" href="#">Marcas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link boton--nav" href="#">Categorias</a>
                  </li>
                </ul>
                <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-heart"></i></a>
                <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-person"></i></a>    
              </div>
            </div>
          </nav>
    </header>

    <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king">

      @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
      @endif

      <form class="container__form">
        <p class="title--container">Datos del usuario</p>
        <div class="form-group">
          <label for="Username">Username</label>
          <input type="text" class="form-control" id="Username" placeholder="{{$usuario->username}}">
        </div>
        <div class="form-group">
          <label for="Email">Email address</label>
          <input type="email" class="form-control" id="Email" placeholder="{{$usuario->correo}}">
        </div>
        
        <button type="submit" class="boton--form">Actualizar cambios</button>
      </form>

      <!-- PRODUCTOS TOP -->
      <label class="title title--container">Lista de deseos</label>
      <div class="container container--top">

        <div class="container__product">
          <img class="img__product" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">Nombre</label>
          <label for="" class="text--product">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facere </label>
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">xx</label>
          </div>
        </div>

        <div class="container__product">
          <img class="img__product" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">Nombre</label>
          <label for="" class="text--product">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facere </label>
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">xx</label>
          </div>
        </div>

        <div class="container__product">
          <img class="img__product" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">Nombre</label>
          <label for="" class="text--product">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Facere </label>
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">xx</label>
          </div>
        </div>

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
@endsection