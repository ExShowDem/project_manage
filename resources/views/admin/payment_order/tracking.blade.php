@extends('admin.partials.master')

@section('title')
    Lịch sử đề nghị thanh toán NTP
@endsection

@section('content')
    <payment-order-tracking :id="{{ $id ?? null }}" :tracking-route="'api.payment-order.tracking'">
    </payment-order-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/payment-order.js') }}"></script>
@endsection
