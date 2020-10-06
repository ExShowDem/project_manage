<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/font.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/login.min.css') }}"/>
    <style>
        body{background-color:#F1F1F1;font-family: "PT Serif", serif;}
        .login {

            background-color: #f1f1f1 !important;

        }
        .btn.blue:not(.btn-outline) {
            color: #FFF;
            background-color: #26BFFF !important;
            border-color:#08b4ff !important;
        }
        .btn.green:not(.btn-outline) {

            color: #FFF;
            background-color: #26BFFF !important;
            border-color:#08b4ff !important;
        }
        .btn-success {
            color: #FFF;
            background-color: #26BFFF !important;
            border-color:#08b4ff !important;
        }
    </style>
    @yield('styles')
</head>
<body class=" login">
<div class="logo">
    <a href="#">
        <img src="{{ asset('assets/admin/images/megaon.png') }}"
             style="width: 200px; height: auto;"
             alt="logo" class="logo-default"/>
    </a>
</div>
<div class="content">
    @yield('content')
</div>
<div class="copyright"> 2019 © Megaon</div>
</body>
<script src="{{ asset('assets/admin/js/theme.js') }}"></script>
<script src="{{ asset('assets/admin/js/login.min.js') }}"></script>
</html>
