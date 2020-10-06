@extends('admin.partials.master')

@section('title')
    Lịch sử xuất kho
@endsection

@section('content')
    <receipt-output-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}">
    </receipt-output-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-output.js') }}"></script>
@endsection
