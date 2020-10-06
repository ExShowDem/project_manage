@extends('admin.partials.master')

@section('title')
    Xuất thiết bị tới dự án
@endsection

@section('content')
    <device-project-list></device-project-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-project.js') }}"></script>
@endsection
