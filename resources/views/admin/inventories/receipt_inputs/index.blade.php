@extends('admin.partials.master')

@section('title')
    Danh sách nhập kho
@endsection

@section('content')
    <receipt-input-list></receipt-input-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-input.js') }}"></script>
@endsection
