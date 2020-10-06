@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} hạng mục
    @else
        Tạo hạng mục
    @endif
@endsection

@section('content')
    @if (isset($id))
        <item-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                   :is_show="{{ isset($isShow) ? 'true' : 'false' }}" 
                   :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></item-form>
    @else
        <item-form :can_approve="{{ $canApprove }}" code="{{ $code }}" 
                    :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></item-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/item.js') }}"></script>
@endsection
