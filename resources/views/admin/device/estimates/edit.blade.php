@extends('admin.partials.master')

@section('title')
    Sửa dự trù thiết bị tổng
@endsection

@section('content')
    <device-estimates-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-estimates-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-estimates.js') }}"></script>
@endsection
