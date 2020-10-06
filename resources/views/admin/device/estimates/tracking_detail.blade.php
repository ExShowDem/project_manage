@extends('admin.partials.master')

@section('title')
    Lịch sử dự trù thiết bị tổng
@endsection

@section('content')
    <device-estimates-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.estimates.tracking'"></device-estimates-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-estimates.js') }}"></script>
@endsection
