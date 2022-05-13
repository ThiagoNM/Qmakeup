@extends('layouts.loginandregister')

@section('content')
<form method="POST" class="container__form" action="{{ route('password.email') }}">
    
    @csrf
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    
    <div class="form--group">
        <p class="text--form">¿Has olvidado la contraseña?</p>
        <p class="text--form">Introduce tu correo para recuperarla.</p>
        <label class="text--form" for="email" :value="__('Email')"></label>
        <input id="email" class="form--control" type="email" name="email" :value="old('email')" placeholder="Email" autodocusrequired autofocus />
    </div>
    
    <div class="form--group">
        <button class="boton--form">Email Password Reset Link</button>
    
    </div>
</form>
