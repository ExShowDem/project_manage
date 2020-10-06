@extends('admin.partials.master')

@section('title')
    Thanh lý thiết bị
@endsection

@section('content')
    <device-clearance-list></device-clearance-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-clearance.js') }}"></script>
@endsection
