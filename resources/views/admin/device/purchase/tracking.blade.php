@extends('admin.partials.master')

@section('title')
    Lịch sử mua thiết bị
@endsection

@section('content')
    <device-purchase-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.purchase.tracking'"></device-purchase-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase.js') }}"></script>
@endsection
