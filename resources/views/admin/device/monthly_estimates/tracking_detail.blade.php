@extends('admin.partials.master')

@section('title')
    Lịch sử dự trù thiết bị tháng
@endsection

@section('content')
    <device-monthly-estimates-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.monthly-estimates.tracking'"></device-monthly-estimates-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-monthly-estimates.js') }}"></script>
@endsection
