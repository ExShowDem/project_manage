<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ $token }}">
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <meta name="vapid_public_key" content="{{ env('VAPID_PUBLIC_KEY') }}">

    <script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!}
    var PDF_URL = "{!! asset('/')  !!}"
    </script>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/font.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
    <style>
        .page-header.navbar .top-menu .navbar-nav>li.dropdown .dropdown-toggle>i {
            color: #000 !important;
        }
        .page-header.navbar .page-project-title h4{
            color:#000;
            font-weight:bold;
        }
        .page-sidebar .title{ color:#000;
            font-weight:bold;}
        .page-sidebar .page-sidebar-menu>li>a>i, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li>a>i {
            color: #000;
        }
        .sub-menu .title{
            font-weight: normal;}
        .sub-menu .title:hover {
            color:#000;
        }
        .login{}
        .page-header.navbar{
            background: #F1f1f1;

        }
        .top-menu{background-color:#F1f1f1}


        .dropdown-user a{
            line-height:19px !important;
        }
        .dropdown-user span{
            color:#000!important;
            font-weight:bold !important;
        }
        .page-header.navbar .top-menu .navbar-nav>li.dropdown .dropdown-toggle:hover, .page-header.navbar .top-menu .navbar-nav>li.dropdown.open .dropdown-toggle {
            background-color: #f1f1f1;
        }
        .dropdown-menu .divider{
            margin:0px;
        }
        .dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover{
            background:#ffce40 !important;
        }
        .font-green-haze {
            color: #26BFFF !important;
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
        .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {

            background-color: #FFC20E;
            border-color: #FFC20E;
        }
        .btn-info {
            background-color: #26BFFF;
            border-color: #26BFFF;
        }
        .page-sidebar .page-sidebar-menu>li.active.open>a>.arrow.open:before, .page-sidebar .page-sidebar-menu>li.active.open>a>.arrow:before, .page-sidebar .page-sidebar-menu>li.active.open>a>i, .page-sidebar .page-sidebar-menu>li.active>a>.arrow.open:before, .page-sidebar .page-sidebar-menu>li.active>a>.arrow:before, .page-sidebar .page-sidebar-menu>li.active>a>i, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a>.arrow.open:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a>.arrow:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a>i, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a>.arrow.open:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a>.arrow:before, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a>i{
            color:#000;
        }
        .dropdown-menu{
            background-color: #FFC20E !important;
        }
        .dropdown-menu a{  color: #000 !important; ;
        }

        .page-header-fixed-mobile .page-header.navbar .top-menu{background-color:#FFC20E}
        .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle:hover{background-color:#f1f1f1}
        body{background-color:#F1F1F1;font-family: "PT Serif", serif;}

        .page-sidebar .page-sidebar-menu>li>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li>a{
            font-size:16px;
        }

        .page-sidebar, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover {
            background-color: #f1f1f1;
        }
        .page-sidebar .page-sidebar-menu>li.active.open>a, .page-sidebar .page-sidebar-menu>li.active>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a {
            background: #FFC20E;
        }
        .page-sidebar .page-sidebar-menu .sub-menu>li.active>a, .page-sidebar .page-sidebar-menu .sub-menu>li.open>a, .page-sidebar .page-sidebar-menu .sub-menu>li:hover>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.active>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.open>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li:hover>a {
            background: #FFF41A!important;
        }
        .sub-menu {
            background-color: #d5dce9 !important;
        }
        .page-sidebar .page-sidebar-menu .sub-menu>li.active>a, .page-sidebar .page-sidebar-menu .sub-menu>li.open>a, .page-sidebar .page-sidebar-menu .sub-menu>li:hover>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.active>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li.open>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu>li:hover>a {
            background: #ffce40!important;
        }
        .page-sidebar .page-sidebar-menu>li.open>a, .page-sidebar .page-sidebar-menu>li:hover>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.open>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li:hover>a {
            background: #FFC20E !important;
        }
        li.open{
            background: #f1f1f1;
        }
        .page-sidebar .page-sidebar-menu .sub-menu li>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu li>a{
            padding: 6px 15px 6px 30px;
        }
       .custom-margin-hung .portlet-input,.custom-margin-hung .form-group{ margin-top:10px;}
        .username{
            font-size:20px !important;
        }
        .icon-user,.icon-key{
            color:#000 !important;
        }
        .dropdown-menu{
            font-family: "PT Serif", serif;
        }
        .page-header.navbar .top-menu .navbar-nav>li.dropdown .dropdown-menu:after{
            border-bottom: 6px solid #FFC20E;
        }
        .page-header.navbar .top-menu .navbar-nav>li.dropdown .dropdown-menu:before{
            border-bottom-color: #FFC20E;
        }
        .dropdown-menu-list{ background: #fff;}
        .dropdown-menu-list img{
            width:30px !important; height:30px !important;;
        }
        .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle>img{
            width:30px;
            height:30px;
        }
        .top-menu img{
            width:30px !important;
            height:30px !important;

        }
        .tag{
            font-size:12px !important;
        }
        body, h1, h2, h3, h4, h5, h6{
            font-family: "PT Serif", serif;
        }
        h3{
            font-size: 32px;
            text-transform: uppercase;
            color: #FFC20E;
            font-weight: bold;
        }
        .height-dashboard{
            height:150px;
        }
        .morris-hover.morris-default-style .morris-hover-point, .select2-container--bootstrap .select2-results__group, .select2-container--bootstrap .select2-selection{
            font-family: "PT Serif", serif !important;
        }
        table tr:nth-child(even){
            background: #f1f1f1 !important;
        }
        table thead{
            background: #f1f1f1 !important;
        }
        .margin-bottom-10{
            margin-top:10px;
        }



    </style>

    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    @yield('styles')
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<div id="page" class="page-wrapper">
    <div id="loading" class="hide"></div>
    @include('admin.partials.header')
    <div class="clearfix"></div>
    <div class="page-container">
        @if (request()->route('projectId') != 0)
        @include('admin.partials.sidebar')
        @endif
        <div class="page-content-wrapper">
            <input type="hidden" name="current_project_id" value="{{ request()->route('projectId') }}">
            <input type="hidden" name="current_project_name" value="{{ $currentProjectName }}">
            <input type="hidden" name="current_user" value="{{ json_encode($currentUser) }}">
            @include('admin.partials.content')
        </div>
        {{--@include('admin.partials.footer')--}}
    </div>
</div>
@include('admin.partials.scripts')
<script src="{{ asset('js/push-notification/enable-push.js') }}" defer></script>
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<script>
    $(document).ready(function(){
        if(window.location.pathname !='/report'){
            $(".project-search").hide();
        }
        //$(".custom-margin-hung").children().find('select option[value=2]').attr('selected','selected');
    });
</script>


</body>
</html>


