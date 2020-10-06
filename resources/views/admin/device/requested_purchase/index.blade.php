@extends('admin.partials.master')

@section('title')
    Mua thiết bị theo yêu cầu
@endsection

@section('content')
    <device-requested-purchase-list></device-requested-purchase-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-requested-purchase.js') }}"></script>
@endsection
