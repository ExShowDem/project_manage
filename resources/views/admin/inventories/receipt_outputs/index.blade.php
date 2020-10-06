@extends('admin.partials.master')

@section('title')
    Danh sách xuất kho
@endsection

@section('content')
    <receipt-output-list></receipt-output-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-output.js') }}"></script>
@endsection
