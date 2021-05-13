@extends('layouts.app')

@section('title','SIGN UP')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/cobaregister.css')}}">
<div class="login-container d-flex align-items-center justify-content-center">
    <form method="POST" action="{{ route('register') }}" class="login-form text-center">
        @csrf
        <img width="170rem" class="mb-5 font-weight-light" src="{{asset('images/restaurant.svg')}}"/>

        <div class="form-group">
            <input id="name" type="text" class="form-control rounded-pill form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name.." autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="phone_number" type="number" class="form-control rounded-pill form-control-lg @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone_number" placeholder="Phone Number..">
            @error('phone_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="email" type="email" class="form-control rounded-pill form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email..">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control rounded-pill form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password..">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="password-confirm" type="password" class="form-control rounded-pill form-control-lg" name="password_confirmation" required autocomplete="new-password" placeholder="Password Confirm..">
        </div>
        <button type="submit" class="btn mt-4 btn-custom btn-block text-uppercase rounded-pill btn-lg">
            {{ __('Register') }}
        </button>
    </form>
</div>
@endsection
