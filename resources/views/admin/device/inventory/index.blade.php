@extends('admin.partials.master')

@section('title')
    Kiểm kê thiết bị
@endsection

@section('content')
    <device-inventory-list></device-inventory-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-inventory.js') }}"></script>
@endsection
