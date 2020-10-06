@extends('admin.partials.master')

@section('title')
    Trả thiết bị về công ty
@endsection

@section('content')
    <device-company-list></device-company-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-company.js') }}"></script>
@endsection
