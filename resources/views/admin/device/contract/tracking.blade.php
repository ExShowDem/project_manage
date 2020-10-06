@extends('admin.partials.master')

@section('title')
    Lịch sử hóa đơn mua thiết bị
@endsection

@section('content')
    <device-contract-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.contract.tracking'"></device-contract-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-contract.js') }}"></script>
@endsection
