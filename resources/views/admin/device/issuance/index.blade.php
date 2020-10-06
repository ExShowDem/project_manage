@extends('admin.partials.master')

@section('title')
    Phiếu đề nghị cấp thiết bị
@endsection

@section('content')
    <device-issuance-list></device-issuance-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-issuance.js') }}"></script>
@endsection
