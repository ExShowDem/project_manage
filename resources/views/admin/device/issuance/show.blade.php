@extends('admin.partials.master')

@section('title')
    Xem phiếu đề nghị cấp thiết bị
@endsection

@section('content')
    <device-issuance-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}" :is_show="true"></device-issuance-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-issuance.js') }}"></script>
@endsection
