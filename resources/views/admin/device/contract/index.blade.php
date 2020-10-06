@extends('admin.partials.master')

@section('title')
    Hóa đơn mua thiết bị
@endsection

@section('content')
    <device-contract-list></device-contract-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-contract.js') }}"></script>
@endsection
