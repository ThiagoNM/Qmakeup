<<<<<<< HEAD
@extends('layouts.loginandregister')

@section('content')
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Link para boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link para iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Link para el style -->
    <link rel="stylesheet" href="{{ asset('css/loginAndRegister.css') }}">

</head>
<body>

>>>>>>> web-screpping
  <form class="container__form" method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
    @csrf
    <p class="title--form">Iniciar sesión</p>
    <div class="form-group">
      <label for="Email">Email address</label>
      <input type="email" class="form-control" id="Email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
      <label for="Password1">Password</label>
      <input type="password" class="form-control" id="Password1" name="password" required autocomplete="current-password" placeholder="Password">
    </div>
    <p class="text--form">No estas registrado? <a class="text--link" href="#">Registrate</a></p>
<<<<<<< HEAD
    <p class="text--form"><a class="text--link" href="/forgot-password">He olvidado la contraseña?</a></p>
    <button type="submit" class="boton--form">{{ __('Log in') }}</button>
  </form>


=======
    <button type="submit" class="boton--form">{{ __('Log in') }}</button>
  </form>

  <!-- Script para funciones -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
>>>>>>> web-screpping
