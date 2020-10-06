@extends('admin.partials.master')

@section('title')
    Luân chuyển thiết bị
@endsection

@section('content')
    <device-round-robin-list></device-round-robin-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-round-robin.js') }}"></script>
@endsection
