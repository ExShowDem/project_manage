@extends('admin.partials.master')

@section('title')
    Xem nhập thiết bị
@endsection

@section('content')
    <device-input-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}" :is_show="true"></device-input-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-input.js') }}"></script>
@endsection
