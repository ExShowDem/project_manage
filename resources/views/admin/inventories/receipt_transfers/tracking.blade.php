@extends('admin.partials.master')

@section('title')
    Lịch sử chuyển kho
@endsection

@section('content')
    <receipt-transfer-tracking :id="{{ $id ?? null }}" :tracking-route="'api.inventories.receipt-transfers.tracking'">
    </receipt-transfer-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-transfer.js') }}"></script>
@endsection
