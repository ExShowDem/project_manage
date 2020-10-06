@extends('admin.partials.master')

@section('title')
    Lịch sử xuất thiết bị tới dự án
@endsection

@section('content')
    <device-project-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.project.tracking'"></device-project-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-project.js') }}"></script>
@endsection
