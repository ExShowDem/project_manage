@extends('admin.partials.master')

@section('title')
    Lịch sử nhập kho
@endsection

@section('content')
    <receipt-input-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}">
    </receipt-input-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-input.js') }}"></script>
@endsection
