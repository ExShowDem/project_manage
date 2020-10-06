@extends('admin.partials.master')

@section('title')
    Tạo nhập thiết bị
@endsection

@section('content')
    <device-input-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-input-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-input.js') }}"></script>
@endsection
