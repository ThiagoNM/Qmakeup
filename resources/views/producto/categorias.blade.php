@extends('layouts.principal')

@section('content')

    <!-- BARRA DE CATEGORIAS -->
    <div class="navbar__categories">

      @foreach($categorias as $categoria)
        
        <div class="dropdown">
          <button type="button" class="dropdown-toggle boton--nav" data-bs-toggle="dropdown">
            {{ucfirst($categoria->nombre)}}
          </button>
          <ul class="dropdown-menu">
            @php 
              $subcategorias = $categorias->where('id_categoria', $categoria->id)
            @endphp
            
            @foreach($subcategorias as $subcategoria)
              <li><button class="dropdown-item" onClick="{{$productos = $productos->where('id_subcategoria', $subcategoria->id)}}" >{{$subcategoria->nombre}}</button></li>
            @endforeach

          </ul>
        </div>

      @endforeach

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
    <div class="container__king container__king--category" >

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
@endsection