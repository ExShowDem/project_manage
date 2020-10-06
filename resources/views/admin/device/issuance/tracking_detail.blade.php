@extends('admin.partials.master')

@section('title')
    Lịch sử phiếu đề nghị cấp thiết bị
@endsection

@section('content')
    <device-issuance-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.devices.issuance.tracking'"></device-issuance-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-issuance.js') }}"></script>
@endsection
