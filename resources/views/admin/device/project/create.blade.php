@extends('admin.partials.master')

@section('title')
    Tạo xuất thiết bị tới dự án
@endsection

@section('content')
    <device-project-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-project-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-project.js') }}"></script>
@endsection
