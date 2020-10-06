@extends('admin.partials.master')

@section('title')
    Dự trù thiết bị tổng
@endsection

@section('content')
    <device-estimates-list></device-estimates-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-estimates.js') }}"></script>
@endsection
