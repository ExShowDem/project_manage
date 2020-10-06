@extends('admin.partials.master')

@section('title')
    Lịch sử nhà thầu phụ
@endsection

@section('content')
    <sub-contractor-form
     :log_id="{{ $log_id }}"
     :is_show="{{ true }}"></sub-contractor-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/sub-contractor.js') }}"></script>
@endsection
