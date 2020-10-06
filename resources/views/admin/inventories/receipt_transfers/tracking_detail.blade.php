@extends('admin.partials.master')

@section('title')
    Lịch sử chuyển kho
@endsection

@section('content')
    <receipt-transfer-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}">
    </receipt-transfer-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-transfer.js') }}"></script>
@endsection
