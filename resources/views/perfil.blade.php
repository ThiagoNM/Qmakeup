@extends('layouts.principal')

@section('content')
  <!-- CONTENEDOR PARA CENTRAR -->
  <div class="container__king">

    @if(session()->has('success'))
          <div class="alert alert-success">
              {{ session()->get('success') }}
          </div>
    @endif

    @if(Auth::user()->id_rol == 2)
    
    @else
    <form class="container__form" action="{{ route('update' )}}" method="PUT">
      @csrf
      @method('PUT')
      <p class="title--container">Datos del usuario</p>
      <div class="form-group">
        <label for="Username">Usuario</label>
        <input type="text" class="form-control" id="Username" name="name" placeholder="{{$user->name}}">
      </div>
      <p class="text--form"><button type="submit" class="boton boton--form" >Actualizar nombre</button></p>
      <p class="text--form"><a class="text--link" href="{{route('ea')}}">Cambiar contraseña</a></p>
    </form>
    @endif

    @if( Auth::user()->id_rol == 2)
    <p class="text--attention">  <i class="bi bi-exclamation-triangle-fill"></i> Tenga cuidado con los botones <i class="bi bi-exclamation-triangle-fill"></i></p>
    <div class="container__king--admin">
      <div class="container__admin">
        <p class="title__admin">TIENDA DRUNI</p>
        <form method="GET" action="{{ route('tiendaDruni') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir tienda</label>
            <button type="submit" class="boton boton--admin">Añadir</button>
          </div>
        </form>

        <form method="GET" action="{{ route('categoriasDruni') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir categorias y subcategorias</label>
            <button type="submit" class="boton boton--admin">Añadir</button>
          </div>
        </form>

        <form method="GET" action="{{ route('productosDruni') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir productos y precios</label>
            <button type="submit" class="boton boton--admin" onClick="">Añadir</button>
          </div>
        </form>
      </div>  

      <div class="container__admin">
        <p class="title__admin">TIENDA LOOKFANTASTIC</p>
        <form method="GET" action="{{ route('tiendaLook') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir tienda</label>
            <button type="submit" class="boton boton--admin">Añadir</button>
          </div>
        </form>

        <form method="GET" action="{{ route('categoriasLook') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir categorias y subcategorias</label>
            <button type="submit" class="boton boton--admin">Añadir</button>
          </div>
        </form>

        <form method="GET" action="{{ route('preciosLook') }}" >
          @csrf
          @method('GET')
          <div class="container__options">
            <label class="text__admin">Añadir precios (Para añadir los precios primero tiene que tener productos)</label>
            <button type="submit" class="boton boton--admin">Añadir</button>
          </div>
        </form>
      </div>  
    </div>
    @else
    <!-- PRODUCTOS TOP -->
    <label class="title title--container">Lista de deseos</label>
    <div class="container container--top">
      @php($cont = 0)
      @if ($productos != [])
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
      @else
        <p>La lista de deseos esta vacia.</p>
      @endif

    </div>
    @endif
  </div>

@endsection