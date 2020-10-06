@extends('admin.partials.master')

@section('title')
    Sửa kế hoạch điều chuyển thiết bị
@endsection

@section('content')
    <device-transfer-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-transfer-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-transfer.js') }}"></script>
@endsection
