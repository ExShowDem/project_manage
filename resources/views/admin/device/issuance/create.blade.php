@extends('admin.partials.master')

@section('title')
    Tạo phiếu đề nghị cấp thiết bị
@endsection

@section('content')
    <device-issuance-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-issuance-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-issuance.js') }}"></script>
@endsection
