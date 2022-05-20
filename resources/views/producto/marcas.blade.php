@extends('layouts.principal')

@section('content')

  <div class="container__global--brands">

    <!-- HERRAMIENTA DE BUSQUEDA -->
    <form class="container__search">
          <input class="form-control input__search" type="search" placeholder="Search" id="search" name="search" aria-label="Search">
          <button class="btn boton--search" type="submit"><i class="bi bi-search"></i></button>
    </form>


      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands" >
      <div class="container__alphabet">

        @foreach ($marcas as $marca)
          <p style="color:white;">{{ $marca->marca}}</p>
        @endforeach

      </div>

      <!-- PRODUCTOS -->

      <div class="container container--brands">
        @foreach ($productos as $producto)

        <div class="container__product">
          <a class="nada" href="{{ route('productoShow.show', $producto, $producto)}}">
          <img class="img__product" src="{{ $producto->imagen }}" alt="">
          <label for="" class="title--product">{{ $producto->nombre}}</label>
          <p for="" class="text--product">{{ $producto->descripcion}}</p>
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">{{ $producto->valoracion}}</label>
          </div>
          </a>
        </div>

        @endforeach
      </div>

    </div>
    <div class="container__paginador">
      {{ $productos->appends(request()->input())->links()}}
      </div>

  </div>

@endsection
