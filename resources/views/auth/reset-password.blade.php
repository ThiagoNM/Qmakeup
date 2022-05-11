<<<<<<< HEAD
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
=======
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
>>>>>>> web-screpping
