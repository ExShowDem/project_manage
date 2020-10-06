@extends('admin.partials.master')

@section('title')
    Thống kê thiết bị
@endsection

@section('content')
    <device-stats-list></device-stats-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-stats.js') }}"></script>
@endsection
