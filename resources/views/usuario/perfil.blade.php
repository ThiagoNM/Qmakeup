@extends('layouts.principal')
@section('content')
    <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king">

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
        <p class="text--form"><button type="submit" class="boton--form" >Actualizar nombre</button></p>
        <p class="text--form"><a class="text--link" href="{{route('ea')}}">Cambiar contraseña</a></p>
      </form>
      @endif

      @if(Auth::user()->id_rol == 2)
      <div class="form-group">
        <form method="POST" action="{{ route('logout') }}" >
          @csrf
          <div>
            <label class="title--container">Borrar BD</label>
            <button type="submit" class="boton--welcome">Delete</button>
          </div>
          <div>
            <label class="title--container">Recoger precios</label>
            <button type="submit" class="boton--welcome">Catch</button>
          </div>
          <div>
            <label class="title--container">Introducir productos</label>
            <button type="submit" class="boton--welcome">Put</button>
          </div>
        </form>
      </div>  
      @endif
     
      
      @if( Auth::user()->id_rol == 2)
        <label class="title--container">Tenga cuidado con los botones</label>
      @else
      <!-- PRODUCTOS TOP -->
      <label class="title title--container">Lista de deseos</label>
      <div class="container container--top">

      </div>
      @endif
    </div>

  </div>
@endsection