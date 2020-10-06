@extends('admin.partials.master')

@section('title')
    Lịch sử danh sách hợp đồng NTP
@endsection

@section('content')
    <contract-sub-tracking :id="{{ $id ?? null }}" :tracking-route="'api.contract-sub.tracking'">
    </contract-sub-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/contract-sub.js') }}"></script>
@endsection
