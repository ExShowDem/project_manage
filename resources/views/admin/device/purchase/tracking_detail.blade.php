@extends('admin.partials.master')

@section('title')
    Lịch sử mua thiết bị
@endsection

@section('content')
    <device-purchase-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.purchase.tracking'"></device-purchase-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase.js') }}"></script>
@endsection
