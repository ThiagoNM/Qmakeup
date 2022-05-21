@extends('layouts.principal')

@section('content')
      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--details">
      <div class="container__details">
        <img class="img__product--details" src="{{$producto->imagen}}" alt="">
        <div class="container__product--details">
          <label class="title title--details">{{ $producto->nombre}}</label>
          <p class="text--product">{{ $producto->descripcion}}</p>
          <div class="container__starsProduct container__starsProduct--details">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label class="text--ratings" >{{ $producto->valoracion}} Valoraciones</label>
          </div>
          @if(Auth::user())

            @php($estado = $listaDeseo->where('id_producto', $producto->id)->where('id_usuario', Auth::user()->id)->count())

            @if ($estado==0)
              <a class="icono icono--navbar" type="button"><i class="bi bi-heart"></i></a>
            @else
              <a class="icono icono--navbar" type="button"><i class="bi bi-heart-fill"></i></a>
            @endif

          @endif
          <div class="container__price">
            <p class="text--product bolder">El precio más barato es: <a href="{{$precio->url_producto}}" target="_blank"> {{$precio->precio}}€ </a> </p>
            <p class="text--product bolder">El precio más Gastos de envio Baleares:  {{$precio->precio + $tienda->gastos_baleares}}€</p>
            <p class="text--product bolder">El precio más Gastos de envio Peninsula:  {{$precio->precio + $tienda->gastos_peninsula}}€</p>
            <p class="text--product">Gastos de envio Baleares: {{$tienda->gastos_baleares}}€</p>
            <p class="text--product">Gastos de envio Peninsula: {{$tienda->gastos_peninsula}}€</p>
            <p class="text--product">A partir de: {{$tienda->gastos_minimos}}€ envios gratis.</p>
            <p class="text--product">De la tienda: <a href="{{$pagina_e->where('id_tienda', $tienda->id)->first()->url}}" target="_blank"> {{ucfirst($tienda->nombre)}} </a> </p>
          </div>
        </div>
      </div>

        <table class="tabla__precios">
          <tr class="title--table">
            <td>Precio total Baleares</td>
            <td>Precio total Peninsula</td>
            <td>Precio</td>
            <td>Gastos de envio Baleares</td>
            <td>Gastos de envio Peninsula</td>
            <td>Envios gratis a partir de</td>
            <td>Tienda</td>
          </tr>
          @foreach($precios as $p)
            @php($t = $tiendas->where("id", $p->id_tienda)->first())
            <tr>
              <td>{{$p->precio + $t->gastos_baleares}}</td>
              <td>{{$p->precio + $t->gastos_peninsula}}</td>
              <td><a href="{{$p->url_producto}}" target="_blank">{{$p->precio}}</a></td>
              <td>{{$t->gastos_baleares}}</td>
              <td>{{$t->gastos_peninsula}}</td>
              <td>{{$t->gastos_minimos}}</td>
              <td><a href="{{$pagina_e->where('id_tienda', $t->id)->first()->url}}" target="_blank">{{ucfirst($tiendas->where("id", $p->id_tienda)->first()->nombre)}}</a></td>
            </tr>
          @endforeach
        </table>

    </div>
  @endsection
