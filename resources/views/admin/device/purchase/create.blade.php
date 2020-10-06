@extends('admin.partials.master')

@section('title')
    Tạo mua thiết bị
@endsection

@section('content')
    <device-purchase-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-purchase-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-purchase.js') }}"></script>
@endsection
