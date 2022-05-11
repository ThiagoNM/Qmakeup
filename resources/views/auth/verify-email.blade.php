<<<<<<< HEAD
@extends('layouts.loginandregister')

@section('content')
        <div class="form--group">
=======
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
>>>>>>> web-screpping
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
<<<<<<< HEAD
            <div class="form-group">
=======
            <div class="mb-4 font-medium text-sm text-green-600">
>>>>>>> web-screpping
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

<<<<<<< HEAD
        <div class="form--group">
=======
        <div class="mt-4 flex items-center justify-between">
>>>>>>> web-screpping
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
<<<<<<< HEAD
                    <button class="boton--form">
                        {{ __('Resend Verification Email') }}
                    </button>
=======
                    <x-button>
                        {{ __('Resend Verification Email') }}
                    </x-button>
>>>>>>> web-screpping
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

<<<<<<< HEAD
                <button type="submit" class="boton--form">
=======
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
>>>>>>> web-screpping
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
<<<<<<< HEAD
=======
    </x-auth-card>
</x-guest-layout>
>>>>>>> web-screpping
