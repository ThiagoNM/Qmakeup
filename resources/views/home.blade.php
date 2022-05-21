@extends('layouts.principal')

@section('content')
<!-- BIENVENIDA -->
<div class="container__welcome">
    <video src="{{ asset('videos/How to Light a MAKEUP ad.mp4') }}" autoplay muted loop></video>
    <div class="centered">
        <label class="title">BIENVENIDO!</label>
        @if(Auth::user())
          <p class="title">{{Auth::user()->name}}</p>
        @endif
        <label class="text">Busca tu producto de maquillaje clicando</label>
        <a aria-current="page" href="{{ route('categorias') }}"><button class="boton boton--welcome">Aquí</button></a>
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
  <div class="container container--top">
    @foreach ($productos as $producto)
      @php
        $cont += 1
      @endphp
      @if($cont <= 3)
        @php($cont = 0)
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
      @endif
    @endforeach
  </div>

  <!-- INFORMACIÓN DE LA EMPRESA -->
  <label class="title title--container">Sobre nosotros</label>
  <div class="container">
    <p>Somos unos jóvenes emprendedores que nos hemos dedicado a hacer esta página para facilitar a todos los amantes del maquillaje que se les sea más fácil a la hora de buscar el producto ideal al mejor precio.</p>
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