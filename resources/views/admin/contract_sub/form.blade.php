@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} hợp đồng nhà thầu phụ
    @else
        Tạo hợp đồng nhà thầu phụ
    @endif
@endsection

@section('content')
    @if (isset($id))
        <contract-sub-form :id="{{ $id ?? null }}"
                           :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                           :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></contract-sub-form>
    @else
        <contract-sub-form :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></contract-sub-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/contract-sub.js') }}"></script>
@endsection
