@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} chuyển kho
    @else
        Tạo chuyển kho
    @endif
@endsection

@section('content')
    @if (isset($id))
        <receipt-transfer-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                               :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                               :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-transfer-form>
    @else
        <receipt-transfer-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-transfer-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-transfer.js') }}"></script>
@endsection
