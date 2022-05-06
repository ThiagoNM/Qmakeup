@extends('layouts.principal')

@section('content')

    <!-- HERRAMIENTA DE BUSQUEDA -->
    <div class="input-group margin-top">
        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn boton--search " type="submit"><i class="bi bi-search"></i>
            </button>
        </div>
    </div>

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands">

      <div class="container__alphabet">

        <p>
          <a class="title--alphabet" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Link with href
          </a>
        </p>

        <div class="collapse" id="collapseExample">
  
          <div class="card container__alphabet--card">
            <label class="text--alphabet">hola</label>
  
          </div>
        </div>
  
      </div>


      <!-- PRODUCTOS -->
      <div class="container container--brands">

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
  
@endsection
