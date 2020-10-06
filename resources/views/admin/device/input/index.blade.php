@extends('admin.partials.master')

@section('title')
    Nhập thiết bị
@endsection

@section('content')
    <device-input-list></device-input-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-input.js') }}"></script>
@endsection
