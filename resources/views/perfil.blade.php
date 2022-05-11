@extends('layouts.principal')

@section('content')
<<<<<<< HEAD
    <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king">

      @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
      @endif

      <form class="container__form" action="{{ route('update' )}}" method="PUT">
        @csrf
        @method('PUT')
        <p class="title--container">Datos del usuario</p>
        <div class="form-group">
          <label for="Username">Usuario</label>
          <input type="text" class="form-control" id="Username" name="name" placeholder="{{ $user->name }}">
        </div>
        <p class="text--form"><button type="submit"class="text--link" >Actualizar nombre</button></p>

      </form>
      <p class="text--form"><a class="text--link" href="{{route('ea')}}">Cambiar contraseña</a></p>


     
      

      <!-- PRODUCTOS TOP -->
      <label class="title title--container">Lista de deseos</label>
      <div class="container container--top">

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

=======
  <!-- CONTENEDOR PARA CENTRAR -->
  <div class="container__king">

    @if(session()->has('success'))
          <div class="alert alert-success">
              {{ session()->get('success') }}
          </div>
    @endif

    <form class="container__form" method="POST" >
      <p class="title--container">Datos del usuario</p>
      <div class="form-group">
        <label for="Username">Username</label>
        <input type="text" class="form-control" id="Username" name="name" placeholder="{{Auth::user()->name}}">
      </div>
      <div class="form-group">
        <label for="Email">Email address</label>
        <input type="email" class="form-control" id="Email" name="email" placeholder="{{Auth::user()->email}}">
      </div>
      
      <button type="submit" class="boton--form">Actualizar cambios</button>
    </form>

    <!-- PRODUCTOS TOP -->
    <label class="title title--container">Lista de deseos</label>
    <div class="container container--top">

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
>>>>>>> web-screpping
      </div>

    </div>

<<<<<<< HEAD

    <!-- Script para funciones -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
=======
  </div>
>>>>>>> web-screpping
@endsection