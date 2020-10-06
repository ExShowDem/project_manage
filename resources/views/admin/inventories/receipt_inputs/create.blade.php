@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} nhập kho
    @else
        Tạo nhập kho
    @endif
@endsection

@section('content')
    @if (isset($id))
        <receipt-input-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                            :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                            :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-input-form>
    @elseif (isset($invoice))
        <receipt-input-form :can_approve="{{ $canApprove }}" :invoice="{{ $invoice }}"
                            code="{{ $code }}"
                            :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-input-form>
    @else
        <receipt-input-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-input-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-input.js') }}"></script>
@endsection
