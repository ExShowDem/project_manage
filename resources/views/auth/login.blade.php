@extends('auth.master')

@section('title')
    Login
@endsection

@section('content')
    <form class="login-form" method="POST" action="{{ route('login') }}">
        <h3 class="form-title font-green">Login</h3>
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

    <script>
        function IEdetection() {
            let ua = window.navigator.userAgent;
            let msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older, return version number
                // return ('IE ' + parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10));
                return true;
            }
            let trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11, return version number
                let rv = ua.indexOf('rv:');
                // return ('IE ' + parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10));
                return true;
            }
            let edge = ua.indexOf('Edge/');
            if (edge > 0) {
                //Edge (IE 12+), return version number
                // return ('IE ' + parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10));
                return false;
            }

            return false;
        }

        if (IEdetection()) {
            alert('Trình duyệt web của bạn không tương thích với phần mềm. Vui lòng dùng trình duyệt web khác như Firefox, Chrome');
            document.getElementsByClassName('form-actions')[0].style.display = "none";
        }
    </script>
@endsection
