@extends('admin.partials.master')

@section('title')
    Lịch sử phiếu đề nghị cấp thiết bị
@endsection

@section('content')
    <device-issuance-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.issuance.tracking'"></device-issuance-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-issuance.js') }}"></script>
@endsection
