@extends('admin.partials.master')

@section('title')
    Lịch sử danh sách  NTP
@endsection

@section('content')
    <sub-contractor-tracking :id="{{ $id ?? null }}" :tracking-route="'api.sub-contractors.tracking'">
    </sub-contractor-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/sub-contractor.js') }}"></script>
@endsection
