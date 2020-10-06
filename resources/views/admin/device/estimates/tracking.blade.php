@extends('admin.partials.master')

@section('title')
    Lịch sử dự trù thiết bị tổng
@endsection

@section('content')
    <device-estimates-tracking :id="{{ $id ?? null }}" :tracking-route="'api.devices.estimates.tracking'"></device-estimates-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-estimates.js') }}"></script>
@endsection
