@extends('admin.partials.master')

@section('title')
    Lịch sử cho thuê thiết bị
@endsection

@section('content')
    <device-rental-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.rental.tracking'"></device-rental-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-rental.js') }}"></script>
@endsection
