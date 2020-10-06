<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/font.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>

    @yield('styles')
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<div id="page" class="page-wrapper">
    <div class="clearfix"></div>
    <div class="page-container">
        <div class="page-content-wrapper">
            <input type="hidden" name="current_project_id" value="{{ request()->route('projectId') }}">
            @include('admin.export.content')
        </div>
    </div>
</div>

</body>
</html>


