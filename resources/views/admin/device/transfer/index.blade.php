@extends('admin.partials.master')

@section('title')
    Kế hoạch điều chuyển thiết bị
@endsection

@section('content')
    <device-transfer-list></device-transfer-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-transfer.js') }}"></script>
@endsection
