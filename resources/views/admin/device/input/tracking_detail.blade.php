@extends('admin.partials.master')

@section('title')
    Lịch sử nhập thiết bị
@endsection

@section('content')
    <device-input-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.input.tracking'"></device-input-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-input.js') }}"></script>
@endsection
