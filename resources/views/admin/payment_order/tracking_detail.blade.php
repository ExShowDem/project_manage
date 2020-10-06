@extends('admin.partials.master')

@section('title')
    Lịch sử Đề nghị thanh toán NTP
@endsection

@section('content')
    @php $types = json_encode(\App\Enums\PaymentOrderType::toSelectArray()) @endphp
    <payment-order-form :log_id="{{ $log_id }}" :is_show="{{ true }}" :types="{{ $types }}"></payment-order-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/payment-order.js') }}"></script>
@endsection
