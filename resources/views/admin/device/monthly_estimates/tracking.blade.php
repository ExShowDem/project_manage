@extends('admin.partials.master')

@section('title')
    Lịch sử dự trù thiết bị tháng
@endsection

@section('content')
    <device-monthly-estimates-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.monthly-estimates.tracking'"></device-monthly-estimates-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-monthly-estimates.js') }}"></script>
@endsection
