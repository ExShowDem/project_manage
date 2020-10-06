@extends('admin.partials.master')

@section('title')
    Tạo thanh lý thiết bị
@endsection

@section('content')
    <device-clearance-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-clearance-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-clearance.js') }}"></script>
@endsection
