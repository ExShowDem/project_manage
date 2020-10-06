@extends('admin.partials.master')

@section('title')
    Lịch sử thanh lý thiết bị
@endsection

@section('content')
    <device-clearance-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.clearance.tracking'"></device-clearance-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-clearance.js') }}"></script>
@endsection
