@extends('admin.partials.master')

@section('title')
    Lịch sử yêu cầu mua mới thiết bị
@endsection

@section('content')
    <device-purchase-request-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.purchase-request.tracking'"></device-purchase-request-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase-request.js') }}"></script>
@endsection
