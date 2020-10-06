@extends('admin.partials.master')

@section('title')
    Sửa yêu cầu mua mới thiết bị
@endsection

@section('content')
    <device-purchase-request-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-purchase-request-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase-request.js') }}"></script>
@endsection
