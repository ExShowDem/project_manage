@extends('admin.partials.master')

@section('title')
    Lịch sử xuất kho
@endsection

@section('content')
    <receipt-output-tracking :id="{{ $id ?? null }}" :tracking-route="'api.inventories.receipt-outputs.tracking'">
    </receipt-output-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-output.js') }}"></script>
@endsection
