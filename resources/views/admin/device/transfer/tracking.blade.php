@extends('admin.partials.master')

@section('title')
    Lịch sử kế hoạch điều chuyển thiết bị
@endsection

@section('content')
    <device-transfer-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.transfer.tracking'"></device-transfer-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-transfer.js') }}"></script>
@endsection
