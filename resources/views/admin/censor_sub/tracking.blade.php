@extends('admin.partials.master')

@section('title')
    Lịch sử danh sách hồ sơ NTP
@endsection

@section('content')
    <censor-sub-tracking :id="{{ $id ?? null }}" :tracking-route="'api.censor-sub.tracking'">
    </censor-sub-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/censor-sub.js') }}"></script>
@endsection
