<h1>Categoria {{$categoria}}</h1>

@foreach ($productos as $producto)
    {{$producto->nombre}}
@endforeach