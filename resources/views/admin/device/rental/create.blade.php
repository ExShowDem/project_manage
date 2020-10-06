@extends('admin.partials.master')

@section('title')
    Tạo cho thuê thiết bị
@endsection

@section('content')
    <device-rental-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-rental-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-rental.js') }}"></script>
@endsection
