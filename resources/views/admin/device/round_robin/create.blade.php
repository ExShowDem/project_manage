@extends('admin.partials.master')

@section('title')
    Tạo luân chuyển thiết bị
@endsection

@section('content')
    <device-round-robin-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-round-robin-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-round-robin.js') }}"></script>
@endsection
