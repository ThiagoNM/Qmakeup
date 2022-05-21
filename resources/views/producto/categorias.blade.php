@extends('layouts.principal')

@section('content')

    <!-- BARRA DE CATEGORIAS -->
    <div class="navbar__categories">

      @foreach($categorias as $categoria)

        <div class="dropdown">
          <button type="button" class="dropdown-toggle boton__nav" data-bs-toggle="dropdown">
            {{ucfirst($categoria->nombre)}}
          </button>
          <ul class="dropdown-menu">
              
            @php($subcategorias = \App\Models\Subcategoria::where('id_categoria', '=' ,$categoria->id)->get()) 
          
            @foreach($subcategorias as $subcategoria)
              <li><a class="btn dropdown-item" href="{{route('subcate', $subcategoria->id)}}" onClick="{{$productos->where('id_subcategoria', $subcategoria->id)}}">{{$subcategoria->nombre}}</a></li>
            @endforeach
            
          </ul>
        </div>
      @endforeach

    </div>


    <!-- HERRAMIENTA DE BUSQUEDA -->
    @if(strpos(Request::url(), '/subcate/'))
    @else
      <form class="container__search container__search--category">
            <input class="form-control input__search" type="search" placeholder="Search" id="find" name="find" name="search" aria-label="Search">
            <button class="btn boton--search" type="submit"><i class="bi bi-search"></i></button>
      </form>
    @endif

      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--category" >

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
      {{ $productos->appends(request()->input())->links()}}
    </div> 
@endsection
