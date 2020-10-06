@extends('admin.partials.master')

@section('title')
    Tạo dự trù thiết bị tổng
@endsection

@section('content')
    <device-estimates-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-estimates-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-estimates.js') }}"></script>
@endsection
