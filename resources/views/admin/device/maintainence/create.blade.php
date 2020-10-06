@extends('admin.partials.master')

@section('title')
    Tạo bảo trì, sửa chữa
@endsection

@section('content')
    <device-maintainence-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-maintainence-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-maintainence.js') }}"></script>
@endsection
