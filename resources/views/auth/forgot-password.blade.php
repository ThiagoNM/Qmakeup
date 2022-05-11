<<<<<<< HEAD
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
=======
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
>>>>>>> web-screpping
