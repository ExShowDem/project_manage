@extends('admin.partials.master')

@section('title')
    Lịch sử
@endsection

@section('content')
    <supplier-tracking :id="{{ $id ?? null }}" :tracking-route="'api.suppliers.tracking'">
    </supplier-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplier.js') }}"></script>
@endsection
