@extends('admin.partials.master')

@section('title')
    Xuất bill
@endsection

@section('content')
    <device-bill-list></device-bill-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-bill.js') }}"></script>
@endsection
