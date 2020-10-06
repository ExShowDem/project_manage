@extends('auth.master')

@section('title')
    {{ __('Verify Email Address') }}
@endsection

@section('content')
    <h3 class="form-title font-green">{{ __('Verify Email Address') }}</h3>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <strong>Error!</strong>&nbsp
        {{ __('A fresh verification link has been sent to your email address.') }}
        {{ __('Before proceeding, please check your email for a verification link.') }}
        <br/>
        {{ __('If you did not receive the email') }}, <a
                href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
    </div>
    <div class="form-actions" style="border-bottom: none; padding: 0 30px; text-align: center">
        <a href="{{ route('back-to-login') }}">
            <button type="submit" class="btn green uppercase">Back To Login</button>
        </a>
    </div>
@endsection
