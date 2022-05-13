@extends('layouts.principal')

@section('content')
      <!-- CONTENEDOR PARA CENTRAR -->
    <div class="container__king container__king--details">
      
      <div class="container__details">
        <img class="img__product--details" src="../img/producto.jpg" alt="">
        
        <div class="container__product--details">
          <label class="title title--details">Nombre</label>
          <label class="text--product">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Perspiciatis, quasi nulla? Ea optio perferendis vel dicta aut asperiores facere quae, consequatur officia repudiandae illo quasi illum praesentium ad suscipit nemo.</label>
          <div class="container__starsProduct container__starsProduct--details">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <i class="bi bi-star"></i>
            <label >xx Valoraciones</label>
          </div>
          <a class="icono icono--navbar" type="button" href="#"><i class="bi bi-heart"></i></a>    
          
          <div class="container__price">
            <p class="text--price">El precio más barato es:</p>
            <p class="text--product">Gastos de envio:</p>
            <p class="text--product">Impuestos:</p>
            <p class="text--product">De la tienda:</p>
          </div>

        </div>
      </div>

        <table class="tabla__precios">
          <tr class="title--table">
            <td>Precio</td>
            <td>Gastos de envio</td>
            <td>Impuestos</td>
            <td>Tienda</td>
          </tr>
          <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
          </tr>
        </table>

    </div> 
  @endsection