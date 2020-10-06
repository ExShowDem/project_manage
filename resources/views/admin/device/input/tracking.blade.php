@extends('admin.partials.master')

@section('title')
    Lịch sử nhập thiết bị
@endsection

@section('content')
    <device-input-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.input.tracking'"></device-input-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-input.js') }}"></script>
@endsection
