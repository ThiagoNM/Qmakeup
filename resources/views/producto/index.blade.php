@extends('layouts.principal')

@section('content')

    <!-- BARRA DE CATEGORIAS -->
    <div class="navbar__categories">
      
      <div class="dropdown">
        <button type="button" class="dropdown-toggle boton--nav" data-bs-toggle="dropdown">
          Ojos
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Máscara de pestañas</a></li>
          <li><a class="dropdown-item" href="#">Delineadores de ojos</a></li>
          <li><a class="dropdown-item" href="#">Sombras de ojos</a></li>
          <li><a class="dropdown-item" href="#">Cejas</a></li>
        </ul>
      </div>

      <div class="dropdown">
        <button type="button" class="dropdown-toggle boton--nav" data-bs-toggle="dropdown">
          Rostro
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Bases de maquillaje</a></li>
          <li><a class="dropdown-item" href="#">Prebases de maquillaje</a></li>
          <li><a class="dropdown-item" href="#">Polvos de maquillaje</a></li>
          <li><a class="dropdown-item" href="#">Coloretes</a></li>
          <li><a class="dropdown-item" href="#">Correctores de maquillaje</a></li>
        </ul>
      </div>

      <div class="dropdown">
        <button type="button" class="dropdown-toggle boton--nav" data-bs-toggle="dropdown">
          Labios
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Pintalabios</a></li>
          <li><a class="dropdown-item" href="#">Gloss de labios</a></li>
          <li><a class="dropdown-item" href="#">Perfiladores de labios</a></li>
          <li><a class="dropdown-item" href="#">Voluminizadores</a></li>
          <li><a class="dropdown-item" href="#">Prebases</a></li>
        </ul>
      </div>

    </div>




    <!-- HERRAMIENTA DE BUSQUEDA -->
    <div class="input-group">
        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn boton--search " type="submit"><i class="bi bi-search"></i>
            </button>
        </div>
    </div>

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands" >

      <!-- PRODUCTOS -->
      <div class="container container--brands">
        @foreach ($productos as $producto)

        <div class="container__product">
          <img class="img__product" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">{{ $producto->nombre}}</label>
          <p for="" class="text--product">{{ $producto->descripcion}}</p>
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">{{ $producto->valoracion}}</label>
          </div>
        </div>

        @endforeach
      </div> 

    </div> 
@endsection