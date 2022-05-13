@extends('layouts.loginandregister')

@section('content')
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
    <p class="text--form">Ya estas registrado? <a class="text--link" href="{{ route('login') }}">Iniciar sesión</a></p>
    <button type="submit" class="boton--form">{{ __('Register') }}</button>
  </form>
