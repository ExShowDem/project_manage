@extends('admin.partials.master')

@section('title')
    Lịch sử cho thuê thiết bị
@endsection

@section('content')
    <device-rental-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.rental.tracking'"></device-rental-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-rental.js') }}"></script>
@endsection
