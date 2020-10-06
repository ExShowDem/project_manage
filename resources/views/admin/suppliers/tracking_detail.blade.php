@extends('admin.partials.master')

@section('title')
    Lịch sử
@endsection

@section('content')
    <supplier-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.suppliers.tracking'">
    </supplier-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplier.js') }}"></script>
@endsection
