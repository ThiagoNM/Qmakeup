@extends('layouts.principal')

@section('content')
  <div class="container__global--brands">

    <!-- HERRAMIENTA DE BUSQUEDA -->
    @if(strpos(Request::url(), '/filtro/'))
    @else
      <form class="container__search container__search--category">
            <input class="form-control input__search" type="search" placeholder="Search" id="search" name="search" aria-label="Search">
            <button class="btn boton--search" type="submit"><i class="bi bi-search"></i></button>
      </form>
    @endif

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--brands" >
        <div class="container__alphabet">
        <form action="{{route('marcas')}}">
          @foreach ($marcas as $marca)
            <p><a class="alphabet__brand" id="filtro" href="{{ route('find', $marca->id)}}">{{ $marca->marca}}</a></p>
          @endforeach
        </form>
      </div>

      <!-- PRODUCTOS -->
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

    </div> 

    <div class="container__paginador">
      {{ $productos->appends(request()->input())->links()}}
    </div>    
  </div>   

@endsection
