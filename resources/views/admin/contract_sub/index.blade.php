@extends('admin.partials.master')

@section('title')
    Danh sách hợp đồng nhà thầu phụ
@endsection

@section('content')
    <contract-sub-list />
@endsection

@section('script')
    <script src="{{ asset('js/modules/contract-sub.js') }}"></script>
@endsection
