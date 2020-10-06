@extends('admin.partials.master')

@section('title')
    Dự trù thiết bị tháng
@endsection

@section('content')
    <device-monthly-estimates-list></device-monthly-estimates-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-monthly-estimates.js') }}"></script>
@endsection
