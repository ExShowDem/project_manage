@extends('admin.partials.master')

@section('title')
    Sửa thiết bị về công ty
@endsection

@section('content')
    <device-company-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-company-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-company.js') }}"></script>
@endsection
