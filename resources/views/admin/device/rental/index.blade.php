@extends('admin.partials.master')

@section('title')
    Cho thuê thiết bị
@endsection

@section('content')
    <device-rental-list></device-rental-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-rental.js') }}"></script>
@endsection
