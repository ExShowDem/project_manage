@extends('admin.partials.master')

@section('title')
    Xem luân chuyển thiết bị
@endsection

@section('content')
    <device-round-robin-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-round-robin-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-round-robin.js') }}"></script>
@endsection
