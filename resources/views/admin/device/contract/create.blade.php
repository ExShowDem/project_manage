@extends('admin.partials.master')

@section('title')
    Tạo hóa đơn mua thiết bị
@endsection

@section('content')
    <device-contract-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-contract-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-contract.js') }}"></script>
@endsection
