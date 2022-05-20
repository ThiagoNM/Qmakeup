@extends('layouts.principal')

@section('content')
  <div class="container__global--brands">

    <!-- HERRAMIENTA DE BUSQUEDA -->
    <form>
      <div class="input-group margin-top">
        <input class="form-control" type="search" placeholder="Search" id="search" name="search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn boton--search " type="submit"><i class="bi bi-search"></i>
            </button>
        </div>
      </div>
    </form>  

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands" >
        <div class="container__alphabet">
        <form action="{{route('marcas')}}">
          @foreach ($marcas as $marca)
            <a class="alphabet__brand" id="filtro" href="{{ route('find', $marca->id)}}">{{ $marca->marca}}</a>
          @endforeach
        </form>
      </div>

      <!-- PRODUCTOS -->
      <div class="container__king container__king--category" >
      <div class="container container--brands">
        @php($cont = 0)
        @foreach ($productos as $producto)
        <div class="container__product">
          <a class="link__product" href="{{ route('productoShow.show', $producto, $producto)}}">
          <img class="img__product" src="{{ $producto->imagen }}" alt="">
          <label for="" class="title--product">{{ $producto->nombre}}</label>
          <p for="" class="text--product">{{ $producto->descripcion}}</p>
          <div class="container__starsProduct">
            @php($ratenum = $producto->valoracion_media)
          <div class="rating">
            @for($i=1; $i<= $ratenum; $i++)
              <i class="fa fa-star checked"></i>
            @endfor
            @for($j = $ratenum+1; $j <=5; $j++)
              <i class="fa fa-star"></i>
            @endfor
          </div>
          </div>
          </a>
        </div>
        @php($cont++)
        @endforeach
      </div> 
          {{ $productos->appends(request()->input())->links()}}
    </div> 
@endsection
