@extends('layouts.app')

@section('content')
<link type="text/css" rel="stylesheet" href="{{asset('/css/cobaregister.css')}}">
<div class="login-container d-flex align-items-center justify-content-center">
    <div class="login-form text-center">
        <img width="170rem" class="mb-5 font-weight-light" src="{{asset('images/restaurant.svg')}}"/>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-custom btn-block text-uppercase rounded-pill btn-lg">{{ __('Verify Your Email Address') }}</button>
        </form>
    {{-- </form> --}}
    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
