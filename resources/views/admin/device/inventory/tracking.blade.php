@extends('admin.partials.master')

@section('title')
    Lịch sử kiểm kê thiết bị
@endsection

@section('content')
    <device-inventory-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.inventory.tracking'"></device-inventory-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-inventory.js') }}"></script>
@endsection
