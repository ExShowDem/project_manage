@extends('admin.partials.master')

@section('title')
    Mua thiết bị
@endsection

@section('content')
    <device-purchase-list></device-purchase-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase.js') }}"></script>
@endsection
