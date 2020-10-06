@extends('admin.partials.master')
@section('title')
    Thêm phiếu nhập kho
@endsection

@section('content')
    <ticket-import-form :invoice-id="{{$id}}"></ticket-import-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/ticket-import.js') }}"></script>
@endsection
