@extends('admin.partials.master')

@section('title')
    Lịch sử hợp đồng nhà thầu phụ
@endsection

@section('content')
    <contract-sub-form :log_id="{{ $log_id }}"
                       :is_show="{{ true }}"></contract-sub-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/contract-sub.js') }}"></script>
@endsection
