@extends('admin.partials.master')

@section('title')
    Danh sách chuyển kho
@endsection

@section('content')
    <receipt-transfer-list></receipt-transfer-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-transfer.js') }}"></script>
@endsection
