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
  <form class="container__form" method="post" action="{{ route('password_update') }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <p class="title--form">Cambiar contraseña</p>
    <div class="form-group">
      <label for="Password1">Password</label>
      <input type="password" class="form-control" id="Password1" name="password" required autocomplete="new-password"  placeholder="Password" autofocus>
    </div>
    <div class="form-group">
      <label for="Password2">Confirm password</label>
      <input type="password" class="form-control" id="Password2" name="password_confirmation" required placeholder="Confirm password">
    </div>
    <button type="submit" class="boton--form">{{ __('Cambiar') }}</button>
  </form>

  <!-- Script para funciones -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
