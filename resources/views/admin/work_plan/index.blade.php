@extends('admin.partials.master')

@section('title')
    Kế hoạch công việc
@endsection

@section('content')
    <work-plan-list></work-plan-list>
@endsection

@section('script')
    <script>
        $(function () {
            $('.menu-toggler.sidebar-toggler').click();
        });
    </script>
    <script src="{{ asset('js/modules/workplan.js') }}"></script>
    <script src="https://export.dhtmlx.com/gantt/api.js"></script>
@endsection