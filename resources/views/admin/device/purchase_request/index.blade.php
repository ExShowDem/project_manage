@extends('admin.partials.master')

@section('title')
    Yêu cầu mua mới thiết bị
@endsection

@section('content')
    <device-purchase-request-list></device-purchase-request-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase-request.js') }}"></script>
@endsection
