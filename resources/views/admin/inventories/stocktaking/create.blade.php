@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} phiếu kiểm kê
    @else
        Tạo phiếu kiểm kê
    @endif
@endsection

@section('content')
    @if (isset($id))
        <stocktaking-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                          :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                          :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></stocktaking-form>
    @else
        <stocktaking-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></stocktaking-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
