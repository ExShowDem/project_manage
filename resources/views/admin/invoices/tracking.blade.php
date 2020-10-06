@extends('admin.partials.master')

@section('title')
    Lịch sử hoá đơn mua vật tư
@endsection

@section('content')
    <invoice-tracking :id="{{ $id ?? null }}" :tracking-route="'api.invoices.tracking'">
    </invoice-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/invoice.js') }}"></script>
@endsection
