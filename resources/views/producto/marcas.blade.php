@extends('layouts.principal')

@section('content')

    <!-- HERRAMIENTA DE BUSQUEDA -->
    <div class="input-group margin-top">
<<<<<<< HEAD
          <form>
        <input class="form-control" type="search" placeholder="Search" id="search" name="search" aria-label="Search">
=======
        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
>>>>>>> web-screpping
        <div class="input-group-append">
            <button class="btn boton--search " type="submit"><i class="bi bi-search"></i>
            </button>
        </div>
<<<<<<< HEAD
    </form>  
=======
>>>>>>> web-screpping
    </div>

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands" >
        <div class="container__alphabet">
        @foreach ($productos as $producto)
          <p style="color:white;">{{ $producto->marca}}</p>
        @endforeach
  
      </div>

      <!-- PRODUCTOS -->
      <div class="container container--brands">
        @foreach ($productos as $producto)

        <div class="container__product">
          <img class="img__product" src="{{ asset('imagenes/producto.jpg') }}" alt="">
          <label for="" class="title--product">{{ $producto->nombre}}</label>
          <p for="" class="text--product">{{ $producto->descripcion}}</p>
<<<<<<< HEAD
          <p for="" class="text--product">{{ $producto->precio}}€</p>
=======
>>>>>>> web-screpping
          <div class="container__starsProduct">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label for="">{{ $producto->valoracion}}</label>
          </div>
        </div>
<<<<<<< HEAD
        @endforeach
      </div> 
          {{ $productos->appends(request()->input())->links()}}
=======

        @endforeach
      </div> 

>>>>>>> web-screpping
    </div> 
@endsection