@extends('auth.master')

@section('title')
    Error
@endsection

@section('content')
    <form class="login-form" method="POST" action="{{ route('login') }}">
        <h3 class="form-title font-green">Error</h3>
        @include('admin.partials.status')
        @csrf
        <div class="form-group">
            <input id="email"
                   type="email"
                   class="form-control form-control-solid placeholder-no-fix @error('email') is-invalid @enderror"
                   name="email"
                   placeholder="Email"
                   value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>
        <div class="form-group">
            <input id="password"
                   type="password"
                   class="form-control form-control-solid placeholder-no-fix @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Password"
                   required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="check mt-checkbox mt-checkbox-outline">
                <input type="checkbox"
                       name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                {{ __('Remember Me') }}
                <span></span>
            </label>
        </div>
        <div class="form-actions" style="border-bottom: none; padding: 0 30px; text-align: center">
            <button type="submit" class="btn green uppercase">Login</button>
        </div>
    </form>

@endsection
