@extends('admin.partials.master')

@section('title')
    Danh sách Đề nghị thanh toán NTP
@endsection

@section('content')
    <payment-order-list />
@endsection

@section('script')
    <script src="{{ asset('js/modules/payment-order.js') }}"></script>
@endsection
