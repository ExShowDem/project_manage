@extends('admin.partials.master')

@section('title')
    Danh mục thiết bị
@endsection

@section('content')
    <devices-list></devices-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/devices.js') }}"></script>
@endsection
