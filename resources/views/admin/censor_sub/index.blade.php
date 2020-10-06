@extends('admin.partials.master')

@section('title')
    Danh sách Trình Duyệt Hồ Sơ Nhà Thầu Phụ
@endsection

@section('content')
    <censor-sub-list />
@endsection

@section('script')
    <script src="{{ asset('js/modules/censor-sub.js') }}"></script>
@endsection
