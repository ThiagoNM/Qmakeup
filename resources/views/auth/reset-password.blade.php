@extends('layouts.loginandregister')

@section('content')
<form method="POST" class="container__form" action="{{ route('password.update') }}">
    @csrf
    
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div class="form--group">
        <p class="text--form" for="email" :value="__('Email')">Email</p>
        <x-input class="form--control" id="email"  type="email" name="email" :value="old('email', $request->email)" required autofocus onfocus="this.blur()"/>
    </div>
    <!-- Password -->
    <div class="form--group">
        <p class="text--form" for="password" :value="__('Password')">Contraseña</p>
        <input class="form--control" id="password"  type="password" name="password" required />
    </div>
    <!-- Confirm Password -->
    <div class="form--group" >
        <p class="text--form" for="password_confirmation" :value="__('Confirm Password')">Confirmar contraseña</p>
        <input class="form--control" id="password_confirmation"
                            type="password"
                            name="password_confirmation" required />
    </div>

    <div class="form--group">
        <button class="boton--form">Cambiar contraseña</button>
    </div>
</form>
