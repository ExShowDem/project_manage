@extends('admin.partials.master')

@section('title')
    Bảo trì, sửa chữa
@endsection

@section('content')
    <device-maintainence-list></device-maintainence-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-maintainence.js') }}"></script>
@endsection
