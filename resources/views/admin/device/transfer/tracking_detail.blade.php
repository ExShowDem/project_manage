@extends('admin.partials.master')

@section('title')
    Lịch sử kế hoạch điều chuyển thiết bị
@endsection

@section('content')
    <device-transfer-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.transfer.tracking'"></device-transfer-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-transfer.js') }}"></script>
@endsection
