@extends('layouts.principal')

@section('content')
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{route('add-rating')}}" method="POST" >
        @csrf
        <input type="hidden" name="id_producto" value="{{ $producto->id }}">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Valorar {{$producto->nombre}} </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="rating-css">
              <div class="star-icon">
                @if($user_rating)

                  @for($i=1; $i<= $user_rating->stars_rated; $i++)
                    <input type="radio" value="{{$i}}" name="product_rating" checked id="rating{{$i}}">
                    <label for="rating{{$i}}" class="fa fa-star"></label>
                  @endfor
                  @for($j = $user_rating->stars_rated+1; $j <=5; $j++)
                    <input type="radio" value="{{$j}}" name="product_rating" id="rating{{$j}}">
                    <label for="rating{{$j}}" class="fa fa-star"></label>
                  @endfor
                  
                @else
                  <input type="radio" value="1" name="product_rating" checked id="rating1">
                  <label for="rating1" class="fa fa-star"></label>
                  <input type="radio" value="2" name="product_rating" id="rating2">
                  <label for="rating2" class="fa fa-star"></label>
                  <input type="radio" value="3" name="product_rating" id="rating3">
                  <label for="rating3" class="fa fa-star"></label>
                  <input type="radio" value="4" name="product_rating" id="rating4">
                  <label for="rating4" class="fa fa-star"></label>
                  <input type="radio" value="5" name="product_rating" id="rating5">
                  <label for="rating5" class="fa fa-star"></label>
                @endif
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">valorar</button>
        </div>
      </form>
    </div>
  </div>
</div>
      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--details">
      
      <div class="container__details">
        <img class="img__product--details" src="{{$producto->imagen}}" alt="">
        <div class="container__product--details">
          <label class="title title--details">{{ $producto->nombre}}</label>
          <p class="text--product">{{ $producto->descripcion}}</p>
          @php($ratenum = number_format($rating_value))
          <div class="rating">
            @for($i=1; $i<= $ratenum; $i++)
              <i class="fa fa-star checked"></i>
            @endfor
            @for($j = $ratenum+1; $j <=5; $j++)
              <i class="fa fa-star"></i>
            @endfor
            <span>
              @if($ratings->count() > 0)
                {{ $ratings->count()}} Valoraciones
              @else
                No valorado
              @endif
            </span>
          </div>
          
        
          @if(Auth::user())
            <a class="icono icono--navbar" type="button" href="{{ route('lista', $producto->id) }}"><i class="bi bi-heart"></i></a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              Valorar
            </button>    
          @endif
          <div class="container__price">
            <p class="text--price">El precio más barato es:  <a href="{{$precio->url_producto}}" target="_blank">{{($precio->precio)}}€</a></p>
            <p class="text--price">El precio más Gastos de envio Baleares:  {{$precio->precio + $tienda->gastos_baleares}}€</p>
            <p class="text--price">El precio más Gastos de envio Peninsula:  {{$precio->precio + $tienda->gastos_peninsula}}€</p>
            <p class="text--product">Gastos de envio Baleares: {{$tienda->gastos_baleares}}€</p>
            <p class="text--product">Gastos de envio Peninsula: {{$tienda->gastos_peninsula}}€</p>
            <p class="text--product">A partir de: {{$tienda->gastos_minimos}}€ envios gratis.</p>
            <p class="text--product">De la tienda: <a href="{{$tienda->url}}" target="_blank">{{ucfirst($tienda->nombre)}}</a></p>
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
            @php($t = $tiendas->where("id",$p->id_tienda)->first())
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
        </table>
    </div> 
  @endsection
