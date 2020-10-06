@extends('admin.partials.master')

@section('title')
    Lịch sử nhập kho
@endsection

@section('content')
    <receipt-input-tracking :id="{{ $id ?? null }}" :tracking-route="'api.inventories.receipt-inputs.tracking'">
    </receipt-input-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-input.js') }}"></script>
@endsection
