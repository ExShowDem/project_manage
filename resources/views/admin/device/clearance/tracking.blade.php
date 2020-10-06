@extends('admin.partials.master')

@section('title')
    Lịch sử thanh lý thiết bị
@endsection

@section('content')
    <device-clearance-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.clearance.tracking'"></device-clearance-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-clearance.js') }}"></script>
@endsection
