@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} nhà thầu phụ
    @else
        Tạo nhà thầu phụ
    @endif
@endsection

@section('content')
    @if (isset($id))
        <sub-contractor-form :id="{{ $id ?? null }}"
                             :is_show="{{ isset($isShow) ? 'true' : 'false' }}"></sub-contractor-form>
    @else
        <sub-contractor-form></sub-contractor-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/sub-contractor.js') }}"></script>
@endsection
