@extends('admin.partials.master')

@section('title')
    Sửa dự trù thiết bị tháng
@endsection

@section('content')
    <device-monthly-estimates-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-monthly-estimates-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-monthly-estimates.js') }}"></script>
@endsection
