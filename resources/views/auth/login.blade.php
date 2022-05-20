@extends('layouts.loginandregister')

@section('content')
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
    <p class="text--form">No estas registrado? <a class="text--link" href="{{ route('register') }}">Registrate</a></p>
    <p class="text--form"><a class="text--link" href="{{ route('password.request') }}">He olvidado la contraseña?</a></p>
    <button type="submit" class="boton--form">{{ __('Log in') }}</button>
  </form>
@endsection
