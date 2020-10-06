@extends('admin.partials.master')

@section('title')
    Xem hóa đơn mua thiết bị
@endsection

@section('content')
    <device-contract-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}" :is_show="true"></device-contract-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-contract.js') }}"></script>
@endsection
