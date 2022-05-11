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
  <form class="container__form" method="POST" action="{{ route('register') }}">
      @csrf
    <p class="title--form">Registarte</p>
    <div class="form-group">
      <label for="Username">Name</label>
      <input type="text" class="form-control" id="Username" name="name" placeholder="Enter username">
    </div>
    <div class="form-group">
      <label for="Email">Email address</label>
      <input type="email" class="form-control" id="Email" name="email" placeholder="Enter email">
    </div>
    <div class="form-group">
      <label for="Password1">Password</label>
      <input type="password" class="form-control" id="Password1" name="password" required autocomplete="new-password"  placeholder="Password">
    </div>
    <div class="form-group">
      <label for="Password2">Confirm password</label>
      <input type="password" class="form-control" id="Password2" name="password_confirmation" required placeholder="Confirm password">
    </div>
    <p class="text--form">Ya estas registrado? <a class="text--link" href="#">Iniciar sesión</a></p>
    <button type="submit" class="boton--form">{{ __('Register') }}</button>
<<<<<<< HEAD
  </form>
=======
  </form>

  <!-- Script para funciones -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
>>>>>>> web-screpping
