@extends('layouts.app')

@section('title','SIGN IN')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/cobalogin.css')}}">
<div class="login-container d-flex align-items-center justify-content-center">
    <form method="POST" action="{{ route('login') }}" class="login-form text-center">
        @csrf
        <img width="170rem" class="mb-5 font-weight-light" src="{{asset('images/restaurant.svg')}}"/>

        <div class="form-group">
            <input id="email" type="email" class="form-control rounded-pill form-control-lg @error('email') is-invalid @enderror" name="email" placeholder="Email.." required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input type="password" class="form-control rounded-pill form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password.." required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="forgot-link d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
            </div>
            {{-- <a href="#">Forgot Password</a> --}}
            @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            @endif
        </div>
        <button type="submit" class="btn mt-4 btn-custom btn-block text-uppercase rounded-pill btn-lg">{{ __('Login') }}</button>
        {{-- @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif --}}
        @guest
            @if (Route::has('register'))
                <p class="mt-3 font-weight-normal">Don't have an account ? <a href="{{ route('register') }}"><strong>{{ __('Register Now') }}</strong></a></p>
            @endif
        @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
        </li>
        @endguest
    </form>
</div>
@endsection
