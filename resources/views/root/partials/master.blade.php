<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ $token }}">
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
        var PDF_URL = "{!! asset('/')  !!}"
    </script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/font.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/root/css/theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
    @yield('styles')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body{background-color:#F1F1F1;font-family: "PT Serif", serif;}
        .page-header .page-header-menu {
            background: #D5DCE9 !important;
        }
        .page-footer {
            background: #FFC20E !important;
        }
        .page-wrapper .page-wrapper-middle{
            background: #f1f1f1 !important;
        }
        .page-header .page-header-menu .hor-menu .navbar-nav>li.active>a,
        .page-header .page-header-menu .hor-menu .navbar-nav>li.active>a:hover, .page-header .page-header-menu .hor-menu .navbar-nav>li.current>a, .page-header .page-header-menu .hor-menu .navbar-nav>li.current>a:hover {
            background: #FFC20E;
        }
        nav>li>a:focus, .page-header .page-header-menu .hor-menu .navbar-nav>li>a:hover {
            background: #FFC20E!important;
        }
        .page-header .page-header-menu .hor-menu .navbar-nav>li>a, .page-header .page-header-menu .hor-menu .navbar-nav>li>a>i {
            color: #000;
            font-weight:bold;
        }
        .page-header .page-header-menu .hor-menu .navbar-nav>li>a, .page-header .page-header-menu .hor-menu .navbar-nav>li>a:hover {
            color: #000;
            font-weight:bold;
        }
        nav>li>a:focus, .page-header .page-header-menu .hor-menu .navbar-nav>li>a:hover {
            background: #FFC20E!important;
        }
        .container{ color:#000; font-weight:bold;}
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
        .page-header .page-header-menu .hor-menu .navbar-nav>li.active>a, .page-header .page-header-menu .hor-menu .navbar-nav>li.active>a:hover, .page-header .page-header-menu .hor-menu .navbar-nav>li.current>a, .page-header .page-header-menu .hor-menu .navbar-nav>li.current>a:hover {
            background: #FFC20E;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user>.dropdown-toggle>.username, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user>.dropdown-toggle>i {
            color: #000;

        }
        .username{
            font-size:20px !important;
            font-weight:bold;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user.open>.dropdown-toggle>.username, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user.open>.dropdown-toggle>i, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user:hover>.dropdown-toggle>.username, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user:hover>.dropdown-toggle>i{
            color:#000;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu {
            background: #FFC20E;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu a {
            color:#000 !important;
        }
        .login{}
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu.dropdown-menu-default>li.divider {
            background: #fff;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu:after {
            border-bottom-color: #FFC20E;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu .dropdown-menu-list>li a>i, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu.dropdown-menu-default>li a>i {
            color: #000;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu .dropdown-menu-list>li a:hover, .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-dark .dropdown-menu.dropdown-menu-default>li a:hover {
            background: #FFC20E;
        }
        .username{
            font-size:20px !important;
        }
        .icon-user,.icon-key{
            color:#000 !important;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user .dropdown-menu>li>a {
            font-weight: 300;
            font-size: 14px !important;
        }
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown>.dropdown-menu{
            font-family: "PT Serif", serif;
        }
        .dropdown-menu .divider{
            margin:0px;
        }
        .dropdown-menu li a:hover{
            background:#ffce40 !important;
        }
        .page-header .page-header-menu .hor-menu .navbar-nav>li>a{
            font-size:18px;
        }
        .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle>img{
            width:30px;
            height:30px;
        }
        .page-logo img{ width: 150px !important;
            height: auto;
            margin-top: 15px !important;}
        .dropdown-user img{
            width:30px !important;
            height:30px !important;
        }
        .hung-custom-table table tbody:nth-of-type(5) tr:nth-of-type(2){
            display: none;
        }
        .hung-custom-table table tbody:nth-of-type(5) tr:nth-of-type(12){
            display: none;
        }
        .hung-custom-table table tbody:nth-of-type(7) tr:nth-of-type(3){
            display: none;
        }
        .hung-custom-table table tbody tr:nth-of-type(1){
            background: #f3f4f6;
        }
        .hung-custom-table table tbody:nth-of-type(9) tr, 
        .table-scrollable table tbody:nth-of-type(10) tr, 
        .table-scrollable table tbody:nth-of-type(11) tr{
            background: #f1f1f1 !important;
        }
        .table-hover>tbody>tr:hover, .table-hover>tbody>tr:hover>td{
            background: #f1f1f1 !important;
        }

        /*.table-scrollable table tbody:nth-of-type(9) tr{
            display: none;
        }
        .table-scrollable table tbody:nth-of-type(10) tr{
            display: none;
        }*/
        .page-header .page-header-top .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle>img{
            margin-top:0px !important;
        }

        table thead{
            background: #f1f1f1 !important;
        }
        .hung-custom-project table tr:nth-child(even){
            background: #f1f1f1 !important;
        }
        /*hide delete admin role*/
        .tree-default>ul>li>.tree-children>li>.tree-anchor>div>button{ display: none;}

        .hung-custom-project>table>tbody tr td a[href*='projects/1/edit'] + button { display: none;}

    </style>

</head>
<body class="page-container-bg-solid">
<div id="page" class="page-wrapper">
    <div class="page-wrapper-row">
        <div class="page-wrapper-top">
            <div class="page-header">
                @include('root.partials.header')
                @include('root.partials.menu')
            </div>
        </div>
    </div>
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <input type="hidden" name="current_user" value="{{ json_encode($currentUser) }}">
                    @include('root.partials.content')
                </div>
            </div>
        </div>
    </div>
    @include('root.partials.footer')
</div>
@include('root.partials.scripts')
<script>
    $(document).ready(function(){

        $('.table-scrollable table tbody:nth-of-type(9) tr input').prop('disabled', true);
        $('.table-scrollable table tbody:nth-of-type(10) tr input').prop('disabled', true);
        $('.table-scrollable table tbody:nth-of-type(11) tr input').prop('disabled', true);

        var substr = $('.table-scrollable table tbody').length;
        var i;
        var j;
        for (i = 1; i <= substr; ++i) {
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(2) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                 for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(2) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);


                    }
                }
            });
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(3) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(3) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);
                    }
                }
            });
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(4) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(4) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);
                    }
                }
            });
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(5) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(5) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);
                    }
                }
            });
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(6) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(6) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);
                    }
                }
            });
            $('.table-scrollable table tbody:nth-of-type('+i+') tr:nth-of-type(1) td:nth-of-type(7) input').change(function() {
                var counttr = $(this).parent().parent().parent().parent().children().length-1;
                for(j = 0; j < counttr; ++j) {
                    var node = $(this).parent().parent().parent().nextAll().eq(j).find('td:nth-of-type(7) input');
                    if (this.checked) {
                        node.prop('checked', true);
                    } else {
                        node.prop('checked', false);
                    }
                }
            });

        }

    });
</script>
</body>
