@extends('layouts.principal')

@section('content')
<!-- BIENVENIDA -->
<div class="container__welcome">
    <video src="{{ asset('videos/How to Light a MAKEUP ad.mp4') }}" autoplay muted loop></video>
    <div class="centered">
        <label class="title">HOLA, BIENVENIDO!</label>
        <label class="text">Busca tu producto de maquillaje clicando</label>
        <button class="boton boton--welcome">AQUÍ</button>
    </div>
</div>
<br>

<!-- CONTENEDOR PARA CENTRAR -->
<div class="container__king">

  <!-- PRODUCTOS TOP -->
  <label class="title title--container">Productos mejor valorados</label>
  @php
    $cont = 0
  @endphp
  <div class="containercontainer--top">

    @foreach ($productos as $producto)

    @php
      $cont += 1
    @endphp

    @if($producto->valoracion >= 3 && $cont <= 3)

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

    @endif

    @endforeach

  </div>

  <!-- INFORMACIÓN DE LA EMPRESA -->
  <label class="title title--container">Sobre nosotros</label>
  <div class="container">
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Est ipsam similique quo explicabo nam iure delectus accusantium dolorum consequuntur, nobis possimus vero! Laborum soluta similique adipisci, blanditiis est illum quasi!</p>
  </div>

  <p class="title title--question">¿De donde sacamos los productos?</p>
  <div class="container">
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Est ipsam similique quo explicabo nam iure delectus accusantium dolorum consequuntur, nobis possimus vero! Laborum soluta similique adipisci, blanditiis est illum quasi!</p>
  </div>
  <div class="continer__business">
    <img src="{{ asset('imagenes/logo-DRUNI.png') }}" alt="">
    <img src="{{ asset('imagenes/logo-lookfantastic.png') }}" alt="">
  </div>
</div>
@endsection