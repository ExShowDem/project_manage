@extends('admin.partials.master')

@section('title')
    Xem mua thiết bị
@endsection

@section('content')
    <device-purchase-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}" :is_show="true"></device-purchase-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase.js') }}"></script>
@endsection
