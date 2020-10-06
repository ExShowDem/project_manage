@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} Đề nghị thanh toán NTP
    @else
        Tạo Đề nghị thanh toán NTP
    @endif
@endsection

@section('content')
    @php $types = json_encode(\App\Enums\PaymentOrderType::toSelectArray()) @endphp
    @if (isset($id))
        <payment-order-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :types="{{ $types }}"
                            :is_show="{{ isset($isShow) ? 'true' : 'false' }}"></payment-order-form>
    @else
        <payment-order-form code="{{ $code }}" :can_approve="{{ $canApprove }}" :types="{{ $types }}"></payment-order-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/payment-order.js') }}"></script>
@endsection
