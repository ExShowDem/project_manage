@extends('admin.partials.master')

@section('title')
    Xem thanh lý thiết bị
@endsection

@section('content')
    <device-clearance-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}" :is_show="true"></device-clearance-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-clearance.js') }}"></script>
@endsection
