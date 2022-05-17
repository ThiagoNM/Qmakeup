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
            <label >{{ $producto->valoracion}} Valoraciones</label>
          </div>
          <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-heart"></i></a>    
          <div class="container__price">
            <p class="text--price">El precio más barato es:  {{$precio->precio}}€</p>
            <p class="text--price">El precio más Gastos de envio Baleares:  {{$precio->precio + $tienda->gastos_baleares}}€</p>
            <p class="text--price">El precio más Gastos de envio Peninsula:  {{$precio->precio + $tienda->gastos_peninsula}}€</p>
            <p class="text--product">Gastos de envio Baleares: {{$tienda->gastos_baleares}}€</p>
            <p class="text--product">Gastos de envio Peninsula: {{$tienda->gastos_peninsula}}€</p>
            <p class="text--product">A partir de: {{$tienda->gastos_minimos}}€ envios gratis.</p>
            <p class="text--product">De la tienda: {{ucfirst($tienda->nombre)}}</p>
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
            @foreach($tiendas as $t)
            <tr>
              <td>{{$p->precio + $t->gastos_baleares}}</td>
              <td>{{$p->precio + $t->gastos_peninsula}}</td>
              <td>{{$p->precio}}</td>
              <td>{{$t->gastos_baleares}}</td>
              <td>{{$t->gastos_peninsula}}</td>
              <td>{{$t->gastos_minimos}}</td>
              <td><a href="{{$pagina_e->where('id_tienda', $t->id)->first()->url}}">{{ucfirst($t->nombre)}}</a></td>
            </tr>
            @endforeach
          @endforeach
        </table>

    </div> 
  @endsection
