@extends('layouts.loginandregister')

@section('content')
        <div class="form--group">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="form-group">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="form--group">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <button class="boton--form">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="boton--form">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
