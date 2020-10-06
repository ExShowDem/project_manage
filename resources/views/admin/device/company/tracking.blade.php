@extends('admin.partials.master')

@section('title')
    Lịch sử thiết bị về công ty
@endsection

@section('content')
    <device-company-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.company.tracking'"></device-company-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-company.js') }}"></script>
@endsection
