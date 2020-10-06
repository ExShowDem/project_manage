@extends('admin.partials.master')

@section('title')
    Lịch sử bảo trì, sửa chữa
@endsection

@section('content')
    <device-maintainence-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.maintainence.tracking'"></device-maintainence-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-maintainence.js') }}"></script>
@endsection
