@extends('admin.partials.master')

@section('title')
    Danh sách hoá đơn mua vật tư
@endsection

@section('content')
    <invoice-list></invoice-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/invoice.js') }}"></script>
@endsection
