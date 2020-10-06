<div class="page-content" @if (request()->route('projectId') == 0) style="margin-left: 0px;" @endif>
    {{--@include('admin.partials.breadcrumb')--}}
    {{--<h1 class="page-title">--}}
        {{--@yield('content-title')--}}
    {{--</h1>--}}
    @include('admin.partials.status')
    @if (!\Request::is('report'))
    <div class="row">
        <div class="col-md-3 margin-bottom-20">
            <select2-projects/>
        </div>
    </div>
@endif
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
