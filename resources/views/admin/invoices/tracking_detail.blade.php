@extends('admin.partials.master')

@section('title')
    Lịch sử hoá đơn mua vật tư
@endsection

@section('content')
    <invoice-tracking-detail
            :id="{{ $id ?? null }}"
            :log_id="{{ $log_id }}"
            :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
            :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}">
    </invoice-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/invoice.js') }}"></script>
@endsection
