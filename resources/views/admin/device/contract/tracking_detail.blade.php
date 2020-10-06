@extends('admin.partials.master')

@section('title')
    Lịch sử hóa đơn mua thiết bị
@endsection

@section('content')
    <device-contract-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.contract.tracking'"></device-contract-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-contract.js') }}"></script>
@endsection
